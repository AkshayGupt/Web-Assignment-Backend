<?php
require 'vendor/autoload.php';
use Src\Database;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// test code:
// it will output: localhost
// when you run $ php start.php
echo $_ENV['DB_HOST'];

$dbConnection = (new Database())->connect();
