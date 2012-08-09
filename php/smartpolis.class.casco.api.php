<?php
	@set_time_limit(0);
	class connectionPool {
		private $apiPoint = "http://casco.cmios.ru/rest/default";

		public function get($url) {
			return file_get_contents($this->apiPoint . $url);
		}

		public function post($url, $data, $debug = 0) {
			$ch = curl_init( $this->apiPoint . $url );
			curl_setopt($ch, CURLOPT_HEADER, $debug);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$res = curl_exec($ch);
			curl_close($ch);
			return $res;
		}
		
	}

	class smartpolisCascoApi {
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
			$res = $this->connection->post('/calculation/', json_encode($this->requestObject));
			$object = json_decode($res);
			$_SESSION['cascoResultId'] = $object->id;
			session_commit();
		}

		public function getActiveCompanies() {
			include_once( SMARTPOLIS_PLUGIN_DIR . 'php/smartpolis.class.settings.php');
			$settings = new smartPolisSettings();
			$companies = $settings->getCompanies();
			$result = array();
			foreach($companies as $company) {
				if ($company['params']['active']=='true') {
					$result[] = $company['object'];
				}
			}
			return json_encode($result);
		}
		
		public function getCompanies() {
			return $this->connection->get(
				'/insurance_company/active/'
			);
		}
		
		public function getResult($companyId) {
			$url = '/calculation/' . $_SESSION['cascoResultId'] . '/result/' . $companyId . '/';
			$res = $this->connection->post($url, '{}');
			if (empty($res)) return json_encode(false);
			$res = json_decode($res);
			$result = array();
			$result['company_id'] = $res->insurance_company->id;
			$result['logo'] = $res->insurance_company->logo;
			$result['result_id'] = $res->id;
			$result['sum'] = ceil($res->sum);
			
			include_once( SMARTPOLIS_PLUGIN_DIR . 'php/smartpolis.class.settings.php');
			$settings = new smartPolisSettings();
			$companies = $settings->getCompanies();
			$result['our_sum'] = ceil($result['sum'] - $result['sum']*($companies[$companyId]['params']['discount']/100));
			$result['discount'] = $companies[$companyId]['params']['discount'];
			return json_encode($result);
			
/*			include_once( SMARTPOLIS_PLUGIN_DIR . 'php/smartpolis.class.settings.php');
			$settings = new smartPolisSettings();
			$companies = $settings->getCompanies();
			//var_dump($companies);
			$result = array();
			$result['company_id'] = $companyId;
			$result['logo'] = $companies[$companyId]['object']->logo;
			$result['result_id'] = rand(100000, 200000);
			$result['sum'] = rand(100000, 200000);
			$result['our_sum'] = ceil($result['sum'] - $result['sum']*($companies[$companyId]['params']['discount']/100));
			$result['discount'] = $companies[$companyId]['params']['discount'];
			return json_encode($result);
*/			
		}
	}
?>
