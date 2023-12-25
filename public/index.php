<?php
use Router\Router;
require __DIR__ . '/../vendor/autoload.php';

$router = new Router($_GET['url']);
$router->show();