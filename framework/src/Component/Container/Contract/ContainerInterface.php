<?php
namespace Jan\Component\Container\Contract;


/**
 * @see ContainerInterface
 *
 * @package Jan\Component\DependencyInjection
*/
interface ContainerInterface
{

     /**
      * @param $id
      * @return mixed
     */
     public function get($id);



     /**
      * @param $id
      * @return bool
     */
     public function has($id): bool;
}