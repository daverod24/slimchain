<?php 
	class Request{
		public $method, $arguments, $root;

		/*sets root, only use once to instantiate class pointing to correct domain*/
		function __construct($r){
			$this->root=$r;
		}


		public function makeRequest($method, $url, $arguments = array()) {
			$ch = curl_init();

			//url-ify the data 
			$argString = http_build_query($arguments);
			$fullurl =  $this->root.$url;

			//set url data
			if($method = 'GET'){
				$fullurl.='?'.$argString;
			}
			//set post data
			elseif ($method = 'POST') {
				curl_setopt($ch,CURLOPT_POST, count($arguments));
				curl_setopt($ch,CURLOPT_POSTFIELDS, $argString);
				//execute post
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			//set curl url and execute
			curl_setopt($ch,CURLOPT_URL, $fullurl);
			$result = curl_exec($ch);
			//close connection
			curl_close($ch);
			$result = json_decode($result, true);
			
			
			return $result;
		}

	}
?>