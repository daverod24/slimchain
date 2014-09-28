<?php
	$app->get('/', function () use($twig) {
		


		echo $twig->render('home.html');
	});
	$app->get('/send', function () use($twig) {
		$valid = User::validuser($_POST['email'], $_POST['password'],$_POST['form_token']);
		$valid->register();
	});
	$app->get('/recieve', function () use($twig) {
		$_SESSION['form_token'] = md5( uniqid('auth', true) );
		echo $twig->render('login.html', array('form_token' => $_SESSION['form_token']));
	});
	$app->get('/market', function () use($twig) {
		$valid = User::validuser($_POST['email'], $_POST['password'],$_POST['form_token']);
		$valid->login();
	});
?>

