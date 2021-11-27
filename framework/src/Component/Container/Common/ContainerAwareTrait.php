<?php
namespace Jan\Component\Container\Common;


use Jan\Component\Container\Contract\ContainerInterface;


/**
 * @see ContainerAwareTrait
 *
 * @package Jan\Component\Container\Common
*/
trait ContainerAwareTrait
{

    /**
     * @var ContainerInterface
    */
    protected $container;




    /**
     * @param ContainerInterface $container
     * @return mixed
     */
    public function setContainer(ContainerInterface $container)
    {
           $this->container = $container;
    }
}