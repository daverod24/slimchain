<?php 
	function requestBuilder($method, $reqType, $arguments, $root='http://blockchain.info/api/'){
		global $request;
		//url-ify the data 
		$argString = http_build_query($arguments);

		if($method = 'GET'){
			$request =  $root.$reqType.'?'.$argString;
		}
		elseif ($method = 'POST') {
			//open connection
			$ch = curl_init();

			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL, $root.$reqType);
			curl_setopt($ch,CURLOPT_POST, count($arguments));
			curl_setopt($ch,CURLOPT_POSTFIELDS, $argString);
			//execute post
			$result = curl_exec($ch);

			//close connection
			curl_close($ch);
		}
	}

?>