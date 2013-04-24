<?php
  class smartPolisSettings {
    private $settingsFile = null;
    private $settings = null;
    
    public function __construct() {
      //error_reporting(E_ALL);
      //ini_set('display_errors', 1);
      if ( is_null($this->settingsFile) ) {
        $this->settingsFile = SMARTPOLIS_PLUGIN_DIR . 'php/settings.php';
      }
      if ( is_null($this->settings) && file_exists($this->settingsFile) ) {
        include($this->settingsFile);
        $this->settings = unserialize(base64_decode($SMARTPOLIS_SETTINGS));
      }
      
      if ( isset($_POST) && count($_POST) > 0 && !isset($_POST['action']) ) {
        $this->set($_POST);
      }
    }

    public function updateCompanies() {
      include_once( SMARTPOLIS_PLUGIN_DIR . 'php/smartpolis.class.casco.api.php');
      if ( class_exists('smartpolisCascoApi') ) {
        $api = new smartpolisCascoApi();
        $companies = json_decode($api->getCompanies());
        foreach($companies as $company) {
          $this->settings['smartpolis_companies'][$company->id]['object'] = $company;
          if ( !isset($this->settings['smartpolis_companies'][$company->id]['params']) ) {
            $this->settings['smartpolis_companies'][$company->id]['params'] = array();
            $this->settings['smartpolis_companies'][$company->id]['params']['active'] = 'false';
            $this->settings['smartpolis_companies'][$company->id]['params']['discount'] = '0.00';
          }
        }
        $this->save();
      }
    }

    public function saveCompanies() {
      if ( count($this->settings['smartpolis_companies']) > 0 ) {
        foreach($this->settings['smartpolis_companies'] as $id=>$company) {
          $active = isset($_POST['smartpolis_companies'][$id]['params']['active']) && $_POST['smartpolis_companies'][$id]['params']['active'] == 'true';
          $this->settings['smartpolis_companies'][$id]['params']['active'] = $active ? 'true' : 'false';
          $this->settings['smartpolis_companies'][$id]['params']['discount'] = $_POST['smartpolis_companies'][$id]['params']['discount'];
        }
      }
      $this->save();
    }

    public function checkConnectionsParams() {
      if ( !isset($this->settings['smartpolis_auth_type']) || empty($this->settings['smartpolis_auth_type']) ) {
        return false;
      }
      return true;
    }

    public function getCompanies() {
      if ( isset($this->settings['smartpolis_companies']) && count($this->settings['smartpolis_companies']) > 0 ) {
        return $this->settings['smartpolis_companies'];
      } else {
        $this->set(array('smartpolis_companies'=>array()));
        return $this->settings['smartpolis_companies'];
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
          $this->settings[$key] = $value;
        }
      }
      $this->save();
    }

    public function save() {
      $file_content  =  "<?php\n";
      $file_content .= '$SMARTPOLIS_SETTINGS = "';
      $file_content .= base64_encode(serialize($this->settings));
      $file_content .= '";' . "\n" . "?>";
      $result_of_write = file_put_contents($this->settingsFile, $file_content);
      if ($result_of_write == FALSE) {
        throw new Exception('Невозможно сохранить настройки в файл, проверьте наличие прав на запись');
      }


    }
  }
?>
