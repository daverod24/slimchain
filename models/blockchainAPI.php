<?php
	class blockchainAPI extends Request{
		


		public static function ticker(){
			$request = new Request('GET', '/ticker', array(), 'https://blockchain.info');

			$response = $request->Parse();
			/*var_dump((Parse($request))->'usd');*/
		}
	}

?>