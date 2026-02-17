<?php

$config = require 'config.php';
require 'core/Database.php';
$db = new Database(
    $config['database'],
    $config['database']['username'],
    $config['database']['password']
);
