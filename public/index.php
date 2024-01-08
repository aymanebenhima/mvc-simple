<?php

use Router\Router;
use App\Exceptions\NotFoundException;

require '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
define('VIEWS', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
define('SCRIPTS', dirname($_SERVER['SCRIPT_NAME']) . DIRECTORY_SEPARATOR);

require '../app/config/app.php';
// define('DB_NAME', 'mvcapp');
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PWD', '');

$router = new Router($_GET['url']);

require '../routes/web.php';

try {
    $router->run();
} catch (NotFoundException $e) {
    return $e->error404();
}