<?php

namespace Ijdb;

//defines class and implements Routes interface
class Ijdbroutes implements \Ninja\Routes

{
	private $authorsTable;
	private $jokestable;
	private $authentication;

	public function __construct(){

		include __DIR__ . '/../../includes/DatabaseConnection.php';

		$this->jokesTable = new \Ninja\DatabaseTable($pdo, 'joke', 'id');
		$this->authorsTable = new \Ninja\DatabaseTable($pdo, 'author', 'id');
		$this->authentication = new \Ninja\Authentication($this->authorsTable, 'email', 'password');
		// dependency injection(vs Service Locator)
	}
	public function getRoutes(): array{

		$jokeController = new \Ijdb\Controllers\Joke($this->authorsTable, $this->jokesTable, $this->authentication);
		$authorController = new \Ijdb\Controllers\Register($this->authorsTable);
		$loginController = new \Ijdb\Controllers\Login($this->authentication);

		//create array to call relevant action, removed dependency injection
		$routes = [
			'author/register' => [
				'GET' => [
					'controller' => $authorController,
					'action' => 'registrationForm'
				],
				'POST' => [
					'controller' => $authorController,
					'action' => 'registerUser'
				]
			],
			'author/success' => [
				'GET' => [
					'controller' => $authorController,
					'action' => 'success'
				]
			],
			'joke/edit' => [
				'POST' => [
					'controller' => $jokeController,
					'action' => 'saveEdit',
				],
				'GET' => [
					'controller' => $jokeController,
					'action' =>  'edit'
				],
				'login' => TRUE
			],
			'joke/delete' => [
				'POST' => [
					'controller' => $jokeController,
					'action' => 'delete',
				],
				'login' => TRUE
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
			],
			'login' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'loginForm'
				],
				'POST' => [
					'controller' => $loginController,
					'action' => 'processLogin'
				]
			],
			'login/success' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'success'
				]
			],
			'login/error' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'error'
				]
			],
			'logout' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'logout'
				]
			]
		];

		return $routes;

	}

	public function getAuthentication(): \Ninja\Authentication {
		return $this->authentication;
	}
}