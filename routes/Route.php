<?php

namespace Router;

class Route {
    public $path;
    public $action;
    public $matches;

    public function __construct(string $path, string $action) {
        $this->path = trim($path, '/');
        $this->action = $action;
    }

    public function matches(string $url) {
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $pathToMatch = "#^$path$#";

        if(!preg_match($pathToMatch, $url, $matches)) {
            $this->matches = $matches;
            return true;
        }
        else {
            return false;
        }
    }

    public function execute() {
        $params = explode('@', $this->action);
        $controller = new $params[0]();
        $method = $params[1];

        return isset($this->matches[1]) ? $controller->$method($this->matches[1]) : $controller->$method();
    }
}