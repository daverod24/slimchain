<?php 
	class unitConversion {

		/*return btc value of dollar amt*/
		public static function USDtoBTC($usdValue){
			$request = new Request('https://blockchain.info');
			$btcValue= $request -> makeRequest('GET', '/tobtc', array(
					'currency'=>"USD",
					'value'=>$usdValue
				));
			return $btcValue;
		}

		public static function SatoshitoBTC($satoshiValue){
			return $satoshis/100000000;
		}

		public static function BTCtoSatoshi($btcValue){
			return $satoshis*100000000;
		}

		public static function BTCtoUSD($value, $isSatoshi){
			if($isSatoshi){
				$value = $value/100000000;
			}
			$request = new blockchainCall();
			$marketRate = $request->ticker();
			return $value*$marketRate;
		}


	}


?>