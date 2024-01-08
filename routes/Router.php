<?php

namespace Router;

use App\Exceptions\NotFoundException;

class Router {

    public $url;
    public $routes = [];

    /**
     * Constructor for the class.
     *
     * @param string $url The URL to be trimmed.
     */
    public function __construct($url)
    {
        $this->url = trim($url, '/');
    }

    /**
     * Adds a new GET route to the routes array.
     *
     * @param string $path The path of the route.
     * @param string $action The action to be performed for the route.
     * @throws Some_Exception_Class If something goes wrong.
     * @return void
     */
    public function get(string $path, string $action)
    {
        $this->routes['GET'][] = new Route($path, $action);
    }

    /**
     * A description of the post PHP function.
     *
     * @param string $path The path for the route.
     * @param string $action The action for the route.
     * @throws Some_Exception_Class description of exception
     * @return void
     */
    public function post(string $path, string $action)
    {
        $this->routes['POST'][] = new Route($path, $action);
    }

    /**
     * Executes the run function.
     *
     * @throws NotFoundException if the requested route doesn't exist
     * @return Some_Return_Value the result of executing the route
     */
    public function run()
    {
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->matches($this->url)) {
                return $route->execute();
            }
        }

        throw new NotFoundException("The {$this->url} route doesn't exist");
    }
}