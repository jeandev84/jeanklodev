# Route Group
```
require_once __DIR__.'/../vendor/autoload.php';

$request = new \Jan\Component\Http\Request\Request();
$collection = new \Jan\Component\Routing\Router();

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


$collection->get('/', function () {
    echo 'Welcome!';
}, 'welcome');


$collection->group(function () use ($router) {

    $collection->get( '/users', 'UserController@list', 'list');
    $collection->get( '/user/{id}', 'UserController@show', 'show');
    $collection->post( '/user', 'UserController@create', 'create');
    $collection->put( '/user/{id}', 'UserController@edit', 'edit')
           ->middleware(\App\Middleware\GuardMiddleware::class);

    $collection->delete('/user/{id}', 'UserController@delete', 'delete');

}, $options);



dump($collection->getRoutes());

$request = new \Jan\Component\Http\Request\Request();

$router
if (! $route = $router->match($request->getMethod(), $path = $request->getPath())) {
    dd('Route ( '. $path .' ) not found.');
}

dump($route);
```