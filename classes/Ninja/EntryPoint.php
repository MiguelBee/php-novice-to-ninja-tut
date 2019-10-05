<?php

namespace Ninja;

class EntryPoint
{
	private $route;
	private $routes;
	private $method;

	public function __construct(string $route, string $method, \Ninja\Routes $routes){
		$this->method = $method;
		$this->route = $route;
		$this->routes = $routes;
		$this->checkUrl();
	}

	private function checkUrl(){
		if($this->route !== strtolower($this->route)){
			http_response_code(301);
			header('location: index.php?route=' . strtolower($this->route));
		}
	}

	private function loadTemplate($templateFileName, $variables = []){
		//extract() makes keys in assoc array variables and values the value of the key/variable
		extract($variables);

		//The php code will be executed, but the resulting HTML will be stored in the buffer
		ob_start();

		//Read the contents of the output buffer and store them
		//in the $output variable for use in the layout.html.php
		include __DIR__ . '/../../templates/' . $templateFileName;

		return ob_get_clean();
	}

	public function run(){
		$routes = $this->routes->getRoutes();

		$controller = $routes[$this->route][$this->method]['controller'];
		$action = $routes[$this->route][$this->method]['action'];

		//need the parens, this variable calls a function
		$page = $controller->$action();

		$title = $page['title'];

		if(isset($page['variables'])){
			$output = $this->loadTemplate($page['template'], $page['variables']);
		} else {
			$output = $this->loadTemplate($page['template']);
		}

		include __DIR__ . '/../../templates/layout.html.php';
	}
}