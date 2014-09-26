<?php
	class User
	{
	    public $email, $password, $guid;
	    // the constructor just instantiates a new user object without necesarily messing with db
	    //guid will either be pulled from db or set in create
	    function __construct($mail, $passhash) {
	    	$this->email = $mail;
			$this->password = $passhash;
			$this->guid = null;
	    }

	    //parse form data for valid user creds, hash password
	    public static function validateform($emailIn, $passwordIn, $tokenIn) {
			global $message;
			
			/*** check the form token is valid ***/
			if( $tokenIn != $_SESSION['form_token']) {
				$message = 'Invalid form submission';
			}
			/*** first check that both the email, password and form token have been sent ***/
			elseif(!isset($emailIn, $passwordIn, $tokenIn)) {
				$message = 'Please enter a valid email and password';
			}
			/*** check the email is the correct length ***/
			elseif (strlen( $emailIn) > 300 || strlen($emailIn) < 4) {
				$message = 'Incorrect Length for email';
			}
			elseif (!filter_var($emailIn, FILTER_VALIDATE_EMAIL)) {
			    $message = "Invalid email address";
			}
			/*** check the password is the correct length ***/
			elseif (strlen( $passwordIn) > 300 || strlen($passwordIn) < 10) {
				$message = 'Incorrect Length for Password';
			}
			else {
				/*** valid, prep for insert into database ***/
				$email = filter_var($emailIn, FILTER_SANITIZE_STRING);
				$password = filter_var($passwordIn, FILTER_SANITIZE_STRING);

				/*** encrypt password ***/
				$password = hash( 'sha256', $password );

				$message = 'Valid submission';
				$user = new User($email, $password);
				return $user;
			}
			echo $message;
			return false;

	    }

	    //return user object with value that matches parameter in database
	    public static function getby($parameter, $value){
	    	global $db;
	    	/*** prepare the insert ***/
				$stmt = $db->prepare("SELECT * FROM users WHERE " . $parameter . " = " . $value);
				$stmt->execute();

				$result = $stmt->fetch(PDO::FETCH_OBJ);
				$email = $result->email;
				$password = $result->password;

				$user = new User($email, $password);
				return $user;
				
	    }

	    // accepts validated mail and pass, adds to db, creates user obj with set properties
		function register() {
			global $message, $db;
			/*//set instance var name and pass
			$this->email = $mail;
			$this->password = $passhash;*/

			//reqbuilder?
			$walletPass = password_hash( $this->password.SALT , PASSWORD_DEFAULT);
			$request = "https://blockchain.info/api/v2/create_wallet?api_code=802b764f-aacd-4fed-aa84-af7fb7700432&password=".$walletPass;
			$response = json_decode(file_get_contents($request));
			$this->guid = $response->{'guid'};
			try {
				/*** prepare the insert ***/
				$stmt = $db->prepare("INSERT INTO users (email, password, guid ) VALUES (:email, :password, :guid )");
				/*** bind the parameters, execute the prepared statement ***/
				$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
				$stmt->bindParam(':password', $this->password, PDO::PARAM_STR, 64);
				$stmt->bindParam(':guid', $this->guid, PDO::PARAM_STR);
				$stmt->execute();
				echo 'New user added';
			}
			catch(Exception $e) {
				/*** check if the email already exists ***/
				if( $e->getCode() == 23000) {
					echo $e ->getMessage();
					$message = "mail:".$email."pass:".$password."guid:".$guid.'email already exists';
				}
				else {
					/*** if we are here, something has gone wrong with the database ***/
					$message = 'We are unable to process your request. Please try again later"';
				}
			}

		}
	}
?>