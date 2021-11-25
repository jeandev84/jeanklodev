<?php
namespace Jan\Component\Container;


use Jan\Component\Container\Contract\ContainerInterface;


/**
 * @see Container
 *
 * @package Jan\Component\Container
*/
class Container implements ContainerInterface
{

    public function get($id)
    {
        return $id;
    }

    public function has($id): bool
    {
        return true;
    }
}