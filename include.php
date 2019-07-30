<?php

header('Access-Control-Allow-Origin: *');

require("vendor/autoload.php");


$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$apiToken = getenv("API_TOKEN");