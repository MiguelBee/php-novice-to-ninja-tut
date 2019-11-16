<?php

namespace Ninja;

interface Routes {
	//: array is a form of type hinting to return the correct object
	public function getRoutes(): array;
	public function getAuthentication(): \Ninja\Authentication;
}

//where ever this is 'implemented', that class MUST contain above function
//can now typehint as interface ex."\Ninja\Routes"

//interfaces good for generic framework bridging to project specific code