<?php

$pdo = new PDO('mysql:host=localhost;dbname=ninja_jokes; charset=utf8', 'ninja', 'ninja');
	
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
