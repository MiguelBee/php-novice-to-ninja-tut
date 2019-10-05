<?php

namespace Ijdb;

//defines class and implements Routes interface
class Ijdbroutes implements \Ninja\Routes

{
		// dependency injection(vs Service Locator)
	public  function getRoutes(){
		include __DIR__ . '/../../includes/DatabaseConnection.php';

		$jokesTable = new \Ninja\DatabaseTable($pdo, 'joke', 'id');
		$authorsTable = new \Ninja\DatabaseTable($pdo, 'author', 'id');

		$jokeController = new \Ijdb\Controllers\Joke($authorsTable, $jokesTable);

		//create array to call relevant action, removed dependency injection
		$routes = [
			'joke/edit' => [
				'POST' => [
					'controller' => $jokeController,
					'action' => 'saveEdit',
				],
				'GET' => [
					'controller' => $jokeController,
					'action' =>  'edit'
				]
			],
			'joke/delete' => [
				'POST' => [
					'controller' => $jokeController,
					'action' => 'delete',
				]
			],
			'joke/list' => [
				'GET' => [
					'controller' => $jokeController,
					'action' => 'list'
				]
			],
			'' => [
				'GET' => [
					'controller' => $jokeController,
					'action' => 'home'
				]
			],
			'joke/home' => [
				'GET' => [
					'controller' => $jokeController,
					'action' => 'home'
				]
			]
		];

		return $routes;

	}
}