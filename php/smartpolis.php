<?php
	@set_time_limit(0);
	class connectionPool {
		private $apiPoint = "http://casco.cmios.ru/rest/default";

		public function get($url) {
			return file_get_contents($this->apiPoint . $url);
		}

		public function post($url, $object) {
			$ch = curl_init( $this->apiPoint . $url );
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($object));
			$res = curl_exec($ch);
			curl_close($ch);
			return $res;
		}
		
	}

	class cascoApi {
		private $requestObject = array();
		private $connection = null;

		public function __construct() {
			@session_start();
			if ( isset($_SESSION['cascoRequestObject']) && !empty($_SESSION['cascoRequestObject']) ) {
				$this->requestObject = unserialize($_SESSION['cascoRequestObject']);
			}
			$this->connection = new connectionPool();
		}

		public function setValue($name, $value) {
			$this->requestObject[$name] = $value;
			$_SESSION['cascoRequestObject'] = serialize($this->requestObject);
		}

		public function getCarMarks() {
			return $this->connection->get('/car_mark/');
		}

		public function getCarModels() {
			return $this->connection->get('/car_mark/' . $this->requestObject['car_mark'] . '/car_model/');
		}

		public function getCarModifications() {
			return $this->connection->get(
				'/car_mark/' . $this->requestObject['car_mark'] . '/car_model/' . $this->requestObject['car_model'] . '/car_modification/'
			);
		}

		public function createResult() {
			$res = $this->connection->post('/calculation/', $this->requestObject);
			$object = json_decode($res);
			$_SESSION['cascoResultId'] = $object->id;
		}

		public function getCompanies() {
			return $this->connection->get(
				'/insurance_company/active/'
			);
		}
		
		public function getResult($companyId) {
			$url = '/calculation/' . $_SESSION['cascoResultId'] . '/result/' . $companyId . '/';
			return $this->connection->post($url, array());
		}
	}
	

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	$casco = new cascoApi();

	$requestType = $_REQUEST['type'];

	switch($requestType) {
		case 'car_model': {
			$casco->setValue('car_mark', $_REQUEST['car_mark']);
			echo $casco->getCarModels();
			break;
		}
		case 'car_modification': {
			$casco->setValue('car_model', $_REQUEST['car_model']);
			echo $casco->getCarModifications();
			break;
		}
		case 'getRequarList': {
			$casco->setValue('car_modification', $_REQUEST['car_modification']=='' ? null: $_REQUEST['car_modification']);
			$casco->setValue('car_cost', $_REQUEST['car_cost']);
			$casco->setValue('car_manufacturing_year', $_REQUEST['car_manufacturing_year']);
			$casco->setValue('drivers_count', count($_REQUEST['car_driver_age']));
			$drivers = array();
			foreach($_REQUEST['car_driver_age'] as $key=>$value) {
				$driver = array();
				$driver['age'] = $_REQUEST['car_driver_age'][$key];
				$driver['car_driver_prof'] = $_REQUEST['car_driver_prof'][$key];
				$driver['gender'] = 'M';
				$driver['is_married'] = true;
				$driver['has_children'] = true;
				$drivers[] = $driver;
			}
			$casco->setValue('driver_set', $drivers);
			$casco->createResult();
			echo $casco->getCompanies();
			break;
		}
		case 'getResult': {
			echo $casco->getResult($_REQUEST['id']);
			break;
		}
		case 'getCompanies': {
			echo $casco->getCompanies();
			break;
		}
		default: echo $casco->getCarMarks();
	}
?>
