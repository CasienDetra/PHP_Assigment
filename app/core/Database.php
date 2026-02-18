<?php

class Database
{
    public $connection;

    public $statement;

    public function __construct($config, $username = 'root', $password = '')
    {
        // Setup variables with fallbacks
        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? '3306';
        $db_name = $config['db_name'] ?? '';

        $this->connection = new mysqli($host, $username, $password, $db_name, $port);

        if ($this->connection->connect_error) {
            exit('Database Connection Failed: '.$this->connection->connect_error);
        }
        /* echo ' success boy'; */
        $charset = $config['charset'] ?? 'utf8mb4';
        $this->connection->set_charset($charset);
    }

    /**
     * A helper method to run queries safely using MySQLi
     */
    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);

        if ($params) {
            // treat s as string for simplicity
            $types = str_repeat('s', count($params));
            $this->statement->bind_param($types, ...$params);
        }

        $this->statement->execute();

        return $this;
    }

    public function get()
    {
        $result = $this->statement->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function find()
    {
        $result = $this->statement->get_result();

        return $result->fetch_assoc();
    }
}
