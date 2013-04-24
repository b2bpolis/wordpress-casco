function smartpolis() {
  var self = this;
  var smartpolis_show_type = jQuery('#smartpolis_car_form input[name=smartpolis_show_type]').val();
  var smartpolis_ajax_url = 'wp-content/plugins/wordpress-casco-master/php/ajax.php';
  self.updateCarMarks = function() {
    jQuery('#smartpolis_car_marks').children().remove();
    jQuery.getJSON(smartpolis_ajax_url, {'type':'car_marks'}, function(r) {
      if ( r.length > 0 ) {
        jQuery(r).each(function() {
          jQuery('<option value="' + this.id + '">' + this.title + '</option>').appendTo('#smartpolis_car_marks');
        });
      }
      self.updateCarModels();
    });
    jQuery('#smartpolis_order_form_close').live('click', function () {
      jQuery('#smartpolis_order_form').hide(200);
    });
  }

  self.updateCarModels = function() {
    jQuery('#smartpolis_car_models').children().remove();
    jQuery.getJSON(smartpolis_ajax_url, {'type':'car_models', 'car_mark':jQuery('#smartpolis_car_marks').val()}, function(r) {
      if ( r.length > 0 ) {
        jQuery(r).each(function() {
          jQuery('<option value="' + this.id + '">' + this.title + '</option>').appendTo('#smartpolis_car_models');
        });
      }
      self.updateCarModifications();
    });
  }

  self.updateCarModifications = function() {
    jQuery('#smartpolis_car_modifications').children().remove();
    jQuery.getJSON(smartpolis_ajax_url, {'type':'car_modifications', 'car_model':jQuery('#smartpolis_car_models').val()}, function(r) {
      if ( r.length > 0 ) {
        jQuery(r).each(function() {
          jQuery('<option value="' + this.id + '">' + this.title + '</option>').appendTo('#smartpolis_car_modifications');
        });
      }
    });
  }

  self.updateDriversCount = function() {
    val = jQuery('#smartpolis_drivers_count').val();
    h = '';
    if (val == 'multiply') {
      h+='Количество водителей не ограничено';
    } else {
      for(i=1; i<=val; i++) {
        h+='<div class="row">';
        h+='<div class="bl">';
        h+='<label class="smartpolis_car_form_label">Возраст</label><select class="smartpolis_car_form_age" name="car_driver_age[]">';
        for(z=18; z<=60; z++) {
          h+='<option value="' + z + '">' + z + ' лет</option>';
        }
        h+='</select>';
        h+='</div><!--end bl-->';
        h+='<div class="bl">';
        h+='<label class="smartpolis_car_form_label">Стаж</label><select class="smartpolis_car_form_experience" name="car_driver_prof[]">';
        h+='<option value="0">нет</option>';
        for(z=1; z<=5; z++) {
          h+='<option value="' + z + '">' + z + ' лет</option>';
        }
        h+='<option value="5">более 5 лет</option>';
        h+='</select>';
        h+='</div><!--end bl-->';
        h+='<div class="bl">';
        h+='<label class="smartpolis_car_form_label">Пол</label><select class="smartpolis_car_form_gender" name="car_driver_gender[]">';
        h+='<option value="M">Мужской</option>';
        h+='<option value="F">Женский</option>';
        h+='</select>';
        h+='</div><!--end bl-->';
        h+='</div><!--end row-->';
      }
    }
    jQuery('#smartpolis_drivers_set').html(h);
  }

  self.prepareForm = function() {
    // Кнопка продолжить или расчитать, в зависимости от режима работы плагина
    self.prepareSubmitButton();
    // Валидация формы на отправке и отправка запроса
    jQuery('#smartpolis_car_form').bind('submit', function() {
      if ( self.hasErrorForm() ) {
        return false;
      }
      self.getResult();
      return false;
    });

    // Изменение количества водителей
    self.updateDriversCount();
    jQuery('#smartpolis_drivers_count').bind('change', self.updateDriversCount);
    // Сразу запрашиваем марки автомобилей
    self.updateCarMarks();
    jQuery('#smartpolis_car_marks').bind('change', self.updateCarModels);
    jQuery('#smartpolis_car_models').bind('change', self.updateCarModifications);
  }

  self.getResult = function() {
    jQuery('#smartpolis_message_before_form').css('display', 'block');
    jQuery('#smartpolis_car_form input:submit').attr('disabled', 'disabled').addClass('disabled');

    jQuery.getJSON(smartpolis_ajax_url, jQuery('#smartpolis_car_form').serialize(), function(r) {
      var count_result = r.length;
      var headers_table_was_show = false;
      jQuery('#smartpolis_wait_count_result').html('Осталось расчитать: ' + count_result);
      jQuery('#smartpolis_result').children().remove();
      jQuery(r).each(function() {
        var company = this;
        jQuery.getJSON(smartpolis_ajax_url, { 'type':'getResult', 'id':company.id }, function(r){
          count_result--;
          jQuery('#smartpolis_wait_count_result').html('Осталось расчитать: ' + count_result);
          if ( smartpolis_show_type != 'send_by_letter' ) {
            if ( ! headers_table_was_show ) {
              text = '<div class="pol1"></div>\
                  <div class="pol2"></div>\
                  <div class="row-th">\
                  <div class="td1"></div>\
                  <div class="td2">Тариф у страховой</div>\
                  <div class="td3">Тариф у нас</div>\
                  </div><!--end row-th-->';
              jQuery(text).appendTo('#smartpolis_result');
              headers_table_was_show = true;
            }
            if (r.sum && r.sum!=0) {
              text ='<div class="row">\
                  <div class="td1"><img alt="" src="http://casco.cmios.ru/' + r.logo + '" style="width: 100px; height: 40px;margin-top: 7px;"/></div>\
                  <div class="td2">' + r.sum + ' руб.</div>\
                  <div class="td3">' + r.our_sum + ' руб. (-' + r.discount + '%) <a class="but" href="#" onclick="javascript: jQuery(\'#smartpolis_order_form\').css(\'display\', \'block\'); return false;"></a></div>\
                  </div><!--end row-->';


/*              text = '<tr>';
              text += '<td class="logo"><img src="http://casco.cmios.ru/' + r.logo + '" /></td>';
              text += '<td class="company_sum">' + r.sum + '</td>';

              if (r.sum == r.our_sum) {
                text += '<td class="our_sum">' + r.our_sum + '</td>';
                text += '<td class="order"><button type="submit" onclick="javascript: jQuery(\'#smartpolis_order_form\').css(\'display\', \'block\'); return false;">Купить ' + r.result_id + '</td>';
              } else {
                text += '<td class="our_sum">' + r.our_sum + ' (- ' + r.discount + ' %)</td>';
                text += '<td class="order"><button type="submit" onclick="javascript: jQuery(\'#smartpolis_order_form\').css(\'display\', \'block\'); return false;">Купить со скидкой ' + r.result_id + '</td>';
              }
              text += '</tr>';
*/              jQuery(text).appendTo('#smartpolis_result');
            }

          }
          else if ( count_result == 0 ) {
            jQuery('#smartpolis_car_form input:submit').removeAttr('disabled').removeClass('disabled');
            jQuery('#smartpolis_message_before_form').html('На указанный Вами email была отправлена ссылка на наше комерческое предложение.');
          }
        });
      });
    });
    console.log(smartpolis_show_type);
    return false;
  }

  self.prepareSubmitButton = function() {
    if ( smartpolis_show_type != 'form_after_show' ) {
      jQuery('#smartpolis_car_form input:submit').html('Продолжить');
    }
  }

  self.hasErrorRequiredFields = function() {
    error = false;
    jQuery('#smartpolis_car_marks, #smartpolis_car_models, #smartpolis_car_modifications, #smartpolis_car_cost, #smartpolis_car_manufacturing_year').removeClass('error');
    car_cost = jQuery('#smartpolis_car_cost').val();
    if ( ! parseInt(car_cost) || parseInt(jQuery('#smartpolis_car_cost').val()) == 0) {
      jQuery('#smartpolis_car_cost').addClass('error');
      return true;
    }
    return error;
    //console.log(jQuery('#smartpolis_car_marks').val());
    //console.log(jQuery('#smartpolis_car_models').val());
    //console.log(jQuery('#smartpolis_car_modifications').val());
    //console.log(jQuery('#smartpolis_car_cost').val());
    //console.log(jQuery('#smartpolis_car_manufacturing_year').val());
  }

  self.hasErrorContactFormRequiredFields = function() {
    error = false;
    jQuery('#smartpolis_client_name, #smartpolis_client_email, #smartpolis_client_phone').removeClass('error');
    smartpolis_client_name = jQuery('#smartpolis_client_name').val();
    smartpolis_client_email = jQuery('#smartpolis_client_email').val();
    smartpolis_client_phone = jQuery('#smartpolis_client_phone').val();
    if (smartpolis_client_name=='') {
      jQuery('#smartpolis_client_name').addClass('error');
      return true;
    }
    if (smartpolis_client_email=='') {
      jQuery('#smartpolis_client_email').addClass('error');
      return true;
    }
    if (smartpolis_client_phone=='') {
      jQuery('#smartpolis_client_phone').addClass('error');
      return true;
    }
    return error;
  }

  self.hasErrorForm = function() {
    if ( self.hasErrorRequiredFields() ) {
      return true;
    }

    if ( smartpolis_show_type != 'form_after_show' ) {
      if (jQuery('#smartpolis_contact_form:visible').length==0) {
        jQuery('#smartpolis_car_form button:submit').html('Расчитать');
        jQuery('#smartpolis_contact_form').css('display', 'block');
        return true;
      }

      if ( self.hasErrorContactFormRequiredFields() ) {
        return true;
      }
    }

    return false;
  }

  self.init = function() {
    jQuery.ajax({
      async: false
    });
    self.prepareForm();
  }

  self.init();
};

jQuery(document).ready(smartpolis);
/*

jQuery(document).ready(function() {
  jQuery('#car_form').bind('submit', function() {
    jQuery.getJSON(smartpolis_ajax_url, jQuery(this).serialize(), function(r) {
      jQuery('#smartpolis_message_before_form').css('display', 'block');
      jQuery('#result').children().remove();
      text = '<thead>\
          <th>&nbsp;</th>\
          <th>Тариф у страховой</th>\
          <th>Тариф у нас</th>\
          <th>&nbsp</th>\
        </thead>\
        <tbody>\
        </tbody>\
      ';
      jQuery(text).appendTo('#result');
      jQuery(r).each(function() {
        var company = this;
        jQuery.getJSON(smartpolis_ajax_url, { 'type':'getResult', 'id':company.id }, function(r){
          console.log(r);
          if (r.sum && r.sum!=0) {
            text = '<tr>';
            text += '<td class="logo"><img src="http://casco.cmios.ru/' + r.logo + '" /></td>';
            text += '<td class="company_sum">' + r.sum + '</td>';
            
            if (r.sum == r.our_sum) {
              text += '<td class="our_sum">' + r.our_sum + '</td>';
              text += '<td class="order"><button type="submit">Купить ' + r.result_id + '</td>';
            } else {
              text += '<td class="our_sum">' + r.our_sum + ' (- ' + r.discount + ' %)</td>';
              text += '<td class="order"><button type="submit">Купить со скидкой ' + r.result_id + '</td>';
            }
            text += '</tr>';
            jQuery(text).appendTo('#result tbody');
          }
        });
      });
    });
    return false;
  });
});

function isValidate() {
  var error = false;
  return error;
}*/
