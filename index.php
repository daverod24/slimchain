<?php
	session_start();

	$db = new PDO("mysql:host = localhost; dbname=blockchain", "root", "root");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	include 'config/config.php';
	require 'models/User.php';
	require 'vendor/autoload.php';

$app = new \Slim\Slim(array(
		'view' => new \Slim\Views\Twig()
		));

	$view = $app -> view();

	$view -> parserOptions = array (
		'debug' => true,
		'cache' => 'cache'
		);

	$view -> parserExtensions = array(
		new \Slim\Views\TwigExtension()
		);

	$loader = new Twig_Loader_Filesystem('views');
	$twig = new Twig_Environment($loader);
	
	require 'route/user.php';
	require 'route/api.php';

		$app->run();
	
?>