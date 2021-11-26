<?php
namespace Jan\Component\Routing\Resources;


use Jan\Component\Routing\Exception\RouteException;
use Jan\Component\Routing\Resource;
use Jan\Component\Routing\RouteCollection;


/**
 * @see WebResource
 *
 * @package Jan\Component\Routing\Resources
*/
class WebResource extends Resource
{

    public function configure()
    {
        $this->add('GET', 'index', 's')
             ->add('GET',  'show', '/{id}', ['id' => '\d+'])
             ->add('GET|POST','create')
             ->add('GET|POST', 'edit', '/{id}/edit', ['id' => '\d+'])
             ->add('DELETE',  'delete', '/{id}/delete', ['id' => '\d+'])
             ->add('GET', 'restore','/{id}/restore',  ['id' => '\d+'])
       ;
    }



    /**
     * @param RouteCollection $collection
     * @return mixed|void
     * @throws RouteException
    */
    public function mapRoutes(RouteCollection $collection)
    {
        foreach ($this->getData() as $data) {
            $collection->map($data['methods'], $data['path'], $data['callback'], $data['name'])
                       ->where($data['patterns']);
        }
    }


}