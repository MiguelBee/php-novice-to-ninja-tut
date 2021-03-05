<?php

namespace Ijdb\Entity;

class Joke {
	public $id;
	public $authorId;
	public $jokedate;
	public $joketext;
	private $authorsTable;
	private $author;
	private $jokesCategoriesTable;

	public function __construct(\Ninja\DatabaseTable $authorsTable, \Ninja\DatabaseTable $jokesCategoriesTable) {
		$this->authorsTable = $authorsTable;
		$this->jokesCategoriesTable = $jokesCategoriesTable;
	}

	public function getAuthor() {
		if (empty($this->author)) {
			$this->author = $this->authorsTable->findById($this->authorId);
		}

		return $this->author;
	}

	public function addCategory($categoryId) {
		$jokeCat = ['jokeId' => $this->id, 'categoryId' => $categoryId];

		$this->jokesCategoriesTable->save($jokeCat);
	}

	public function hasCategory($categoryId) {
		$jokesCategories = $this->jokesCategoriesTable->find('jokeId', $this->id);

		foreach($jokesCategories as $jokeCategory) {
			if ($jokeCategory->categoryId == $categoryId){
				return TRUE;
			}
		}
	}

	public function clearCategories() {
		$this->jokesCategoriesTable->deleteWhere('jokeId', $this->id);
	}

}