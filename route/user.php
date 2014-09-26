<?php
	$app->get('/test', function () use($twig) {
		$test = User::getby('id','18');
		print $test->email;
		
	});
	$app->get('/register', function () use($twig) {
		$_SESSION['form_token'] = md5( uniqid('auth', true) );
		echo $twig->render('register.html', array('form_token' => $_SESSION['form_token']));
	});

	$app->post('/register', function () use($twig) {
		$valid = User::validateform($_POST['email'], $_POST['password'],$_POST['form_token']);
		
		$user = new User($_POST['email'],$_POST['form_token']);
		$valid->register();
		
	});

	$app->get('/login', function () use($twig) {
		session_start();
		$message = "";
		$form_token = md5( uniqid('auth', true) );
		$_SESSION['form_token'] = $form_token;
		echo $message;
		echo $twig->render('login.html', array('form_token' => $form_token));
	});

	$app->post('/login', function () use($twig) {
		include 'models/uservalidation.php';
		include 'models/request.php';

		require 'models/loginvalidation.php';
		echo $message;
	});
?>
