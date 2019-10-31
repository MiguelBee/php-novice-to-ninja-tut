<?php

try{
	//includes the autoloader to include any new instance of a class
	include __DIR__ . '/../includes/autoload.php';

	// if no route variable is set, use 'joke/home'
	//$route = $_GET['route'] ?? 'joke/home';

	$route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

	$entryPoint = new \Ninja\EntryPoint($route, $_SERVER['REQUEST_METHOD'], new \Ijdb\Ijdbroutes());
	$entryPoint->run();

} catch (\PDOExeption $e) {
	$title = 'An error has occurred';

	$output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile()  . ' : ' . $e->getLine();

	include __DIR__ . '/../templates/layout.html.php';
}