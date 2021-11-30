<?php

require_once __DIR__.'/../vendor/autoload.php';


$collection = new \Jan\Component\Routing\RouteCollection();


$collection->patterns([
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
$collection->get( '/', 'SiteController@index', 'home');
$collection->get('/about', 'SiteController@about', 'about');
$collection->get('/news', 'SiteController@news', 'news');
$collection->get('/contact', 'SiteController@contact', 'contact');
$collection->get( '/search/{name}', 'SearchController@index', 'search');
$collection->post( '/contact', 'SiteController@send', 'send');


$collection->get( '/user/{id}', 'UserController@show', 'user.show')
       //->whereNumber('id')
       ->middleware(\App\Middleware\Authenticated::class);


$collection->put( '/user/{id}', 'UserController@edit', 'user.edit')
       ->whereNumber('id')
       ->middleware(\App\Middleware\GuardMiddleware::class);

$collection->delete( '/user/{id}', 'UserController@delete', 'user.delete')
       ->whereNumber('id')
       ->middleware(\App\Middleware\GuardMiddleware::class);
*/


$collection->get('/', function () {
    echo 'Welcome!';
}, 'welcome');


$collection->group(function (\Jan\Component\Routing\RouteCollection $collection)  {

    $collection->get( '/users', 'UserController@list', 'user.list');
    $collection->get( '/user/{id}', 'UserController@show', 'user.show');
    $collection->post( '/user', 'UserController@create', 'user.create');
    $collection->put( '/user/{id}', 'UserController@edit', 'user.edit')
           ->middleware(\App\Middleware\GuardMiddleware::class);

    $collection->delete('/user/{id}', 'UserController@delete', 'user.delete');

}, $options);



$routes = $collection->getRoutes();

$request = new \Jan\Component\Http\Request\Request();

$router = new \Jan\Component\Routing\Router($routes);
$router->setURL('http://localhost');

if (! $router->match($request->getMethod(), $path = $request->getPath())) {
    dd('Route ( '. $path .' ) not found.');
}

dump($router->getRoutes());
dump($router->getRoute());

echo 'GENERATED URL : '. $router->generate('admin.user.show', ['id' => 3]);



