<?php
namespace Jan\Component\Container\Contract;


/**
 * @see ContainerAwareInterface
 *
 * @package Jan\Component\Container\Contract
*/
interface ContainerAwareInterface
{
     /**
      * @param ContainerInterface $container
      * @return mixed
     */
     public function setContainer(ContainerInterface $container);
}