<?php
namespace Jan\Component\Routing\Contract;


/**
 * @see RouteCollectionInterface
 *
 * @package Jan\Component\Routing\Contract
*/
interface RouteCollectionInterface
{
     /**
      * @return array
     */
     public function getRoutes(): array;
}