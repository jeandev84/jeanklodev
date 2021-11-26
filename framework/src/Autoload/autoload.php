<?php
require_once __DIR__ . '/Autoloader.php';

$autoloader = \Jan\Autoload\Autoloader::load(__DIR__ . '/../framework/');

$autoloader->namespaces(['App\\' => 'app/', 'Jan\\' => 'framework/src/']);

$autoloader->register();