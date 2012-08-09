<?php
	class smartPolisSettings {
		private $settingsFilePath = null;
		private $settings = null;

		public function __construct() {
			if ( is_null($this->settingsFilePath) ) {
				$this->settingsFilePath = realpath(dirname(__FILE__)) . '/settings.php';
			}
			if ( is_null($this->settings) && file_exists($this->settingsFilePath) ) {
				include($this->settingsFilePath);
				$this->settings = unserialize(base64_decode($SMARTPOLIS_SETTINGS));
			}
		}

		public function get($key="") {
			if ( ! is_null($this->settings) && isset($this->settings[$key]) ) {
				return $this->settings[$key];
			}
			return "";
		}

		public function set($array = array()) {
			foreach($array as $key=>$value) {
				if (strpos($key, 'smartpolis_')===0) {
					$key = substr($key, 11);
					$this->settings[$key] = $value;
				}
			}
		}

		public function save() {
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			file_put_contents($this->settingsFilePath, "<?php\n");
			file_put_contents($this->settingsFilePath, '$SMARTPOLIS_SETTINGS = "', FILE_APPEND);
			file_put_contents($this->settingsFilePath, base64_encode(serialize($this->settings)), FILE_APPEND);
			file_put_contents($this->settingsFilePath, '";' . "\n", FILE_APPEND);
			file_put_contents($this->settingsFilePath, "?>", FILE_APPEND);
		}
		
	}
?>
