<?php
  include_once( SMARTPOLIS_PLUGIN_DIR . 'php/smartpolis.class.settings.php' );
  if ( class_exists( 'smartpolisSettings' ) ) {
    $settings = new smartpolisSettings();
  } else {
    echo '<h2>Не удалось загрузить класс работы с настройками!</h2>';
  }
?>
<div id="smartpolis_admin_options_page">
  <h1>Настройки</h1>
  <form method="post">
    <div">
      <h2>Подключение сервиса расчетов</h2>
      <table>
        <tr>
          <td>
            <input type="radio" name="smartpolis_auth_type" value="by_ip"<?php echo $settings->get('smartpolis_auth_type')=='by_ip'?' checked':'';?>/>Авторизация по ip
          </td>
          <td>
            <input type="radio" name="smartpolis_auth_type" value="by_token"<?php echo $settings->get('smartpolis_auth_type')=='by_token'?' checked':'';?>/>Авторизация по ключу<br/>
            <input type="text" name="smartpolis_auth_token"<?php echo $settings->get('smartpolis_auth_token')==''?'':' value="' . $settings->get('smartpolis_auth_token') . '"';?>/><br/>
            Введите секретный ключ, полученный в настройках Вашего<br/>аккаунта на сайте <a href="" target="_blank">умный-полис.рф</a>
          </td>
        </tr>
      </table>
    </div>
    <div">
      <h2>Режим работы</h2>
      <table>
        <tr>
          <td>
            <input type="radio" name="smartpolis_show_type" value="form_after_show" <?php echo $settings->get('smartpolis_show_type')=='form_after_show'?' checked':'';?>/>Заявка после<br/>отображения тарифов
          </td>
          <td>
            <input type="radio" name="smartpolis_show_type" value="show_after_form" <?php echo $settings->get('smartpolis_show_type')=='show_after_form'?' checked':'';?>/>Отображение тарифов после<br/>оформления заявки
          </td>
          <td>
            <input type="radio" name="smartpolis_show_type" value="send_by_letter" <?php echo $settings->get('smartpolis_show_type')=='send_by_letter'?' checked':'';?>/>Отправка предложения<br/>с тарифами на почту
          </td>
        </tr>
      </table>
    </div>
    <div">
      <h2>Сообщения</h2>
      <table>
        <tr>
          <td>
            Текст перед калькулятором:
          </td>
          <td>
            <textarea name="smartpolis_message_before_button"><?php echo $settings->get('smartpolis_message_before_button')==''?'':$settings->get('smartpolis_message_before_button');?></textarea>
          </td>
        </tr>
        <tr>
          <td>
            Поле 2
          </td>
          <td>
            <textarea name="smartpolis_header_before_form"><?php echo $settings->get('smartpolis_message_before_button')==''?'':$settings->get('smartpolis_header_before_form');?></textarea>
          </td>
        </tr>
        <tr>
          <td>
            Текст перед результатами расчета<br/>
            <span>(появляется после нажатия на кнопку<br/>рассчитать, не отображается в третьем режиме)</span>
          </td>
          <td>
            <textarea name="smartpolis_message_before_form"><?php echo $settings->get('smartpolis_message_before_form')==''?'':$settings->get('smartpolis_message_before_form');?></textarea>
          </td>
        </tr>
      </table>
    </div>
    <div>
      <button type="submit">Сохранить</button>
    </div>
  </form>
<?php
  
?>
</div>
