<?php

require_once __DIR__.'/../vendor/autoload.php';


$router = new \Jan\Component\Routing\Router();


$router->get( '/', 'SiteController@index', 'home');
$router->get('/about', 'SiteController@about', 'about');
$router->get('/news', 'SiteController@news', 'news');
$router->get('/contact', 'SiteController@contact', 'contact');
$router->post( '/contact', 'SiteController@send', 'send');
$router->get( '/user/{id}', 'UserController@show', 'user.show')
       ->whereNumber('id')
       ->middleware(\App\Middleware\Authenticated::class);


$router->put( '/user/{id}', 'UserController@edit', 'user.edit')
       ->whereNumber('id')
       ->middleware(\App\Middleware\GuardMiddleware::class);


$router->delete( '/user/{id}', 'UserController@delete', 'user.delete')
       ->whereNumber('id')
       ->middleware(\App\Middleware\GuardMiddleware::class);


dump($router->getRoutes());

$request = new \Jan\Component\Http\Request\Request();

if (! $route = $router->match($request->getMethod(), $path = $request->getPath())) {
    dd('Route ( '. $path .' ) not found.');
}

dump($route);


