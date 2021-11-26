<?php
namespace Jan\Component\Routing\Contract;


/**
 * Interface RouteMatchedInterface
 *
 * @package Jan\Component\Routing\Contract
*/
interface RouteMatchedInterface
{
    /**
     * Determine if the current method and path URL match route
     *
     * @param string $requestMethod
     * @param string $requestUri
     * @return mixed
    */
    public function match(string $requestMethod, string $requestUri);
}