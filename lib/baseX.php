<?php

	class baseX{

		//input string to convert to decimal and alphabet of base the string is in
		public static function baseXdecode($input, $xAlphabet){
			//get base as length of its alphabet
			$X = strlen($xAlphabet);
			echo "Decoding a string from base".$X."\n\n";
			//initialize total to zero since dec result is the total of all input values converted to dec and added up
			$total = 0;
			//find place (n) of digit to be evaluated (zero based big endien so subtract 1 from length)
			$n = strlen($input) - 1;
			//transfer input into an array to handle indivdual chars
			$input = str_split($input);
			foreach ($input as $position => $character) {
				echo "\nPosition in input string: ".$position."\n";
				echo "The character is: ".$character."\n";
				//get baseX value of character
				$charVal = strrpos($xAlphabet, $character);
				echo "Its value is: ".$charVal."\n";
				//muliply by baseX^nth power to get dec value
				$decValue = bcmul($charVal, (bcpow($X, $n)));
				echo "Its base10 value when multiplied by its original base raised to ".$n." is ".$decValue."\n";
				//add dec value to total
				$total = bcadd($total, $decValue);
				echo "The new total is ".$total."\n";
				//decrement power because we're moving onto the next digit
				$n--;
			}
			echo "FINAL TOTAL: ".$total."\n\n";
			return $total;
		}



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

		static function bcfloor($number)
			{
			    if ($number[0] != '-'){
			        return bcadd($number, 0, 0);
			    }
			    return bcsub($number, 1, 0);
			}

		public static function nextDigit($input,$n){
			//base 16 alphabet
			$base16 = "0123456789ABCDEF";
			
			echo "The remainder to convert to base 16 is: ".$input."\n";


			//divide input by base to the nth power to find digit in nth place
			$result = baseX::bcfloor(bcdiv($input, bcpow(16, $n)));
			echo "The remainder/(16 ^ nth power) and then rounded down yields this result: ".$result."\n";
			//calculate remainder
			$remainder = bcsub($input,(bcmul($result ,(bcpow(16, $n)))));
			echo "The new remainder is".$remainder."\n";

			//translate base10 digit to desired base alphabet digit by mapping to basealphabet string with base10 index
			$output = substr($base16, $result, 1);
			echo "The result mapped to a base16 digit is: ".$output."\n\n";

			//return next encoded character and remaining base10 quantity to encode
			return array(
				'result'=>$output,
				'remainder'=>$remainder
				);

		}

		public static function base16encode($input){
			echo "Encoding the following to base16".$input."\n\n\n\n\n\n";
			//base 16 alphabet
			$base16 = "0123456789ABCDEF";
			$output = '';
			$remainder = $input;
			$n=0;

			//find place (n) of first digit to be evaluated
			while(bcpow(16, bcadd($n, 1)) < $input){
				$n = bcadd($n, 1);
			}
			/*$n = bcsub($n, 1);*/
			echo "The position (n) is:" . $n."\n";

			//keep calcing next digit until there is no remainder left
			while ($remainder>0) {
				echo "The remainder to convert to base 16 is: ".$input."\n";

				//divide input by base to the nth power to find digit in nth place
				$result = baseX::bcfloor(bcdiv($input, bcpow(16, $n)));
				echo "The remainder/(16 ^ nth power) and then rounded down yields this result: ".$result."\n";
				//calculate remainder
				$remainder = bcsub($input,(bcmul($result ,(bcpow(16, $n)))));
				echo "The new remainder is".$remainder."\n";

				//translate base10 digit to desired base alphabet digit by mapping to basealphabet string with base10 index
				$nextDigit= substr($base16, $result, 1);
				echo "The result mapped to a base16 digit is: ".$nextDigit."\n\n";

				//append next digit to output and set new remainder and n
				$output=$output.$nextDigit;
				$n--;
			}
			echo "FINAL OUTPUT: ".$output;
		}


	}
?>