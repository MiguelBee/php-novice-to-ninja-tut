<?php

//Include the database connection, $pdo
include_once __DIR__ . '/../includes/DatabaseConnection.php';

//Include the totalJoke() function
include_once __DIR__ . '/../includes/DatabaseFunctions.php';

//call totalJokes() function

echo totalJokes($pdo);