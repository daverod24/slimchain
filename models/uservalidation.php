<?php
	/*** connect to database ***/
	function dbConnect(){
		/*** mysql hostname ***/
		$mysql_hostname = 'localhost';
		/*** mysql email ***/
		$mysql_username = 'root';
		/*** mysql password ***/
		$mysql_password = 'root';
		/*** database name ***/
		$mysql_dbname = 'blockchain';

		$dbh = new PDO("mysql:host = $mysql_hostname; dbname=$mysql_dbname", $mysql_username, $mysql_password);
		/*** set the error mode to exceptions ***/
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbh;
	}

	/*return bool*/
	function validUser(){  
		

		global $message;
		global $email;
		global $password;
		echo "email is:".$email;
		echo $password;
			/*** check the form token is valid ***/
		if( $_POST['form_token'] != $_SESSION['form_token']) {
			$message = 'Invalid form submission';
		}
		/*** first check that both the email, password and form token have been sent ***/
		elseif(!isset( $_POST['email'], $_POST['password'], $_POST['form_token'])) {
			$message = 'Please enter a valid email and password';
		}
		/*** check the email is the correct length ***/
		elseif (strlen( $_POST['email']) > 300 || strlen($_POST['email']) < 4) {
			$message = 'Incorrect Length for email';
		}
		elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		    $message = "Invalid email address";
		}
		/*** check the password is the correct length ***/
		elseif (strlen( $_POST['password']) > 300 || strlen($_POST['password']) < 10) {
			$message = 'Incorrect Length for Password';
		}
		else {
			/*** valid, prep for insert into database ***/
			$email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
			$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

			/*** encrypt password ***/
			$password = hash( 'sha256', $password );
			return true;
		}
		return false;
	}
?>