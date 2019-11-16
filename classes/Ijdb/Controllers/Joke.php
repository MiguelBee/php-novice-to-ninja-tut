<?php

namespace Ijdb\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;

class Joke {
	private $authorsTable;
	private $jokesTable;
	#private $authentication;


	public function __construct(DatabaseTable $authorsTable, DatabaseTable $jokesTable, Authentication $authentication){
		$this->authorsTable = $authorsTable;
		$this->jokesTable = $jokesTable;
		$this->authentication = $authentication;
	}

	public function home(){
		$title = 'Internet Joke Database';

		return ['template' => "home.html.php", 'title' => $title];
	}

	public function list(){
		$results = $this->jokesTable->findAll();

		$jokes = [];

		foreach($results as $joke){
			$author = $this->authorsTable->findById($joke['authorid']);

	#looping through a table and adding by id is essentially what an inner join does in MYSQL
			$jokes[] = [
				'id' => $joke['id'],
				'joketext' => $joke['joketext'],
				'jokedate' => $joke['jokedate'],
				'name' => $author['name'],
				'email' => $author['email'],
				'authorId' => $author['id']
			];
		}

		$title = 'Joke List';

		$totalJokes = $this->jokesTable->total();

		return ['template' => 'jokes.html.php', 'title' => $title, 
						'variables' => [
							'totalJokes' => $totalJokes,
							'jokes' => $jokes,
							'userId' => $author['id'] ?? null
						]
					];
	}

	public function delete(){
		$author = $this->authentication->getUser();

		$joke = $this->jokesTable->findById($_POST['id'])

		if($joke['authorid'] != $author['id']){
			return;
		}
		
		$this->jokesTable->delete($_POST['id']);

		header('location: /joke/list');
	}

	public function saveEdit(){
		$author = $this->authentication->getUser();

		if(isset($_GET['id'])){
			$joke = $this->jokesTable->findById($_GET['id']);

			if($joke['authorid'] != $author['id']){
				return;
			}
		}

		$joke = $_POST['joke'];
		$joke['authorid'] = $author['id'];
		$joke['jokedate'] = new \DateTime();

		$this->jokesTable->save($joke);

		header('location: /joke/list');
	} 

	public function edit() {
		$author = $this->authentication->getUser();

		if(isset($_GET['id'])){
			$joke = $this->jokesTable->findById($_GET['id']);
		}
		$title = 'Edit Joke';

		return ['template' => 'editjoke.html.php', 'title' => $title,
						'variables' => [
							'joke' => $joke ?? null,
							'userId' => $author['id'] ?? null
						]
				];
	}


}