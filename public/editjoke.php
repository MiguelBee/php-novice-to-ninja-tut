<?php

include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';


try {
	if(isset($_POST['joke'])){

		$joke = $_POST['joke'];
		$joke['authorid'] = 1;
		$joke['jokedate'] = new DateTime();

		save($pdo, 'joke', 'id', $joke);

		header('location: jokes.php');
	} else {
		
		if(isset($_GET['id'])){
			$joke = findById($pdo, 'joke', 'id', $_GET['id']);
		}
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
