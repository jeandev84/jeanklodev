<?php
namespace Jan\Component\Routing\Contract;


/**
 * @see RouterInterface
 *
 * @package Jan\Component\Routing\Contract
*/
interface RouterInterface
{

    /**
     * Get route collections
     *
     * @return mixed
    */
    public function getRoutes();




    /**
     * Get current route
     *
     * @return mixed
    */
    public function getCurrentRoute();




    /**
     * Determine if the current method and path URL match route
     *
     * @param string $requestMethod
     * @param string $requestUri
     * @return mixed
    */
    public function match(string $requestMethod, string $requestUri);

}