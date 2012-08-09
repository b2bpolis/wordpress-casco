<?php
/*
Plugin Name: SmartPolis
Plugin URI: http://cmios.ru/kasko.html
Description: Умный Полис
Version: 0.0.1
Author: Руслан Шарифуллин
Author URI: http://vk.com/ruslan1980
*/
?>
<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	define('SMARTPOLIS_PLUGIN_DIR', plugin_dir_path(__FILE__));
	define('SMARTPOLIS_PLUGIN_URL', plugin_dir_url(__FILE__));

	if ( is_admin() ) {
		include_once( SMARTPOLIS_PLUGIN_DIR . 'php/smartpolis.class.admin.php' );
		if ( class_exists('smartPolisAdmin') ) {
			$smartPolis = new smartPolisAdmin();
		}
	} else {
		include_once( SMARTPOLIS_PLUGIN_DIR . 'php/smartpolis.class.php' );
		if ( class_exists('smartPolis') ) {
			$smartPolis = new smartPolis();
		}
	}

?>