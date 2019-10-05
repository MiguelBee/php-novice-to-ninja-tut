<?php

namespace Ninja;

interface Routes {
	public function getRoutes();
}

//where ever this is 'implemented', that class MUST contain above function
//can now typehint as interface ex."\Ninja\Routes"

//interfaces good for generic framework bridging to project specific code