<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

use Core\View;

session_start();

//Récupération des variables de cookies :
if (isset($_COOKIE["visitorLogged"]) && $_COOKIE["visitorLogged"] && !isset($_SESSION['user'])){
    $_SESSION['user'] = array(
        'id' => $_COOKIE['id'],
        'username' => $_COOKIE['username']
    );
}

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'User', 'action' => 'login']);
$router->add('register', ['controller' => 'User', 'action' => 'register']);
$router->add('logout', ['controller' => 'User', 'action' => 'logout', 'private' => true]);
$router->add('account', ['controller' => 'User', 'action' => 'account', 'private' => true]);
$router->add('product', ['controller' => 'Product', 'action' => 'index', 'private' => true]);
$router->add('product/{id:\d+}', ['controller' => 'Product', 'action' => 'show']);
$router->add('{controller}/{action}');

/*
 * Gestion des erreurs dans le routing
 */
try {
    $router->dispatch($_SERVER['QUERY_STRING']);
} catch(Exception $e){
    switch($e->getMessage()){
        case 'You must be logged in':
            header('Location: /login');
            break;
        case 'No route matched.':
            View::renderTemplate('404.html');
            break;
        default:
            View::renderTemplate('500.html');
    }
}