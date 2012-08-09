<?php
	class smartPolisAdmin {
		public function __construct() {
			//register_activation_hook( __FILE__, array( 'VK_api', 'install' ) );
			//register_deactivation_hook( __FILE__, array( 'VK_api', 'pause' ) );
			//register_uninstall_hook( __FILE__, array( 'VK_api', 'deinstall' ) );
			add_action( 'admin_menu', array( &$this, 'create_menu' ), 1 ); /* Административное меню */
			add_action( 'admin_init', array( &$this, 'add_css' ) ); /* Регистрация стилей для административной части */
		}

		public function create_menu() {
			$main = add_menu_page( 
				'Умный полис',
				'Умный полис',
				8,
				'smartpolis_admin_settings',
				array( &$this, 'settings_page' ),
				'http://cmios.ru/favicon.ico'
			);
			$first = add_submenu_page( 
				'smartpolis_admin_settings', 
				'Умный полис',
				'Страховые компании',
				8,
				'smartpolis_admin_settings',
				array( &$this, 'settings_page' ) 
			);
			$second = add_submenu_page( 
				'smartpolis_admin_settings',
				'Умный полис',
				'Настройки',
				8,
				'smartpolis_admin_options',
				array( &$this, 'options_page' ) 
			);
			add_action( 'admin_print_styles-' . $main, array( &$this, 'add_css_admin' ) ); /* admin css enqueue */
			add_action( 'admin_print_styles-' . $first, array( &$this, 'add_css_admin' ) ); /* admin css enqueue */
			add_action( 'admin_print_styles-' . $second, array( &$this, 'add_css_admin' ) ); /* admin css enqueue */
		}

		public function add_css() {
			wp_register_style( 'smartpolis_admin', SMARTPOLIS_PLUGIN_URL . 'css/smartpolis.css');
		}

		public function add_css_admin () {
			wp_enqueue_style( 'smartpolis_admin' );
		}

		public function settings_page() {
			$name = 'smartpolis_settings_page.php';
			$path = locate_template( $name );

			if ( ! $path ) {
				$path = SMARTPOLIS_PLUGIN_DIR . "/default-templates/admin/$name";
			}
			load_template($path);
		}

		public function options_page() {
			$name = 'smartpolis_options_page.php';
			$path = locate_template( $name );

			if ( ! $path ) {
				$path = SMARTPOLIS_PLUGIN_DIR . "/default-templates/admin/$name";
			}
			load_template($path);
		}
	}
?>
