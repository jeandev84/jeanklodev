<?php
namespace Jan\Component\Routing;


/**
 * @see Resource
 *
 * @package Jan\Component\Routing
*/
abstract class Resource
{

     /**
      * @var string
     */
     protected $path;


     /**
      * @var string
     */
     protected $controller;



     /**
      * @var string
     */
     protected $name;




     /**
      * @var array
     */
     protected $data = [];




     /**
      * @param string $path
      * @param string $controller
     */
     public function __construct(string $path , string $controller)
     {
         $this->path       = $path;
         $this->controller = $controller;
         $this->name       = str_replace('/', '_', $path);

         $this->configure();
     }


     /**
      * configure resource
      * @param string $methods
      * @param string $action
      * @param string $suffix
      * @param array $patterns
      * @return Resource
     */
     public function add(string $methods, string $action, string $suffix = '', array $patterns = []): Resource
     {
         $this->data[$action] = [
             'methods'     => $methods,
             'path'        => sprintf('/%s%s', $this->path, $suffix),
             'callback'    => sprintf('%s@%s', $this->controller, $action),
             'name'        => sprintf('%s.%s', $this->name, $action),
             'patterns'    => $patterns
         ];

         return $this;
     }




     /**
      * @return array
     */
     public function getData(): array
     {
         return $this->data;
     }



     /**
      * @return mixed
     */
     abstract public function configure();



     /**
      * @param RouteCollection $collection
      * @return mixed
     */
     abstract public function mapRoutes(RouteCollection $collection);

}