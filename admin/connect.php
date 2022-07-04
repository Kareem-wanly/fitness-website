<?php

$dsn = 'mysql:host=localhost;dbname=sport2';
$name = 'root';
$pass = '';
$option = array( // if there's an arabic word in database for no problems views
    PDO::MYSQL_ATTR_INIT_COMMAND  => 'SET NAMES utf8'
);

try {
    $conn = new PDO($dsn, $name, $pass, $option);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'failed to connect because: '  . $e->getMessage();
}
