<?php

function loadTemplate($templateFileName, $variables = []){
	extract($variables);

	//The php code will be executed, but the resulting HTML will be stored in the buffer
	ob_start();

	//Read the contents of the output buffer and store them
	//in the $output variable for use in the layout.html.php
	include __DIR__ . '/../templates/' . $templateFileName;

	return $output = ob_get_clean();
}

try{
	include __DIR__ . '/../includes/DatabaseConnection.php';
	include __DIR__ . '/../controllers/JokesController.php';
	include __DIR__	. '/../classes/DatabaseTable.php';

	$jokesTable = new DatabaseTable($pdo, 'joke', 'id');
	$authorsTable = new DatabaseTable($pdo, 'author', 'id');

	$jokesController = new JokesController($authorsTable, $jokesTable);

	$action = $_GET['action'] ?? 'home';

	$page = $jokesController->$action();

	$title = $page['title'];

	if(isset($page['variables'])){
		$output = loadTemplate($page['template'], $page['variables']);
	} else {
		$output = loadTemplate($page['template']);
	}

} catch (PDOExeption $e) {
	$title = 'An error has occurred';

	$output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile()  . ' : ' . $e->getLine();
}

include __DIR__ . '/../templates/layout.html.php';