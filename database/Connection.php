<?php

namespace Database;

use PDO;

class Connection {

    private $dbname;
    private $host;
    private $username;
    private $password;
    private $pdo;
    private static $instance;

    /**
     * Constructor for the class.
     *
     * @param string $dbname the name of the database
     * @param string $host the host of the database
     * @param string $username the username for the database
     * @param string $password the password for the database
     */
    public function __construct(string $dbname, string $host, string $username, string $password) {
        $this->dbname = $dbname;
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Retrieves the singleton instance of the Connection class.
     *
     * @return Connection The Connection instance.
     */
    public static function getInstance(string $dbname, string $host, string $username, string $password): Connection {
        if (self::$instance === null) {
            // Create a new instance if it doesn't exist
            self::$instance = new self($dbname, $host, $username, $password);
        }
        return self::$instance;
    }

    /**
     * Retrieves the PDO object for the database connection.
     *
     * @return PDO The PDO object.
     */
    public function getPDO(): PDO {
        return $this->pdo ?? $this->pdo = new PDO("mysql:dbname={$this->dbname};host={$this->host}", $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
    }
}