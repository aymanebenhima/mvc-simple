<?php

namespace Router;

use Database\Connection;

class Route {

    public $path;
    public $action;
    public $matches;

    /**
     * Constructs a new instance of the class.
     *
     * @param string $path the path parameter
     * @param mixed $action the action parameter
     */
    public function __construct($path, $action)
    {
        $this->path = trim($path, '/');
        $this->action = $action;
    }

    /**
     * Matches the given URL against the path of the current object.
     *
     * @param string $url The URL to match against.
     * @return bool true if the URL matches the path, false otherwise.
     */
    public function matches(string $url)
    {
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $pathToMatch = "#^$path$#";

        if (preg_match($pathToMatch, $url, $matches)) {
            $this->matches = $matches;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Executes the PHP function.
     *
     * @return mixed The result of executing the function.
     */
    public function execute()
    {
        $params = explode('@', $this->action);
        $controller = new $params[0](Connection::getInstance(getenv('DB_DATABASE'), getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')));
        $method = $params[1];

        return isset($this->matches[1]) ? $controller->$method($this->matches[1]) : $controller->$method();
    }
}