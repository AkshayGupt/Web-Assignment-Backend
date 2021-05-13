<?php
require 'vendor/autoload.php';
use Src\Database;
// include_once './src/Database.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// test code:
// it will output: localhost
// when you run $ php start.php
echo 'Connected to '. $_ENV['DB_HOST'];

$dbConnection = (new Database())->connect();
