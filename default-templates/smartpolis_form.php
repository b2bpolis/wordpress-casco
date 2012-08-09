<?php
	include_once( SMARTPOLIS_PLUGIN_DIR . 'php/smartpolis.class.settings.php');
	if ( class_exists('smartPolisSettings') ) {
		$settings = new smartPolisSettings();
		$smartpolis_show_type = $settings->get('smartpolis_show_type');
	}
?>
<div class="smartpolis_before_info">
	<?php echo $settings->get('smartpolis_message_before_button'); ?>
</div>
<div class="blok">
	<form id="smartpolis_car_form">
		<input type="hidden" name="type" value="getRequarList" />
		<input type="hidden" name="smartpolis_show_type" value="<?php echo $smartpolis_show_type; ?>" />
		<table class="table1" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><div class="bl w100"><label>Марка автомобиля</label><select class="w100" name="smartpolis_car_marks" id="smartpolis_car_marks"><option></option></select></div></td>
				<td><div class="bl w100"><label>Модель автомобиля</label><select class="w100" name="smartpolis_car_models" id="smartpolis_car_models"><option></option></select></div></td>
				<td><div class="bl w100"><label>Модификация автомобиля</label><select class="w100" name="smartpolis_car_modifications" id="smartpolis_car_modifications"><option></option></select></div></td>
			</tr>
			<tr>
				<td><div class="bl w100"><label>Стоимость автомобиля</label><input type="text" class="pole w100"  id="smartpolis_car_cost" name="smartpolis_car_cost" value="0" /></div></td>
				<td><div class="bl w100"><label>Год выпуска автомобиля</label><select class="w100"  name="smartpolis_car_manufacturing_year" id="smartpolis_car_manufacturing_year">
				<?php
					for( $i=date('Y'); $i>=2005; $i--) {
						echo '<option value="' . $i . '">' . $i . ' г.в.</option>';
					}
				?>
				</select></div>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><div class="bl w100"><label>Количество водителей</label><select class="w100" name="smartpolis_drivers_count" id="smartpolis_drivers_count">
					<option value="1" selected>Один</option>
					<option value="2">Два</option>
					<option value="3">Три</option>
					<option value="4">Четыре</option>
					<option value="5">Пять</option>
					<option value="multiply">Мультидрайв</option>
				</select></div></td>
				<td colspan="2" id="smartpolis_drivers_set"></td>
			</tr>
		</table>
		<?php
			if ( $smartpolis_show_type == 'show_after_form' || $smartpolis_show_type== 'send_by_letter') { ?>
			<div class="b-gray" id="smartpolis_contact_form">
				<div class="left">
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2"><label>Ваше имя</label><input name="" type="text" class="pole w100" id="smartpolis_client_name" /></td>
						</tr>
						<tr>
							<td><label>Email</label><input name="" type="text" class="pole w100" id="smartpolis_client_email"/></td>
							<td><label>Контактный телефон</label><input name="" type="text" class="pole w100" id="smartpolis_client_phone" /></td>
						</tr>
					</table>
				</div><!--end left-->
				<div class="right">
					<?php echo $settings->get('smartpolis_header_before_form'); ?>
				</div><!--end right-->
			</div><!--end b-gray-->
			<?php
			}
		?>
		<div class="b-rasch">
			<input class="but" name="" type="submit" value=" " />
		</div><!--end b-rasch-->
		<br />
	</form>
	<div id='smartpolis_message_before_form'>
		<?php echo $settings->get('smartpolis_message_before_form'); ?>
		<br/>
		<span id="smartpolis_wait_count_result"></span>
	</div>
	<div class="table-tarif" id="smartpolis_result">
	</div><!--end table-tarif-->
</div><!--end blok-->
	<div id='smartpolis_order_form'>
		<table>
			<tr>
				<td>Ваше имя<br/><input type="text" /></td>
			</tr>
			<tr>
				<td>Контактный телефон<br/><input type="text" /></td>
			</tr>
			<tr>
				<td>Дата, с которой Вы хотите застраховать автомобиль<br/><input type="text" /></td>
			</tr>
			<tr>
				<td>Мне будет удобно:<br/>
				<input type="radio" />Подъехать к Вам в офис и забрать оформленный полис КАСКО/ОСАГО<br/>
				<input type="radio" />Получить полис по адресу:<br/>
				<textarea></textarea><br/>
				<span>(Доставка полиса КАСКО производится бесплатно)</span>
				</td>
			</tr>
			<tr>
				<td>Примечания к заказу:<br/>
				<textarea></textarea></td>
			</tr>
			<tr>
				<td><button type="submit">Отправить</button></td>
			</tr>
			
		</table>
	</div>
