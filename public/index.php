<?php

require_once __DIR__.'/../vendor/autoload.php';

$request = new \Jan\Component\Http\Request\Request();
$router = new \Jan\Component\Routing\Router();

$router->patterns([
    'name'   => '.*',
    'id'     =>  '[0-9]+',
    'slug'   => '[a-z\-0-9]+'
]);


$options = [
    'prefix'    => 'admin/',
    'namespace' => 'Admin\\',
    'name'      => 'admin.',
    'middleware' => [
        \App\Middleware\Authenticated::class
    ]
];

// $options = [];

/*
$router->get( '/', 'SiteController@index', 'home');
$router->get('/about', 'SiteController@about', 'about');
$router->get('/news', 'SiteController@news', 'news');
$router->get('/contact', 'SiteController@contact', 'contact');
$router->get( '/search/{name}', 'SearchController@index', 'search');
$router->post( '/contact', 'SiteController@send', 'send');


$router->get( '/user/{id}', 'UserController@show', 'user.show')
       //->whereNumber('id')
       ->middleware(\App\Middleware\Authenticated::class);


$router->put( '/user/{id}', 'UserController@edit', 'user.edit')
       ->whereNumber('id')
       ->middleware(\App\Middleware\GuardMiddleware::class);

$router->delete( '/user/{id}', 'UserController@delete', 'user.delete')
       ->whereNumber('id')
       ->middleware(\App\Middleware\GuardMiddleware::class);
*/


$router->get('/', function () {
    echo 'Welcome!';
}, 'welcome');


$options = [];
$router->group(function () use ($router) {

    $router->get( '/users', 'UserController@list', 'list');
    $router->get( '/user/{id}', 'UserController@show', 'show');
    $router->post( '/user', 'UserController@create', 'create');
    $router->put( '/user/{id}', 'UserController@edit', 'edit')
           ->middleware(\App\Middleware\GuardMiddleware::class);

    $router->delete('/user/{id}', 'UserController@delete', 'delete');

}, $options);



dump($router->getRoutes());

$request = new \Jan\Component\Http\Request\Request();

if (! $router->match($request->getMethod(), $path = $request->getPath())) {
    dd('Route ( '. $path .' ) not found.');
}

dump($router->getRoute());


