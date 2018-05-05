<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'Api',
    ['path' => '/api'],
    function (RouteBuilder $routes) {

        //route debug api
        $routes->connect('/viewlogs/*', ['controller' => 'Debug', 'action' => 'viewlogs']);

        //route debug api
        //$routes->connect('/dummy/:apigroup/*', ['controller' => 'Dummy', 'action' => 'index']);

        //list route api
        //$routes->connect('/api/test/:action', ['controller' => 'Test']);

        $routes->fallbacks(DashedRoute::class);
    }
);
