<?php
// Conexion a la base de datos
include_once("../connection.php");
session_start();

// Obtener el ID del usuario que envía la solicitud

$user_id = $_SESSION['id_user'];
$id_user_destino = $_POST['id_user_destino'];




// Verificar si ya existe una solicitud pendiente entre los dos usuarios
$stmt_verificar = $conn->prepare("SELECT * FROM tbl_listaSolicitud WHERE (id_enviador = :user_id AND id_receptor = :id_user_destino) OR (id_enviador = :id_user_destino AND id_receptor = :user_id)");
$stmt_verificar->bindParam(':user_id', $user_id);
$stmt_verificar->bindParam(':id_user_destino', $id_user_destino);
$stmt_verificar->execute();
$result_verificar = $stmt_verificar->fetchAll();
if (count($result_verificar) == 0) {
    // Preparar la consulta SQL para insertar la solicitud en tbl_listaSolicitud
    $stmt_insert = $conn->prepare("INSERT INTO tbl_listaSolicitud (id_enviador, id_receptor, status) VALUES (:user_id, :id_user_destino, 'pendiente')");
    $stmt_insert->bindParam(':user_id', $user_id);
    $stmt_insert->bindParam(':id_user_destino', $id_user_destino);
    $stmt_insert->execute();
}
?>
