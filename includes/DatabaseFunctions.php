<?php

function query($pdo, $sql, $parameters = []){
	$query = $pdo->prepare($sql);

	//assoc array needs name and value
	//foreach($pararmeters as $name => $value){
	//	$query->bindValue($name, $value);
	//}

	//giving the execute() function args also binds values
	$query->execute($parameters);

	return $query;
}

function totalJokes($pdo){
	$sql = 'SELECT COUNT(*) FROM `joke`';

	$query = query($pdo, $sql);

	$row = $query->fetch();

	return $row[0];
}

function getJoke($pdo, $id){
	$sql = 'SELECT * FROM `joke` WHERE `id` = :id';

	//this array will be used in the query function
	$parameters = [':id' => $id];
	
	//use parameters in query function
	$query = query($pdo, $sql, $parameters);
	
	return $query->fetch();
}

function insertJoke($pdo, $joketext, $authorId){
	$query = 'INSERT INTO `joke` (`joketext`, `jokedate`, `authorid`)
						VALUES (:joketext, CURDATE(), :authorid)';

	$parameters = [':joketext' => $joketext, ':authorid' => $authorid];

	query($pdo, $query, $parameters);
}

function updateJoke($pdo, $jokeid, $joketext, $authorid){
	$sql = 'UPDATE `joke` SET `joketext` = :joketext, `authorid` = :authorid WHERE `id` = :id';

	$parameters = [':joketext' => $joketext, ':authorid' => $authorid,':id' => $jokeid];

	query($pdo, $sql, $parameters);
}

function deleteJoke($pdo, $jokeid){
	$sql = 'DELETE FROM `joke` WHERE `id` = :id';

	$parameters = [':id' => $jokeid];

	query($pdo, $sql, $parameters);
}

function allJokes($pdo){
	$sql = 'SELECT `joketext`,`joke`.`id`, `name`, `email` FROM `joke`
					INNER JOIN `author` ON `authorid` = `author`.`id`';

	$jokes = query($pdo, $sql);
	
	return $jokes->fetchAll();

}