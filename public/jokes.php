<?php

include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';

try {
	
	$results = findAll($pdo, 'joke');

	$jokes = [];

	foreach($results as $joke){
		$author = findById($pdo, 'author', 'id', $joke['authorid']);

#looping through a table and adding by id is essentially what an inner join does in MYSQL
		$jokes[] = [
			'id' => $joke['id'],
			'joketext' => $joke['joketext'],
			'jokedate' => $joke['jokedate'],
			'name' => $author['name'],
			'email' => $author['email']
		];
	}

	$title = 'Joke List';

	$totalJokes = total($pdo, 'joke');

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