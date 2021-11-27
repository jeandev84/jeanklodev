<?php
namespace Jan\Foundation\Routing;


use Jan\Component\Http\Request\Request;
use Jan\Component\Routing\Route;
use Jan\Component\Routing\RouteCollection;


/**
 * @see UrlMatcher
 *
 * @package Jan\Foundation\Routing
*/
class UrlMatcher
{

      /**
       * @param RouteCollection $collection
       * @param Request $request
       * @return Route|bool
       */
      public function match(RouteCollection $collection, Request $request)
      {
          foreach ($collection->getRoutes() as $route) {
                if ($route->match($request->getMethod(), $request->getPath())) {
                     return $route;
                }
           }

           return false;
      }
}