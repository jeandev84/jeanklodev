<?php

require_once __DIR__.'/../vendor/autoload.php';

/*
$collection = new \Jan\Component\Routing\RouteCollection();


$collection->map(['GET'], '/', 'SiteController@index', 'home');
$collection->map(['GET'], '/about', 'SiteController@about', 'about');
$collection->map(['GET'], '/news', 'SiteController@news', 'news');
$collection->map(['GET'], '/contact', 'SiteController@contact', 'contact');
$collection->map(['POST'], '/contact', 'SiteController@send', 'send');


dump($collection->getRoutes());
*/


$router = new \Jan\Component\Routing\Router();


$router->map(['GET'], '/', 'SiteController@index', 'home');
$router->map(['GET'], '/about', 'SiteController@about', 'about');
$router->map(['GET'], '/news', 'SiteController@news', 'news');
$router->map(['GET'], '/contact', 'SiteController@contact', 'contact');
$router->map(['POST'], '/contact', 'SiteController@send', 'send');
$router->map(['GET'], '/user/{id}', 'UserController@show', 'user.show')
       ->whereNumber('id');

dump($router->getRoutes());


$request = new \Jan\Component\Http\Request\Request();

if (! $route = $router->match($request->getMethod(), $path = $request->getPath())) {
    dd('Route ( '. $path .' ) not found.');
}

dump($route);


