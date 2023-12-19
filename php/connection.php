<?php
    $servidor = "mysql:dbname=bd_chat;host=localhost";
    $user = "zorrito";
    $pass = "QWEqwe123";
    try {
        $conn = new PDO($servidor, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    } catch (PDOException $e) {
        echo "conexion fallida" .$e->getMessage();
    }

?>