<?php

try {
	$pdo = new PDO('mysql:host=localhost;dbname=ninja_jokes; charset=utf8', 'ninja', 'ninja');
	
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = 'SELECT `joketext`,`id` FROM `joke`';

	$results = $pdo->query($sql);

	$jokes = $results;

	$title = 'Joke List';

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