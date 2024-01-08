<?php

namespace App\Middleware;

trait RedirectIfAdmin {

    protected function isAdmin()
    {
        if (isset($_SESSION['auth']) && $_SESSION['auth'] === 1) {
            return true;
        } else
            return header('Location: /login');
    }
}