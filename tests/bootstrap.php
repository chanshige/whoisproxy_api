<?php
require __DIR__ . '/../vendor/autoload.php';

const APP_DIR = __DIR__ . '/../';

$dotenv = new \Dotenv\Dotenv(APP_DIR);
$dotenv->load();
