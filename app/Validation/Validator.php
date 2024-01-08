<?php

namespace App\Validation;
use DateTime;

class Validator {

    private $data;
    private $errors;
    private $db;

    /**
     * Constructor for the class.
     *
     * @param array $data the initial data for the object
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validates an array of rules.
     *
     * @param array $rules The array of rules to validate.
     * @return array|null Returns an array of errors or null if there are no errors.
     */
    public function validate(array $rules): ?array
    {
        foreach ($rules as $field => $rule) {
            if(array_key_exists($field, $this->data)) {
                foreach($rule as $ruleName) {
                    switch($ruleName) {
                        case 'required':
                            $this->required($field, $this->data[$field]);
                            break;
                        case substr($ruleName, 0, 3) ==='min':
                            $this->min($field, $this->data[$field], $ruleName);
                            break;
                        case substr($ruleName, 0, 3) ==='max':
                            $this->max($field, $this->data[$field], $ruleName);
                            break;
                        case 'email':
                            $this->email($field, $this->data[$field]);
                            break;
                        case 'numeric':
                            $this->numeric($field, $this->data[$field]);
                            break;
                        case 'unique':
                            $this->unique($field, $this->data[$field], $rule['table'], $rule['column']);
                            break;
                        case 'date':
                            $this->date($field, $this->data[$field]);
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        return $this->getErrors();
    }

    /**
     * Validates if a field is required.
     *
     * @param string $field The name of the field being validated.
     * @param string $value The value of the field being validated.
     */
    private function required(string $field, string $value)
    {
        $value = trim($value);

        if(!isset($value) || empty($value) || is_null($value)) {
            $this->errors[$field][] = $field . ' is required';
        }
    }

    /**
     * Validates if a field contains a valid email address.
     *
     * @param string $field The name of the field being validated.
     * @param string $value The value of the field being validated.
     * @return void
     */
    private function email(string $field, string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = $field . ' must be a valid email address';
        }
    }
    /**
     * Validates if a field contains a numeric value.
     *
     * @param string $field The name of the field being validated.
     * @param string $value The value of the field being validated.
     * @return void
     */
    private function numeric(string $field, string $value)
    {
        if (!is_numeric($value)) {
            $this->errors[$field][] = $field . ' must be a numeric value';
        }
    }

    /**
     * Validates if a value is unique in a given database table and column.
     *
     * @param string $field The name of the field being validated.
     * @param string $value The value of the field being validated.
     * @param string $table The name of the database table.
     * @param string $column The name of the column in the database table.
     * @return void
     */
    private function unique(string $field, string $value, string $table, string $column)
    {
        // Perform the necessary database query to check for uniqueness
        // and add an error message if the value is not unique
        $query = "SELECT COUNT(*) FROM {$table} WHERE {$column} = :value";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // Add an error message if the value is not unique
        if ($count > 0) {
            $this->errors[$field][] = $field . ' must be unique';
        }
    }

    /**
     * Validates if a field contains a valid date.
     *
     * @param string $field The name of the field being validated.
     * @param string $value The value of the field being validated.
     * @return void
     */
    private function date(string $field, string $value)
    {
        $date = DateTime::createFromFormat('Y-m-d', $value);
        if ($date === false || array_sum($date->getLastErrors()) > 0) {
            $this->errors[$field][] = $field . ' must be a valid date';
        }
    }
    
    /**
     * Validates if the length of a given value is greater than or equal to a specified limit.
     *
     * @param string $field The name of the field being validated.
     * @param string $value The value of the field being validated.
     * @param string $rule The rule specifying the minimum length requirement.
     * @throws \Exception If the length of the value is less than the specified limit.
     * @return void
     */
    private function min(string $field, string $value, string $rule)
    {
        preg_match_all('/(\d+)/', $rule, $matches);
        $limit = (int) $matches[0][0];

        if(strlen($value) < $limit) {
            $this->errors[$field][] = $field . ' must be at least ' . $limit . ' characters';
        }
    }

    /**
     * Validates if the length of a string is less than or equal to a specified limit.
     *
     * @param string $field The name of the field being validated.
     * @param string $value The value to be validated.
     * @param string $rule The rule to determine the maximum limit.
     * @throws Exception If the value exceeds the specified limit.
     * @return void
     */
    private function max(string $field, string $value, string $rule)
    {
        preg_match_all('/(\d+)/', $rule, $matches);
        $limit = (int) $matches[0][0];

        if(strlen($value) > $limit) {
            $this->errors[$field][] = $field . ' must be at most ' . $limit . ' characters';
        }
    }

    /**
     * Retrieves the errors, if any, from the class.
     *
     * @return array|null The array of errors, if any, or null otherwise.
     */
    private function getErrors(): ?array
    {
        if(!empty($this->errors)) {
            return $this->errors;
        }
        return null;
    }
}