<?php

class JokesController {
	private $authorsTable;
	private $jokesTable;

	public function __construct(DatabaseTable $authorsTable, DatabaseTable $jokesTable){
		$this->authorsTable = $authorsTable;
		$this->jokesTable = $jokesTable;
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
				'email' => $author['email']
			];
		}

		$title = 'Joke List';

		$totalJokes = $this->jokesTable->total();

		return ['template' => 'jokes.html.php', 'title' => $title, 
						'variables' => [
							'totalJokes' => $totalJokes,
							'jokes' => $jokes
						]
					];
	}

	public function delete(){
		$this->jokesTable->delete($_POST['id']);

		header('location: index.php?action=list');
	}

	public function edit(){
		if(isset($_POST['joke'])){
	
			$joke = $_POST['joke'];
			$joke['authorid'] = 1;
			$joke['jokedate'] = new DateTime();

			$this->jokesTable->save($joke);

			header('location: index.php?action=list');
		} else {
		
			if(isset($_GET['id'])){
				$joke = $this->jokesTable->findById($_GET['id']);
			}
			$title = 'Edit Joke';

			return ['template' => 'editjoke.html.php', 'title' => $title,
							'variables' => [
								'joke' => $joke ?? null
							]
						];
		}
	}


}