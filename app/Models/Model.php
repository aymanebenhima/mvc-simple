<?php

namespace App\Models;

use PDO;
use Database\Connection;

abstract class Model {

    protected $db;
    protected $table;

    /**
     * Constructor for the class.
     *
     * @param Connection $db The database connection object.
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Retrieves all records from the table.
     *
     * @return array Returns an array of all records.
     */
    public function all(): array
    {
        return $this->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
    }

    /**
     * Finds a record in the database by its ID.
     *
     * @param int $id The ID of the record to find.
     * @throws Some_Exception_Class If the query fails.
     * @return Model The found record.
     */
    public function findById(int $id): Model
    {
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id], true);
    }

    /**
     * Creates a new record in the database table.
     *
     * @param array $data The data for the new record.
     * @param array|null $relations Optional array of related data.
     * @throws Some_Exception_Class Description of the exception that may be thrown.
     * @return mixed The result of the database query.
     */
    public function create(array $data, ?array $relations = null)
    {
        $firstParenthesis = "";
        $secondParenthesis = "";
        $i = 1;

        foreach ($data as $key => $value) {
            $comma = $i === count($data) ? "" : ", ";
            $firstParenthesis .= "{$key}{$comma}";
            $secondParenthesis .= ":{$key}{$comma}";
            $i++;
        }

        return $this->query("INSERT INTO {$this->table} ($firstParenthesis)
        VALUES($secondParenthesis)", $data);
    }

    /**
     * Updates a record in the database.
     *
     * @param int $id The ID of the record to be updated.
     * @param array $data An array containing the data to be updated.
     * @param array|null $relations (optional) An array of related data to be updated.
     * @throws Some_Exception_Class An exception that may be thrown during the update process.
     * @return mixed The result of the database query.
     */
    public function update(int $id, array $data, ?array $relations = null)
    {
        $sqlRequestPart = "";
        $i = 1;

        foreach ($data as $key => $value) {
            $comma = $i === count($data) ? "" : ', ';
            $sqlRequestPart .= "{$key} = :{$key}{$comma}";
            $i++;
        }

        $data['id'] = $id;

        return $this->query("UPDATE {$this->table} SET {$sqlRequestPart} WHERE id = :id", $data);
    }

    /**
     * Deletes a record from the database table.
     *
     * @param int $id The ID of the record to delete.
     * @throws Some_Exception_Class If the deletion fails.
     * @return bool Returns true if the record is successfully deleted, false otherwise.
     */
    public function destroy(int $id): bool
    {
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }

    /**
     * Executes a SQL query on the database.
     *
     * @param string $sql The SQL query to execute.
     * @param array|null $param An optional array of parameters to bind to the query.
     * @param bool|null $single An optional flag indicating whether to return a single row or all rows.
     * @throws PDOException If an error occurs while executing the query.
     * @return mixed The result of the query execution.
     */
    public function query(string $sql, array $param = null, bool $single = null)
    {
        $method = is_null($param) ? 'query' : 'prepare';

        if (
            strpos($sql, 'DELETE') === 0
            || strpos($sql, 'UPDATE') === 0
            || strpos($sql, 'INSERT') === 0) {

            $stmt = $this->db->getPDO()->$method($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this), [$this->db]);
            return $stmt->execute($param);
        }

        $fetch = is_null($single) ? 'fetchAll' : 'fetch';

        $stmt = $this->db->getPDO()->$method($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this), [$this->db]);

        if ($method === 'query') {
            return $stmt->$fetch();
        } else {
            $stmt->execute($param);
            return $stmt->$fetch();
        }
    }
}