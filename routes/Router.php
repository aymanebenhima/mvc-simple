<?php

namespace Router;

class Router {
    public $url;

    public function __construct($url) {
        $this->url = $url;
    }

    public function show() {
        echo $this->url;
    }
}