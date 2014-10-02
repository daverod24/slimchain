<?php

	$app->get('/', function () use($twig) {
		$walletBalance = $_SESSION['connection']->getWalletBalance();
		$marketRate = merchantCall::ticker();
		$mainAddress = $_SESSION['connection']->getAddress(true);

		echo $twig->render('home.html', array(
			'walletBalance'=>$walletBalance,
			'marketRate'=>$marketRate,
			'mainAddress'=>$mainAddress 
			));
	});

	$app->get('/send', function () use($twig) {
		$_SESSION['form_token'] = md5( uniqid('auth', true) );
		echo $twig->render('send.html', array('form_token' => $_SESSION['form_token']));
	});

	$app->post('/send', function () use($twig) {
		$valid = User::validuser($_POST['email'], $_POST['password'],$_POST['form_token']);
		$valid->login();

	});

	$app->get('/recieve', function () use($twig) {
		var_dump($_SESSION['connection']->getWalletBalance());
	});

	$app->get('/market', function () use($twig) {
		
	});
?>

