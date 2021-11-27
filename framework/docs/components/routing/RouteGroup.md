# Route Group
```
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


$router->get('/', function () {
    echo 'Welcome!';
}, 'welcome');


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

if (! $route = $router->match($request->getMethod(), $path = $request->getPath())) {
    dd('Route ( '. $path .' ) not found.');
}

dump($route);
```