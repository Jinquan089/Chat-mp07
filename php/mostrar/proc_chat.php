<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}

include("../connection.php");
$id_enviador = $_SESSION['id_user'];
$id_receptor = $_POST['id_user'];
$texto_mensaje = $_POST['mensaje'];

$stmt = $conn->prepare("INSERT INTO tbl_mensaje (id_enviador, id_receptor, texto_mensaje, timestamp) VALUES (:id_enviador, :id_receptor, :texto_mensaje, NOW())");
$stmt->bindParam(':id_enviador', $id_enviador);
$stmt->bindParam(':id_receptor', $id_receptor);
$stmt->bindParam(':texto_mensaje', $texto_mensaje);
$stmt->execute();