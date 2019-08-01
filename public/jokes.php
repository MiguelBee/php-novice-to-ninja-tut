<?php

try {
	
	include __DIR__ . '/../includes/DatabaseConnection.php';
	include __DIR__ . '/../includes/DatabaseFunctions.php';
	
	$jokes = allJokes($pdo);

	$title = 'Joke List';

	$totalJokes = totalJokes($pdo);

	ob_start();

//The php code will be executed, but the resulting HTML will be stored in the buffer
	include __DIR__ . "/../templates/jokes.html.php";

//Read the contents of the output buffer and store them
//in the $output variable for use in the layout.html.php
	$output = ob_get_clean();

} catch (PDOException $e) {
	$error = 'Unable to connect to internet ' . $e->getMessage() . ' in ' . $e->getFile() . ' : ' . $e->getLine();
}

include __DIR__ . "/../templates/layout.html.php";