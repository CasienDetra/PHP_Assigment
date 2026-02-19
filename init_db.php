<?php

$config = require __DIR__ . '/app/config.php';

// Connect without DB name first to create it if needed
$mysqli = new mysqli(
    $config['database']['host'],
    $config['database']['username'],
    $config['database']['password'],
    '',
    $config['database']['port']
);

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$sql = file_get_contents(__DIR__ . '/app/database/database.sql');

// Execute multi query
if ($mysqli->multi_query($sql)) {
    do {
        /* store first result set */
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->next_result());
    echo "Database initialized successfully.\n";
} else {
    echo "Error initializing database: " . $mysqli->error . "\n";
}

$mysqli->close();
