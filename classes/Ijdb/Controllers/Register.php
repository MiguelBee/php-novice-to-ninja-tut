<?php

namespace Ijdb\Controllers;

use \Ninja\DatabaseTable;

class Register
{
	private $authorsTable;

	public function __construct(DatabaseTable $authorsTable){
		$this->authorsTable = $authorsTable;
	}

	public function registrationForm(){
		return['template' => 'register.html.php', 'title' => 'Register an Account'];
	}

	public function success(){
		return['template' => 'registersuccess.html.php', 'title' => 'Registration Successful'];
	}

	public function registerUser(){
		$author = $_POST['author'];

		//Initial value of $valid is assumed true
		$valid = TRUE;
		//wont be completely necessary because we have html fields with 'required' attribute
		$errors = [];

		//If fields are left blank, $valid is false;

		if(empty($author['name'])){
			$valid = FALSE;
			$errors[] = "Name cannot be blank";
		} else {
			$author['name'] = $author['name'];
		}

		if(empty($author['email'])){
			$valid = FALSE;
			$errors[] = "Email cannot be blank";
		} elseif (filter_var($author['email'], FILTER_VALIDATE_EMAIL) == false){
			$valid = false;
			$errors[] = 'Invalid email address';
		} else {
			//if email is not blank and valid, then convert to lowercase
			$author['email'] = strtolower($author['email']);

			//search for duplicate registrations
			if(count($this->authorsTable->find('email', $author['email'])) > 0){
				$valid = FALSE;
				$errors[] = 'That email address is already registered';
			}
		}

		if(empty($author['password'])){
			$valid = FALSE;
			$errors[] = "password cannot be left blank";
		}

		//if $valid is still true, then save the author
		if($valid == TRUE){
			//hash the password before saving it to the db
			$author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);
			
			//author now has a lowercase email and hashed password
			$this->authorsTable->save($author);
			header('Location: /author/success');
		} else {
			//if the $valid is not true, show the form again
			return['template' => 'register.html.php',
						'title' => 'Register an account',
						'variables' => [
							'errors' => $errors,
							'author' => $author
						]
					];
		}
	}

	public function list() {
		$authors = $this->authorsTable->findAll();

		return ['template' => 'authorslist.html.php', 'title' => 'Author List',
			'variables' => [
				'authors' => $authors
			]
		];
	}

	public function permissions() {
		$author = $this->authorsTable->findById($_GET['id']);

		$reflected = new \ReflectionClass('\Ijdb\Entity\Author');
		$constants = $reflected->getConstants();

		return ['template' => 'permissions.html.php',
				'title' => 'Edit Permissions',
				'variables' => [
					'author' => $author,
					'permissions' => $constants
				]
			];
	}

	public function savePermissions() {
		$author = [
			'id' => $_GET['id'],
			'permissions' => array_sum($_POST['permissions'] ?? [])
		];

		$this->authorsTable->save($author);

		header('location: /author/list');
	}
}
