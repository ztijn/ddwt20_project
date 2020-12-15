<?php

/* Include model.php */
include 'model.php';


/**
 * Creates a connection with the database
 * @param $host string type of host used
 * @param $db string database name
 * @param $user string username of the databse
 * @param $pass string password of the database
 * @return PDO
 */
function connect_db($host, $db, $user, $pass){
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        echo sprintf("Failed to connect. %s", $e->getMessage());
    }
    return $pdo;

}

