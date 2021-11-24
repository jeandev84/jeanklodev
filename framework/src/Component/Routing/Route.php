<?php
namespace Jan\Component\Routing;


use Jan\Component\Routing\Exception\RouteException;

/**
 * @see Route
 *
 * @package Jan\Component\Routing
*/
class Route
{
    /**
     * route path
     *
     * @var string
     */
    protected $path;



    /**
     * route callback
     *
     * @var mixed
     */
    protected $callback;



    /**
     * route name
     *
     * @var string
     */
    protected $name;



    /**
     * route methods
     *
     * @var array
    */
    protected $methods = [];



    /**
     * route regex params
     *
     * @var array
     */
    protected $patterns = [];



    /**
     * route matches params
     *
     * @var array
    */
    protected $matches = [];




    /**
     * route middlewares
     *
     * @var array
    */
    protected $middlewares = [];




    /**
     * route options
     *
     * @var array
    */
    protected $options = [];




    /**
     * named routes collection
     *
     * @var array
    */
    public static $collectNamed = [];



    /**
     * Route constructor
     *
     * @param array $methods
     * @param string|null $path
     * @param null $callback
     * @param string|null $name
    */
    public function __construct(array $methods = [], string $path = null, $callback = null, string $name = null)
    {
         $this->methods  = $methods;
         $this->path     = $path;
         $this->callback = $callback;
         $this->name     = $name;
    }



    /**
     * get route methods
     *
     * @return array
    */
    public function getMethods(): array
    {
        return $this->methods;
    }




    /**
     * get route path
     *
     * @return string|null
    */
    public function getPath(): ?string
    {
        return $this->path;
    }



    /**
     * get route callback
     *
     * @return mixed
    */
    public function getCallback()
    {
        return $this->callback;
    }



    /**
     * get route name
     *
     * @return string
    */
    public function getName(): ?string
    {
        return $this->name;
    }




    /**
     * get route patterns
     *
     * @return array
    */
    public function getPatterns(): array
    {
        return $this->patterns;
    }





    /**
     * set route methods
     *
     * @param array $methods
     * @return Route
    */
    public function methods(array $methods): Route
    {
        $this->methods = $methods;

        return $this;
    }




    /**
     * set route path
     *
     * @param string $path
     * @return Route
    */
    public function path(string $path): Route
    {
        $this->path = $path;

        return $this;
    }




    /**
     * set route callback
     *
     * @param mixed $callback
     * @return Route
    */
    public function callback($callback): Route
    {
        $this->callback = $callback;

        return $this;
    }





    /**
     * set matches params
     *
     * @param array $matches
     * @return Route
    */
    public function matches(array $matches): Route
    {
        $this->matches = $matches;

        return $this;
    }



    /**
     * set route name
     *
     * @param string|null $name
     * @return Route
     * @throws RouteException
    */
    public function name(string $name): Route
    {
        $name = $this->name . $name;

        if (\array_key_exists($name, static::$collectNamed)) {
             throw new RouteException(sprintf('This route name (%s) already taken!', $name));
        }

        static::$collectNamed[$name] = $this;

        $this->name = $name;

        return $this;
    }



    /**
     * set route middlewares
     *
     * @param $middleware
     * @return $this
    */
    public function middleware($middleware): Route
    {
        $this->middlewares = array_merge($this->middlewares, (array) $middleware);

        return $this;
    }



    /**
     * set route regex params
     *
     * @param $name
     * @param null $regex
     * @return Route
     */
    public function where($name, $regex = null): Route
    {
        foreach ($this->parseWhere($name, $regex) as $name => $regex) {
            $this->patterns[$name] = '(?P<'. $name .'>'. $this->resolveRegex($regex) . ')';
        }

        return $this;
    }




    /**
     * @param string $name
     * @return $this
     */
    public function whereNumeric(string $name): Route
    {
        return $this->where($name, '[0-9]+'); // (\d+)
    }




    /**
     * @param string $name
     * @return Route
    */
    public function anything(string $name): Route
    {
        return $this->where($name, '.*');
    }




    /**
     * @param string $name
     * @return $this|Route
     */
    public function whereWord(string $name): Route
    {
        return $this->where($name, '\w+');
    }




    /**
     * @param string $name
     * @return $this|Route
     */
    public function whereDigital(string $name): Route
    {
        return $this->where($name, '\d+');
    }




    /**
     * @param string $name
     * @return Route
     */
    public function whereAlphaNumeric(string $name): Route
    {
        return $this->where($name, '[^a-z_\-0-9]');
    }



    /**
     * @param string $name
     * @return Route
    */
    public function whereSlug(string $name): Route
    {
        return $this->where($name, '[a-z\-0-9]+');
    }




    /**
     * Determine parses
     *
     * @param $name
     * @param $regex
     * @return array
    */
    protected function parseWhere($name, $regex): array
    {
        return \is_array($name) ? $name : [$name => $regex];
    }



    /**
     * @param $regex
     * @return string|string[]
    */
    protected function resolveRegex($regex)
    {
        return str_replace('(', '(?:', $regex);
    }
}