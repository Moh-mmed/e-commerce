<?php

// initialize the dsn (data source name):
$dsn = "mysql:host=localhost;dbname=shop"; // u can add charset=utf8mb4
$user = 'root';
$pass = 'proudly2648';
$options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']; // in case an using arabic language



// Connect to database 
try {
    $db = new PDO($dsn, $user, $pass, $options);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error! ' . $e->getMessage();
}
