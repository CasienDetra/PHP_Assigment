<?php

class Database
{
    public $connection;

    public $statement;

    public function __construct($config, $username = 'root', $password = '')
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['db_name']};charset={$config['charset']}";

        try {
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            exit('Database Connection Failed: '.$e->getMessage());
        }
    }

    /**
     * A helper method to run queries safely
     */
    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);

        return $this;
    }

    /* get helper */
    public function get()
    {
        $this->statement->fetchAll();
    }

    public function find()
    {

        $this->statement->fetch();
    }
}
