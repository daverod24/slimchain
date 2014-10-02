<?php
<<<<<<< HEAD
	class Validation {

		public static function base16decode($input){
			//base 16 alphabet
			$base16 = "0123456789ABCDEF";

			$total = 0;
			//find place (n) of digit to be evaluated
			$n = strlen($input) - 1;

			$input = str_split($input);

			foreach ($input as $position => $character) {
				$charVal = strrpos($base16 ,$character);
				$decValue = $charVal * (pow(16, $n));
				$total = $total + $decValue;
				$n--;
			}
			echo $total;
		}

		public static function nextDigit($input){
			//base 16 alphabet
			$base16 = "0123456789ABCDEF";
			//find place (n) of digit to be evaluated
			$n = 0;
			echo "input:".$input;
			while(pow(16, $n) < $input){
				$n++;
			}
			$n--;
			echo "n:" . $n;

			//divide input by base to the nth power to find digit in nth place
			$result = floor($input /pow(16, $n));

			//calculate remainder
			$remainder = $input - ($result *( pow(16, $n)));

			echo "result:" . $result;
=======
	class Validation
	{
		public static function nextDigit($input){
			//base 16 alphabet
			$base16 = "0123456789ABCDEF";
			//find place value (n) of digit to be evaluated
			$n = 0;
			echo "input:".$input;
			while(pow(16, $n)<$input){
				$n++;
			}
			$n--;
			echo "n:".$n;

			//divide input by base to the nth power to find digit in nth place
			$result = floor($input/pow(16, $n));

			//calculate remainder
			$remainder = $input -($result*(pow(16, $n)));

			echo "result:".$result;
>>>>>>> 6815327146bef7bf0d5356c9268d34cbb4591c2b

			//translate base10 digit to desired base alphabet digit by mapping to basealphabet string with base10 index
			$output = substr($base16, $result, 1);
			echo "out:".$output;

			//return next encoded character and remaining base10 quantity to encode
			return array(
				'result'=>$output,
				'remainder'=>$remainder
				);

		}

		public static function base16encode($input){
			$output = '';
			$remainder = $input;
			//keep calcing next digit until there is no remainder left
			while ($remainder>0) {
				echo "remainder".$remainder;
				$next = Validation::nextDigit($remainder);
				//append next digit to output and set new remainder
				$output=$output.$next["result"];
				$remainder = $next["remainder"];
			}
			echo "output".$output;
		}


		public static function base58encode($input){
			$base58 = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";

			$div = floor($input / 58);

		}







<<<<<<< HEAD

=======
		
>>>>>>> 6815327146bef7bf0d5356c9268d34cbb4591c2b

		public static function validAddress($address){
			$output = base_convert( $address , 58 , 64 );
			echo $output;

		}
		
		public static function validToken($postToken){
			if( $postToken != $_SESSION['form_token']) {
				$message = 'Invalid form submission';
				print $message;
				return false;
			}
			else {
				return true;
			}
		}

		public static function validLength($postVar, $maxLength, $minLength){
			if (strlen( $postVar) > $maxLength){
				$message = "Input cannot be more than ".$maxLength." characters long.";
				return false;
			}
			elseif (strlen($postVar) < $minLength) {
				$message = "Input cannot be less than ".$minLength." characters long.";
				print $message;
				return false;
			}
			else {
				return true;
			}
		}

		public static function validEmail($postEmail){
			if (!filter_var($postEmail, FILTER_VALIDATE_EMAIL)) {
			    $message = "Invalid email address";
			    print $message;
			    return false;
			}
			else {
				return true;
			}
		}
	}
?>