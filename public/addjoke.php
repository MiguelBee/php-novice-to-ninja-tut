<?php

if(isset($_POST['joketext'])){
	try{
		$pdo = new PDO('mysql:host=localhost;dbname=ninja_jokes; charset=utf8', 'ninja', 'ninja');
	
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = 'INSERT INTO `joke` SET
						`joketext` = :joketext,
						`jokedate` = CURDATE()';

		$stmt = $pdo->prepare($sql);

		$stmt->bindValue(':joketext', $_POST['joketext']);

		$stmt->execute();

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