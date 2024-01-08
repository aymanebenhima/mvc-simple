<?php

namespace App\Controllers;

use App\Middleware\RedirectIfAdmin;
use Database\Connection;

abstract class Controller {

    use RedirectIfAdmin;

    protected $db;

    /**
     * Constructs a new instance of the class.
     *
     * @param Connection $db The database connection.
     */
    public function __construct(Connection $db)
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = $db;
    }
    
    /**
     * Renders a view by including the specified PHP file and passing optional parameters.
     *
     * @param string $path The path to the PHP file to be included.
     * @param array|null $params Optional parameters to be passed to the included PHP file.
     * @throws Some_Exception_Class An exception that may be thrown during the rendering process.
     * @return void
     */
    protected function view(string $path, array $params = null) {
        ob_start();
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);
        require VIEWS . $path . '.php';
        $content = ob_get_clean();
        require VIEWS . 'layout.php';
    }

    /**
     * Retrieve the database object.
     *
     * @return mixed The database object.
     */
    protected function getDb() {
        return $this->db;
    }

}