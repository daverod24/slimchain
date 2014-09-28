<?php

	class Validation
	{
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