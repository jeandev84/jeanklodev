<?php
namespace Jan\Component\Routing;


use Jan\Component\Routing\Contract\RouteMatchedInterface;
use Jan\Component\Routing\Exception\RouteException;



/**
 * @see Route
 *
 * @package Jan\Component\Routing
*/
class Route implements RouteMatchedInterface, \ArrayAccess
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
    protected $params = [];



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
    public function getParams(): array
    {
        return $this->params;
    }




    /**
     * get matches params
     *
     * @return array
    */
    public function getMatches(): array
    {
        return $this->matches;
    }




    /**
     * @param $key
     * @param null $default
     * @return mixed|null
    */
    public function getOption($key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }



    /**
     * @return array
    */
    public function getOptions(): array
    {
        return $this->options;
    }




    /**
     * Add options
     *
     * @param array $options
     * @return Route
    */
    public function addOptions(array $options): Route
    {
        $this->options = array_merge($this->options, $options);

        return $this;
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
            $this->params[$name] =  $this->makePattern($name, $regex);
        }

        return $this;
    }




    /**
     * @param string $name
     * @return $this
     */
    public function whereNumber(string $name): Route
    {
        return $this->where($name, '[0-9]+'); // (\d+)
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
     * @param string $name
     * @return Route
    */
    public function anything(string $name): Route
    {
        return $this->where($name, '.*');
    }



    /**
     * @param string|null $requestMethod
     * @return bool
    */
    public function matchMethods(?string $requestMethod): bool
    {
        if (\in_array($requestMethod, $this->methods)) {
            $this->addOptions(compact('requestMethod'));
            return true;
        }

        return false;
    }




    /**
     * Determine if the current method and path URL match route
     *
     * @param string $requestMethod
     * @param string $requestUri
     * @return bool
     */
    public function match(string $requestMethod, string $requestUri): bool
    {
        return $this->matchMethods($requestMethod) && $this->matchPath($requestUri);
    }





    /**
     * @param string $path
     * @return false
    */
    public function matchPath(string $path): bool
    {
         if (preg_match($pattern = $this->generatePattern(), $this->resolveURL($path), $matches)) {

             $this->matches($this->filterMatchedParams($matches));

             $this->addOptions(compact('pattern', 'path'));

             return true;
         }

         return false;
    }


    /**
     * @return string
    */
    public function generatePattern(): string
    {
          $pattern = $this->removeTrailingSlashes($this->path);

          if ($this->params) {
             $pattern = $this->replacePlaceholders($pattern, $this->params);
          }

          return '#^'. $pattern . '$#i';
    }



    /**
     * @param array $matches
     * @return array
    */
    protected function filterMatchedParams(array $matches): array
    {
        return array_filter($matches, function ($key) {

            return ! is_numeric($key);

        }, ARRAY_FILTER_USE_KEY);
    }




    /**
     * get path of given URL
     *
     * @param string|null $path
     * @return string
    */
    public function resolveURL(?string $path): string
    {
        if(stripos($path, '?') !== false) {
            $path = explode('?', $path, 2)[0];
        }

        return $this->removeTrailingSlashes($path);
    }



    /**
     * @param string $path
     * @param array $params
     * @return string
    */
    protected function replacePlaceholders(string $path, array $params): string
    {
        foreach ($params as $k => $v) {
            $path = preg_replace(["#{{$k}}#", "#{{$k}.?}#"], [$v, '?'. $v .'?'], $path);
        }

        return $path;
    }



    /**
     * @param string $path
     * @return string
    */
    protected function removeTrailingSlashes(string $path): string
    {
        return trim($path, '\\/');
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


    /**
     * @param $name
     * @param $regex
     * @return string
    */
    protected function makePattern($name, $regex): string
    {
        return '(?P<'. $name .'>'. $this->resolveRegex($regex) . ')';
    }



    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }


    /**
     * @param mixed $offset
     * @return mixed|void
     */
    public function offsetGet($offset)
    {
        if(property_exists($this, $offset)) {
            return $this->{$offset};
        }

        return null;
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if(property_exists($this, $offset)) {
            $this->{$offset} = $value;
        }
    }



    /**
     * @param mixed $offset
    */
    public function offsetUnset($offset)
    {
        if(property_exists($this, $offset)) {
            unset($this->{$offset});
        }
    }
}