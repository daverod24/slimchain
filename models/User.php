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
		        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
		        $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		        $stmt->bindParam(':password', $this->password, PDO::PARAM_STR, 64);
		        $stmt->execute();
		        /*** check for a result ***/
		        $result=($stmt->fetch());
		        if($result == false) {
		            $message ='Login Failed';
		        }
		        else {
		        	$message = 'You are now logged in';

		        	//initialize session user
		            $_SESSION['user'] = $this;
		            foreach ($result as $key => $value) {
						$index = print_r($key, true);
						$_SESSION['user']->$index = $value;
					}
					$walletPass = hash( 'sha256', $this->password.SALT );
					$_SESSION['connection'] = new merchantCall($walletPass);
		        }
		        echo $message;
			}
			catch(Exception $e) {
				echo $e ->getMessage();
				$message = 'Database error';
			}
			print $result['guid'];
	    }

	    /*function logout(){}*/

	    // registers calling User obj to db
		function register() {
			global $db;
			/*$request = new merchantCall();*/

			$walletPass = hash( 'sha256', $this->password.SALT );
			$this->guid = merchantCall::newWallet($walletPass);

			try {
				$stmt = $db->prepare("INSERT INTO users (email, password, guid ) VALUES (:email, :password, :guid )");
				/*** bind the parameters, execute the prepared statement ***/
				$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
				$stmt->bindParam(':password', $this->password, PDO::PARAM_STR, 64);
				$stmt->bindParam(':guid', $this->guid, PDO::PARAM_STR);
				$stmt->execute();
				$message = 'New user added';
				$this->login();
			}
			catch(Exception $e) {
				if( $e->getCode() == 23000) {
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

	    	$validToken = Validation::validToken($tokenIn);
	    	$validEmailLength = Validation::validLength($emailIn, 300, 10);
	    	$validEmail = Validation::validEmail($emailIn);
	    	$validPasswordLength = Validation::validLength($passwordIn, 300, 10);


			if( !$validToken || !$validEmailLength ||!$validEmail ||!$validPasswordLength) {
				$message = "invalid. set the message with the validation class. figure out scoping";
				echo $message;
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