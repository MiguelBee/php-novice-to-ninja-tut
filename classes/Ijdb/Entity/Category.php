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

	public function getJokes($limit = null, $offset = null){
		$jokeCategories = $this->jokesCategoryTable->find('categoryId', $this->id, null, $limit, $offset);

		$jokes = [];

		foreach($jokeCategories as $jokeCategory) {
			$joke = $this->jokesTable->findById($jokeCategory->jokeId);
			if ($joke) {
				$jokes[] = $joke;
			}
		}

		usort($jokes, [$this, 'sortJokes']);
		return $jokes;
	}

	private function sortJokes ($a, $b) {
		$aDate = new \DateTime($a->jokedate);
		$bDate = new \DateTime($b->jokedate);

		if ($aDate->getTimestamp() == $bDate->getTimestamp()) {
			return 0;
		}
		return $aDate->getTimestamp() > $bDate->getTimestamp() ? -1 : 1;
	}

	public function getNumJokes() {
		return $this->jokesCategoryTable->total('categoryId', $this->id);
	}

}
