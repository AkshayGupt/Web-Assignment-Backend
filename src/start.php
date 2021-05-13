<?php
require 'vendor/autoload.php';
use Dotenv\Dotenv;
use Src\Database;

$dotenv = new DotEnv(__DIR__);
$dotenv->load();

// test code:
// it will output: localhost
// when you run $ php start.php
echo getenv('DB_HOST');

$dbConnection = (new Database())->connect();
