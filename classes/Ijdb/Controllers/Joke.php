<?php

namespace Ijdb\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;

class Joke {
	private $authorsTable;
	private $jokesTable;
	private $categoriesTable;
	private $authentication;


	public function __construct(DatabaseTable $authorsTable, DatabaseTable $jokesTable, DatabaseTable $categoriesTable, Authentication $authentication){
		$this->authorsTable = $authorsTable;
		$this->jokesTable = $jokesTable;
		$this->categoriesTable = $categoriesTable;
		$this->authentication = $authentication;
	}

	public function home(){
		$title = 'Internet Joke Database';

		return ['template' => "home.html.php", 'title' => $title];
	}

	public function list(){
		if (isset($_GET['category'])) {
			$category = $this->categoriesTable->findById($_GET['category']);
			$jokes = $category->getJokes();
		}
		else {
			$jokes = $this->jokesTable->findAll();
		}

		$title = 'Joke List';

		$author = $this->authentication->getUser();

		$totalJokes = $this->jokesTable->total();

		return ['template' => 'jokes.html.php', 'title' => $title, 
						'variables' => [
							'totalJokes' => $totalJokes,
							'jokes' => $jokes,
							'user' => $author, // previously $userId => $author->id
							'categories' => $this->categoriesTable->findAll(),
						]
					];
	}

	public function delete(){
		$author = $this->authentication->getUser();

		$joke = $this->jokesTable->findById($_POST['id']);

		if($joke->authorid != $author->id && !$author->hasPermission(\Ijdb\Entity\Author::DELETE_JOKES)){
			return;
		}
		
		$this->jokesTable->delete($_POST['id']);

		header('location: /joke/list');
	}

	public function saveEdit(){
		$author = $this->authentication->getUser();

		//this was suggested in book, but doesn work
		//$authorObject = new \Ijdb\Entity\Author($this->jokesTable);

		$joke = $_POST['joke'];
		$joke['jokedate'] = new \DateTime();
		$joke['authorId'] = $author->id;

		$jokeEntity = $author->addJoke($joke);

		$jokeEntity->clearCategories();
		
		foreach ($_POST['category'] as $categoryId) {
			$jokeEntity->addCategory($categoryId);
		}

		header('location: /joke/list');
	} 

	public function edit() {
		$author = $this->authentication->getUser();
		$categories = $this->categoriesTable->findAll();

		if(isset($_GET['id'])){
			$joke = $this->jokesTable->findById($_GET['id']);
		}
		$title = 'Edit Joke';

		return ['template' => 'editjoke.html.php', 'title' => $title,
						'variables' => [
							'joke' => $joke ?? null,
							'user' => $author, // replaces userId = $author->id
							'categories' => $categories
						]
				];
	}


}