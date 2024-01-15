<?php
// Inicia la sesión.
session_start();
include("../connection.php");

// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_SESSION['user'])) {
    header("Location: ../../../login.php");
    exit;
}
    $request_id = $_POST['id'];
    $accion = $_POST['accion'];
    if ($accion == "aceptar") {
        $stmt_update = $conn->prepare("UPDATE tbl_listaSolicitud SET status = 'aceptado' WHERE id_solicitud = :request_id");
        $stmt_update->bindParam(':request_id', $request_id);
        $stmt_update->execute();
    } elseif ($accion == "rechazar") {
        $stmt_delete = $conn->prepare("DELETE FROM tbl_listaSolicitud WHERE id_solicitud = :request_id");
        $stmt_delete->bindParam(':request_id', $request_id);
        $stmt_delete->execute();
    }



