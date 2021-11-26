# Route Collection
```
require_once __DIR__.'/../vendor/autoload.php';

$collection = new \Jan\Component\Routing\RouteCollection();


Example 1.

$route = new \Jan\Component\Routing\Route(['GET'], '/', 'SiteController@index', 'home');

$collection->addRoute($route)
           ->name('home')
           ->middleware(Authenticated::class);


dump($collection->getRoutes());



Example 2.

$routes = [
    new \Jan\Component\Routing\Route(['GET'], '/', 'SiteController@index', 'home'),
    new \Jan\Component\Routing\Route(['GET'], '/about', 'SiteController@about', 'about'),
    new \Jan\Component\Routing\Route(['GET'], '/news', 'SiteController@news', 'news'),
    new \Jan\Component\Routing\Route(['GET'], '/contact', 'SiteController@contact', 'contact'),
    new \Jan\Component\Routing\Route(['POST'], '/contact', 'SiteController@send', 'send'),
];


$collection->addRoutes($routes);


dump($collection->getRoutes());


Example 3.


$collection->map(['GET'], '/', 'SiteController@index', 'home');
$collection->map(['GET'], '/about', 'SiteController@about', 'about');
$collection->map(['GET'], '/news', 'SiteController@news', 'news');
$collection->map(['GET'], '/contact', 'SiteController@contact', 'contact');
$collection->map(['POST'], '/contact', 'SiteController@send', 'send');


dump($collection->getRoutes());

```