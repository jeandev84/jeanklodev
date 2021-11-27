<?php
namespace Jan\Component\Routing\Contract;


/**
 * @see RouteDispatcherInterface
 *
 * @package Jan\Component\Routing\Contract
*/
interface RouteDispatcherInterface
{
    /**
     * Dispatch route
     *
     * @param string $requestMethod
     * @param string $requestUri
     * @return mixed
    */
    public function dispatch(string $requestMethod, string $requestUri);
}