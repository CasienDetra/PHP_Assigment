<?php

$config = require __DIR__.'/config.php';

$dbConfig = $config['database'];

$mysqli = new mysqli(
    $dbConfig['host'],
    $dbConfig['username'],
    $dbConfig['password'],
    '',
    $dbConfig['port']
);

if ($mysqli->connect_error) {
    exit('Connect Error ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
}

$sql = file_get_contents(__DIR__.'/database/database.sql');

// Execute multi query
if ($mysqli->multi_query($sql)) {
    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->next_result());
    echo "Database initialized successfully.\n";
} else {
    echo 'Error initializing database: '.$mysqli->error."\n";
}

$mysqli->close();
