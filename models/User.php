<?php
	class User
	{
	    public $email, $password, $guid;

	    function __construct($mail, $passhash) {
	    	$this->email = $mail;
			$this->password = $passhash;
			$this->guid = null;
	    }

	    //return user obj
	    public static function getby($parameter, $value){
	    	global $db;

			$stmt = $db->prepare("SELECT * FROM users WHERE " . $parameter . " = " . $value);
			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_OBJ);

			$user = new User($result->email, $result->password);
			return $user;
	    }

	    //sets $_SESSION['user']
	    function login(){
			try {
				global $db;
		        $stmt = $db->prepare("SELECT id, email, password FROM users WHERE email = :email AND password = :password");
		        $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		        $stmt->bindParam(':password', $this->password, PDO::PARAM_STR, 64);
		        $stmt->execute();
		        /*** check for a result ***/
		        $id = $stmt->fetchColumn();

		        if($id == false) {
		            $message ='Login Failed';
		        }
		        else {
		            $_SESSION['user'] = $this;
		            $message = 'You are now logged in';
		        }
			}
			catch(Exception $e) {
				$message = 'Database error';
			}

			echo $message;
	    }

	    // registers calling User obj to db
		function register() {
			global $db;

			$walletPass = password_hash( $this->password.SALT , PASSWORD_DEFAULT);
			$request = "https://blockchain.info/api/v2/create_wallet?api_code=802b764f-aacd-4fed-aa84-af7fb7700432&password=".$walletPass;
			$response = json_decode(file_get_contents($request));
			$this->guid = $response->{'guid'};
			try {
				$stmt = $db->prepare("INSERT INTO users (email, password, guid ) VALUES (:email, :password, :guid )");
				/*** bind the parameters, execute the prepared statement ***/
				$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
				$stmt->bindParam(':password', $this->password, PDO::PARAM_STR, 64);
				$stmt->bindParam(':guid', $this->guid, PDO::PARAM_STR);
				$stmt->execute();
				$message = 'New user added';
			}
			catch(Exception $e) {
				if( $e->getCode() == 23000) {
					echo $e ->getMessage();
					$message = 'Duplicate entry';
				}
				else {
					$message = 'Database error';
				}
			}
			echo $message;
		}

		//return user obj w/ hashed password if submission is valid
	    public static function validuser($emailIn, $passwordIn, $tokenIn) {
			
			/*** check the form token is valid ***/
			if( $tokenIn != $_SESSION['form_token']) {
				$message = 'Invalid form submission';
			}
			elseif(!isset($emailIn, $passwordIn, $tokenIn)) {
				$message = 'Please enter a valid email and password';
			}
			elseif (strlen( $emailIn) > 300 || strlen($emailIn) < 4) {
				$message = 'Incorrect Length for email';
			}
			elseif (!filter_var($emailIn, FILTER_VALIDATE_EMAIL)) {
			    $message = "Invalid email address";
			}
			elseif (strlen( $passwordIn) > 300 || strlen($passwordIn) < 10) {
				$message = 'Incorrect Length for Password';
			}
			else {
				$email = filter_var($emailIn, FILTER_SANITIZE_STRING);
				$password = filter_var($passwordIn, FILTER_SANITIZE_STRING);

				$password = hash( 'sha256', $password );

				$message = 'Valid submission';
				$user = new User($email, $password);
				return $user;
			}
			echo $message;
			return false;
	    }
	}
?>