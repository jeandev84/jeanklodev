<?php

require_once __DIR__.'/../vendor/autoload.php';

$route = new \Jan\Component\Routing\Route();


$collection = new \Jan\Component\Routing\RouteCollection();

$collection->addRoute($route);