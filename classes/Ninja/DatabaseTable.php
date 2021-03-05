<?php

namespace Ninja;

class DatabaseTable{
	#defined variables can be called directly from inside the methods
	private $pdo;
	private $table;
	private $primaryKey;
	private $className;
	private $constructorArgs;

	public function __construct(\PDO $pdo, string $table, string $primaryKey,
	 string $className = '\stdclass', array $constructorArgs = []){
		$this->pdo = $pdo;
		$this->table = $table;
		$this->primaryKey = $primaryKey;
		$this->className = $className;
		$this->constructorArgs = $constructorArgs;
	}

	private function query($sql, $parameters = []){
		$query = $this->pdo->prepare($sql);

		//assoc array needs name and value
		//foreach($pararmeters as $name => $value){
		//	$query->bindValue($name, $value);
		//}

		//giving the execute() function args also binds values
		$query->execute($parameters);

		return $query;
	}

	public function total(){
		$sql = 'SELECT COUNT(*) FROM `'. $this->table . '`';

		$query = $this->query($sql);

		$row = $query->fetch();

		return $row[0];
	}

	public function findById($id){
		$sql = 'SELECT * FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :id';

		$parameters = [':id' => $id];

		$query = $this->query($sql, $parameters);

		return $query->fetchObject($this->className, $this->constructorArgs);
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
	public function insert($fields){
		$query = 'INSERT INTO `' . $this->table . '` (';

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

		$fields = $this->processDates($fields);

		$this->query($query, $fields);

		return $this->pdo->lastInsertId();
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
	public function delete($id){
		$sql = 'DELETE FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :id';

		$parameters = [':id' => $id];

		$this->query($sql, $parameters);
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
	public function findAll(){
		$sql = 'SELECT * FROM `' . $this->table . '`';

		$results = $this->query($sql);

		return $results->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs); 
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
	public function update($fields) {
		$query = ' UPDATE `' . $this->table .'` SET ';

		foreach ($fields as $key => $value) {
			$query .= '`' . $key . '` = :' . $key . ',';
		}

		$query = rtrim($query, ',');

		$query .= " WHERE `" . $this->primaryKey . "` = :primaryKey";

		//set :primaryKey
		$fields['primaryKey'] = $fields['id'];

		$fields = $this->processDates($fields);

		$this->query($query, $fields);
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

	private function processDates($fields) {
		foreach ($fields as $key => $value) {
			if ($value instanceof \DateTime) {
				$fields[$key] = $value->format('Y-m-d');
			}
		}
		return $fields;
	}

	public function save($record){
		$entity = new $this->className(...$this->constructorArgs);
		try{
			if($record[$this->primaryKey] == ''){
				$record[$this->primaryKey] = null;
			}
			$insertId = $this->insert($record);

			$entity->{$this->primaryKey} = $insertId;
			//curly brackets use
			// same as:
			// $primaryKey = $this->primaryKey;
			// $entity->$primaryKey = $insertId;

		}
		catch (\PDOException $e){
			$this->update($record);
		}

		foreach($record as $key => $value) {
			// Converting an array into an object
			if (!empty($value)) {
				$entity->$key = $value;
			}
		}

		return $entity;
	}

	public function find($column, $value){
		$query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $column . ' = :value';

		$parameters = ['value' => $value];

		$query = $this->query($query, $parameters);

		return $query->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
	}

	// For deleting, then re-adding jokes to class, chpt:13
	public function deleteWhere($column, $value) {
		$sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $column . ' = :value';

		$parameters = [
			'value' => $value
		];

		$query = $this->query($sql, $parameters);
	}
}