<?php
  @set_time_limit(0);
  // error_reporting(E_ALL);
  //ini_set('display_errors', 1);

  define('SMARTPOLIS_PLUGIN_DIR', realpath(dirname(__FILE__) . '/..') . '/');

  include_once( SMARTPOLIS_PLUGIN_DIR . 'php/smartpolis.class.casco.api.php');

  $casco = new smartpolisCascoApi();

  $requestType = $_REQUEST['type'];

  switch($requestType) {
    case 'car_models': {
      $casco->setValue('car_mark', $_REQUEST['car_mark']);
      echo $casco->getCarModels();
      break;
    }
    case 'car_modifications': {
      $casco->setValue('car_model', $_REQUEST['car_model']);
      echo $casco->getCarModifications();
      break;
    }
    case 'getRequarList': {
      $casco->setValue('car_modification', $_REQUEST['smartpolis_car_modifications']=='' ? null: $_REQUEST['smartpolis_car_modifications']);
      $casco->setValue('car_cost', $_REQUEST['smartpolis_car_cost']);
      $casco->setValue('car_manufacturing_year', $_REQUEST['smartpolis_car_manufacturing_year']);
      if ( isset($_REQUEST['smartpolis_drivers_count']) && $_REQUEST['smartpolis_drivers_count']=='multiply') {
        $casco->setValue('is_multidrive', true);
        $casco->setValue('drivers_minimal_age', 18);
        $casco->setValue('drivers_minimal_experience', 0);
        $casco->setValue('drivers_count', null);
        $casco->setValue('driver_set', array());
      } else {
        $casco->setValue('is_multidrive', false);
        $casco->setValue('drivers_minimal_age', null);
        $casco->setValue('drivers_minimal_experience', null);
        $casco->setValue('drivers_count', count($_REQUEST['car_driver_age']));
        $drivers = array();
        foreach($_REQUEST['car_driver_age'] as $key=>$value) {
          $driver = array();
          $driver['age'] = $_REQUEST['car_driver_age'][$key];
          $driver['expirience'] = $_REQUEST['car_driver_prof'][$key];
          $driver['gender'] = $_REQUEST['car_driver_gender'][$key];
          $driver['is_married'] = false;
          $driver['has_children'] = false;
          $drivers[] = $driver;
        }
        $casco->setValue('driver_set', $drivers);
      }
      $casco->createResult();
      echo $casco->getActiveCompanies();
      break;
    }
    case 'getResult': {
      echo $casco->getResult($_REQUEST['id']);
      break;
    }
    default: echo $casco->getCarMarks();
  }
?>
