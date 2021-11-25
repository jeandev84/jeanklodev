<?php

require_once __DIR__.'/../vendor/autoload.php';

$route = new \Jan\Component\Routing\Route(['GET'], '/', function () {
    return 'Welcome!';
}, 'home.');

$route->where('id', '\d+')->name('foo');

$collection = new \Jan\Component\Routing\RouteCollection();

$collection->addRoute($route);

$collection->map(['GET'], '/contact', 'SiteController@contact' )
           ->name('contact');


dd($collection->getRoutes());