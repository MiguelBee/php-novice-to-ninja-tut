<?php

namespace Ijdb\Entity;

use Ninja\DatabaseTable;

class Category {
	public $id;
	public $name;
	private $jokesTable;
	private $jokesCategoryTable;

	public function __construct(DatabaseTable $jokesTable, DatabaseTable $jokesCategoryTable){
		$this->jokesTable = $jokesTable;
		$this->jokesCategoryTable = $jokesCategoryTable;
	}

	public function getJokes(){
		$jokeCategories = $this->jokesCategoryTable->find('categoryId', $this->id);

		$jokes = [];

		foreach($jokeCategories as $jokeCategory) {
			$joke = $this->jokesTable->findById($jokeCategory->jokeId);
			if ($joke) {
				$jokes[] = $joke;
			}
		}
		return $jokes;
	}

}