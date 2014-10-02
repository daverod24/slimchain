<?php
	
	$app->get('/test', function () use($twig) {
		$decimal = baseX::baseXdecode("16UwLL9Risc3QfPqBUvKofHmBQ7wMtjvM","123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz");
		$hex = baseX::base16encode($decimal);
	});	

	$app->get('/register', function () use($twig) {
		$_SESSION['form_token'] = md5( uniqid('auth', true) );
		echo $twig->render('register.html', array('form_token' => $_SESSION['form_token']));
	});

	$app->post('/register', function () use($twig) {
		$valid = User::validuser($_POST['email'], $_POST['password'],$_POST['form_token']);
		$valid->register();
		
	});

	$app->get('/login', function () use($twig) {
		$_SESSION['form_token'] = md5( uniqid('auth', true) );
		echo $twig->render('login.html', array('form_token' => $_SESSION['form_token']));
	});

	$app->post('/login', function () use($twig) {
		$valid = User::validuser($_POST['email'], $_POST['password'],$_POST['form_token']);
		$valid->login();

	});


	$app->get('/+', function () use($twig) {
		 $app->redirect('/login');

	});


?>
