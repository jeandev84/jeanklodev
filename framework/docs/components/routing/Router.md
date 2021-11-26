# Router
```
$router = new \Jan\Component\Routing\Router();

$router->patterns([
  'search' => '.*',
  'id'     =>  '[0-9]+',
  'slug'   => '[a-z\-0-9]+'
]);

$request = new \Jan\Component\Http\Request\Request();

Example 1.

$router->map(['GET'], '/', 'SiteController@index', 'home');
$router->map(['GET'], '/about', 'SiteController@about', 'about');
$router->map(['GET'], '/news', 'SiteController@news', 'news');
$router->map(['GET'], '/contact', 'SiteController@contact', 'contact');
$router->map(['POST'], '/contact', 'SiteController@send', 'send');
$router->map(['GET'], '/user/{id}', 'UserController@show', 'user.show')
       ->whereNumber('id')
       ->middleware(\App\Middleware\Authenticated::class);


dump($router->getRoutes());


if (! $route = $router->match($_SERVER['REQUEST_METHOD'], $path = $_SERVER['REQUEST_URI'])) {
    dd('Route ( '. $path .' ) not found.');
}

dump($route);


Example 2.


$router->get(['GET'], '/', 'SiteController@index', 'home');
$router->get(['GET'], '/about', 'SiteController@about', 'about');
$router->get(['GET'], '/news', 'SiteController@news', 'news');
$router->get(['GET'], '/contact', 'SiteController@contact', 'contact');
$router->post(['POST'], '/contact', 'SiteController@send', 'send');
$router->get(['GET'], '/user/{id}', 'UserController@show', 'user.show')
       ->whereNumber('id')
       ->middleware(\App\Middleware\Authenticated::class);



if (! $route = $router->match($request->getMethod(), $path = $request->getPath())) {
    dd('Route ( '. $path .' ) not found.');
}

dump($route);


Example 3.

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

```

