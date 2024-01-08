<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NotFoundException extends Exception
{
    /**
     * Constructor for the class.
     *
     * @param string $message The error message.
     * @param int $code The error code.
     * @param Throwable|null $previous The previous exception.
     * @throws Throwable
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Renders a 404 error page.
     *
     * @throws None
     * @return None
     */
    public function error404()
    {
        http_response_code(404);
        require VIEWS . 'errors/404.php';
    }
}