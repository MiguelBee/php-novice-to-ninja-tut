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

function total($pdo, $table){
	$sql = 'SELECT COUNT(*) FROM `'. $table . '`';

	$query = query($pdo, $sql);

	$row = $query->fetch();

	return $row[0];
}

function findById($pdo, $table, $primaryKey, $id){
	$sql = 'SELECT * FROM `' . $table . '` WHERE `' . $primaryKey . '` = :id';

	$parameters = [':id' => $id];

	$query = query($pdo, $sql, $parameters);

	return $query->fetch();
}
/**
function getJoke($pdo, $id){
	$sql = 'SELECT * FROM `joke` WHERE `id` = :id';

	//this array will be used in the query function
	$parameters = [':id' => $id];
	
	//use parameters in query function
	$query = query($pdo, $sql, $parameters);
	
	return $query->fetch();
}
**/

//replaces following insert functions
function insert($pdo, $table, $fields){
	$query = 'INSERT INTO `' . $table . '` (';

	foreach($fields as $key => $value){
		$query .= '`' . $key . '`,';
	}

	$query = rtrim($query, ',');

	$query .= ') VALUES (';

	foreach($fields as $key => $value){
		$query .= ':' . $key . ','; 
	}

	$query = rtrim($query, ',');

	$query .= ")";

	$fields = processDates($fields);

	query($pdo, $query, $fields);
}

/**
function insertJoke($pdo,$fields){
	
	$query = 'INSERT INTO `joke` (';

	foreach($fields as $key => $value){
		$query .= '`' . $key . '`,';
	}

	$query = rtrim($query, ',');

	$query .= ') VALUES ( ';

	foreach($fields as $key => $value){
		$query .= ':' . $key . ',';
	}

	$query = rtrim($query, ',');

	$query .= ")";

	$fields = processDates($fields);

	query($pdo, $query, $fields);
}

function insertAuthor($pdo, $fields){
	$query = 'INSERT INTO `author` (';

	foreach($field as $key => $value){
		$query .= '`' . $key . '`,';
	}

	$query = rtrim($query, ',');

	$query .= ') VALUES (';

	foreach($fields as $key => $value){
		$query .= ':' . $key . ',';
	}

	$query = rtrim($query, ',');

	$query .= ')';

	$fields = processDates($fields);

	query($pdo, $query, $fields);
}
**/

//replaces the next two delete functions
function delete($pdo, $table, $primaryKey, $id){
	$sql = 'DELETE FROM `' . $table . '` WHERE `' . $primaryKey . '` = :id';

	$parameters = [':id' => $id];

	query($pdo, $sql, $parameters);
}

/**
function deleteJoke($pdo, $jokeid){
	$sql = 'DELETE FROM `joke` WHERE `id` = :id';

	$parameters = [':id' => $jokeid];

	query($pdo, $sql, $parameters);
}

function deleteAuthor($pdo, $authorid){
	$sql  = 'DELETE FROM `author` WHERE id = :id';

	$parameters = [':id' => $authorid];

	query($pdo, $sql, $parameters);
}
**/

//replaces next two functions for a Generic function
function findAll($pdo, $table){
	$sql = 'SELECT * FROM `' . $table . '`';

	$results = query($pdo, $sql);

	return $results->fetchAll(); 
}

/**
function allJokes($pdo){
	$sql = 'SELECT `joketext`,`joke`.`id`, `name`, `email`, `jokedate` FROM `joke`
					INNER JOIN `author` ON `authorid` = `author`.`id`';

	$jokes = query($pdo, $sql);
	
	return $jokes->fetchAll();

}

function allAuthors($pdo){
	$sql = 'SELECT * FROM `authors`';

	$authors = query($pdo, $sql);

	return $authors->fetchAll();
}
**/

//replaces static update function
function update($pdo, $table, $primaryKey, $fields) {
	$query = ' UPDATE `' . $table .'` SET ';

	foreach ($fields as $key => $value) {
		$query .= '`' . $key . '` = :' . $key . ',';
	}

	$query = rtrim($query, ',');

	$query .= " WHERE `" . $primaryKey . "` = :primaryKey";

	//set :primaryKey
	$fields['primaryKey'] = $fields['id'];

	$fields = processDates($fields);

	query($pdo, $query, $fields);
}
/**
function updateJoke($pdo, $fields) {
	$query = ' UPDATE `joke` SET ';

	foreach ($fields as $key => $value) {
		$query .= '`' . $key . '` = :' . $key . ',';
	}

	$query = rtrim($query, ',');

	$query .= " WHERE `id` = :primaryKey";

	//set :primaryKey
	$fields['primaryKey'] = $fields['id'];

	$fields = processDates($fields);

	query($pdo, $query, $fields);
}
**/

function processDates($fields) {
	foreach ($fields as $key => $value) {
		if ($value instanceof DateTime) {
			$fields[$key] = $value->format('Y-m-d');
		}
	}
	return $fields;
}

function save($pdo, $table, $primaryKey, $record){
	try{
		if($record[$primaryKey] == ''){
			$record[$primaryKey] = null;
		}
		insert($pdo, $table, $record);
	}
	catch (PDOException $e){
		update($pdo, $table, $primaryKey, $record);
	}
}