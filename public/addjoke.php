<?php

if(isset($_POST['joketext'])){
	try{
		
	include __DIR__ . '/../includes/DatabaseConnection.php';
	include __DIR__ . '/../includes/DatabaseFunctions.php';

		/* Other type of sql setting statement, use VALUES instead
		$sql = 'INSERT INTO `joke` SET
						`joketext` = :joketext,
						`jokedate` = CURDATE()';
		*/

		insertJoke($pdo, $_POST['joketext'], 1);

		header('location: jokes.php');

	} catch(PDOexception $e) {
		$title = 'An error has occured';

		$output = 'Database error' . $e->getMessage() . ' in ' . $e->getFile() . ' : ' . $e->getLine();
	}
} else {

	$title = 'Add a new joke';

	ob_start();

	include __DIR__ . "/../templates/addjoke.html.php";

	$output = ob_get_clean();
}

include __DIR__ . "/../templates/layout.html.php";