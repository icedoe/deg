<?php 
/**
 * This is a Anax frontcontroller.
 *
 */

// Include the essential settings.
require __DIR__.'/config.php'; 


// Create services and inject into the app. 
$di  = new \Anax\DI\CDIFactoryDefault();

$di->set('TableController', function() use ($di) {
    $controller = new \Deg\Table\TableController();
    $controller->setDI($di);
    return $controller;
});

$app = new \Anax\Kernel\CAnax($di);



// Home route
$app->router->add('*', function() use ($app) {

    $app->theme->setTitle("Table tester");
//    $app->views->add('me/page');

    $app->dispatcher->forward([
        'controller' => 'table',
        'action'     => 'index',
    ]);

    
});


// Check for matching routes and dispatch to controller/handler of route
$app->router->handle();

// Render the page
$app->theme->render();
