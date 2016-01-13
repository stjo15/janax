<?php

// Settings for private server

define('DB_USER', 'myusername'); // The database username
define('DB_PASSWORD', 'mypassword'); // The database password

return [
    // Set up details on how to connect to the database
    'dsn'     => "mysql:host=localhost;dbname=mydbname;",
    'username'        => DB_USER,
    'password'        => DB_PASSWORD,
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'    => "janax_", // Prefix according to your application

    // Display details on what happens
    'verbose' => false,

    // Throw a more verbose exception when failing to connect
    'debug_connect' => 'false',
];
