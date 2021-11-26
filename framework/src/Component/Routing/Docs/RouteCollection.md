# Route Collection
```
Example 1.

$routes = [
    new \Jan\Component\Routing\Route(['GET'], '/', 'SiteController@index', 'home'),
    new \Jan\Component\Routing\Route(['GET'], '/about', 'SiteController@about', 'about'),
    new \Jan\Component\Routing\Route(['GET'], '/news', 'SiteController@news', 'news'),
    new \Jan\Component\Routing\Route(['GET'], '/contact', 'SiteController@contact', 'contact'),
    new \Jan\Component\Routing\Route(['POST'], '/contact', 'SiteController@send', 'send'),
];


$collection->addRoutes($routes);


dd($collection->getRoutes());

Example 2.


```