<?php

include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';


try {
	if(isset($_POST['joketext'])){
		updatejoke($pdo, $_POST['jokeid'], $_POST['joketext'], 1);

		header('location: jokes.php');
	} else {
		$joke = getJoke($pdo, $_GET['id']);

		$title = 'Edit Joke';

		ob_start();

		include __DIR__ . '/../templates/editjoke.html.php';

		$output = ob_get_clean();
	}
} catch(PDOexception $e){
	$title = 'An error has occurred';

	$output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' : ' . $e->getLine(); 
}

include __DIR__ . '/../templates/layout.html.php';
