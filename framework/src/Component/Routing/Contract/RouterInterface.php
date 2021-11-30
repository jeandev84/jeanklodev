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
    public function getRoute();




    /**
     * Determine if the current method and path URL match route
     *
     * @param string $requestMethod
     * @param string $requestPath
     * @return mixed
    */
    public function match(string $requestMethod, string $requestPath);




    /**
     * @param string $name
     * @param array $parameters
     * @return mixed
    */
    public function generate(string $name, array $parameters = []);

}