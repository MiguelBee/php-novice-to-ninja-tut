<?php

namespace Ijdb;

//defines class and implements Routes interface
class Ijdbroutes implements \Ninja\Routes

{
	private $authorsTable;
	private $jokesTable;
	private $categoriesTable;
	private $authentication;
	private $jokesCategoriesTable;

	public function __construct(){

		include __DIR__ . '/../../includes/DatabaseConnection.php';

		$this->jokesTable = new \Ninja\DatabaseTable($pdo, 'joke', 'id', '\Ijdb\Entity\Joke', [&$this->authorsTable, &$this->jokesCategoriesTable]);
		$this->authorsTable = new \Ninja\DatabaseTable($pdo, 'author', 'id', '\Ijdb\Entity\Author', [&$this->jokesTable]);
		$this->categoriesTable = new \Ninja\DatabaseTable($pdo, 'category', 'id', '\Ijdb\Entity\Category', [&$this->jokesTable, &$this->jokesCategoriesTable]);
		$this->authentication = new \Ninja\Authentication($this->authorsTable, 'email', 'password');
		$this->jokesCategoriesTable = new \Ninja\DatabaseTable($pdo, 'joke_category', 'categoryId');
		// dependency injection(vs Service Locator)
	}
	public function getRoutes(): array{

		$jokeController = new \Ijdb\Controllers\Joke($this->authorsTable, $this->jokesTable, $this->categoriesTable, $this->authentication);
		$authorController = new \Ijdb\Controllers\Register($this->authorsTable);
		$loginController = new \Ijdb\Controllers\Login($this->authentication);
		$categoryController = new \Ijdb\Controllers\Category($this->categoriesTable);

		//create array to call relevant action, removed dependency injection
		$routes = [
			'category/edit' => [
				'POST' => [
					'controller' => $categoryController,
					'action' => 'saveEdit'
				],
				'GET' => [
					'controller' => $categoryController,
					'action' => 'edit'
				],
				'login' => TRUE,
				'permissions' => \Ijdb\Entity\Author::EDIT_CATEGORIES
			],
			'category/list' => [
				'GET' => [
					'controller' => $categoryController,
					'action' => 'list'
				],
				'login' => TRUE,
				'permissions' => \Ijdb\Entity\Author::LIST_CATEGORIES
			],
			'category/delete' => [
				'POST' => [
					'controller' => $categoryController,
					'action' => 'delete'
				],
				'login' => TRUE,
				'permissions' => \Ijdb\Entity\Author::REMOVE_CATEGORIES
			],
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
			'author/permissions' => [
				'GET' => [
					'controller' => $authorController,
					'action' => 'permissions'
				],
				'POST' => [
					'controller' => $authorController,
					'action' => 'savePermissions'
				],
				'login' => TRUE,
				'permissions' => \Ijdb\Entity\Author::EDIT_USER_ACCESS
			],
			'author/list' => [
				'GET' => [
					'controller' => $authorController,
					'action' => 'list'
				],
				'login' => TRUE,
				'permissions' => \Ijdb\Entity\Author::EDIT_USER_ACCESS
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
			'login/permissionserror' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'permissionsError'
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

	public function checkPermission($permission): bool {
		$user = $this->authentication->getUser();

		if ($user && $user->hasPermission($permission)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}