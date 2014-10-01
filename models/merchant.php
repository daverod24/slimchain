<?php
	class merchantCall extends Request{

		/*instantiate request pointing to blockchain with proper user creds based on session*/
		 function __construct($walletpass){
		 	$this->root = 'https://blockchain.info/merchant/'.$_SESSION['user']->guid;
		 	$_SESSION['walletpass'] = $walletpass;
		}

		/*return named address for specific transaction*/
		function sendCoins($destination, $quantity, $unit){
			if($unit = 'usd'){
				$btc = unitConversion::USDtoBTC($quantity);
				$satoshi = unitConversion::BTCtoSatoshi($btc);
			}
			elseif($unit = 'btc'){
				$satoshi = unitConversion::BTCtoSatoshi($quantity);
			}
			else {
				$satoshi = $amount;
			}
			$transaction = $this -> makeRequest('GET', '/payment', array(
				'password'=>$_SESSION['walletpass'],
				'to'=>$destination,
				'amount'=> $satoshi
				));
			return $transaction['message'];
			
		}

		function getWalletBalance(){
			$balance = $this -> makeRequest('GET', '/balance', array(
				'password'=>$_SESSION['walletpass']
				));
			return $balance['balance'];
		}

		/*takes bool single returns either list of wallet addresses or a single address*/
		function getAddress($single){
			$list = $this -> makeRequest('GET', '/list', array(
				'password'=>$_SESSION['walletpass'],
				'confirmations'=>4
				));

			if($single){
				return $list['addresses'][0]['address'];
			}
			else {
				return $list['addresses'];
			}
		}

		/*return named address for specific transaction*/
		function makeAddress($label){
			$addressdata = $this -> makeRequest('GET', '/new_address', array(
				'password'=>$_SESSION['walletpass'],
				'label'=>$label
				));
			return $addressdata['address'];
			
		}

		//NO AUTH REQUIRED:
		/*return guid generate new wallet*/
		public static function newWallet($pass){
			$api_code="802b764f-aacd-4fed-aa84-af7fb7700432";
			$request = new Request('https://blockchain.info');
			$wallet = $request -> makeRequest('GET', '/api/v2/create_wallet', array(
				'api_code'=>$api_code,
				'password'=>$pass
				));
			return $wallet['guid'];
		}
		/*make GET request to ticker*/
		public static function ticker(){
			$request = new Request('https://blockchain.info');
			$ticker = $request -> makeRequest('GET', '/ticker');
			return $ticker['USD']['15m'];
		}
	}

?>