<?php
// Inicia la sesión.
session_start();

// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}

// Conexión a la base de datos.
include("../connection.php");

$user = $_SESSION['user'];

try {
    if (!empty($_POST['busqueda'])) {
        $userbuscar = '%'.$_POST['busqueda'].'%';
        $stmtAmigos = $conn->prepare("SELECT DISTINCT U.id_user, U.username FROM tbl_listaSolicitud AS LA 
        INNER JOIN tbl_users AS U ON (LA.id_enviador = U.id_user OR LA.id_receptor = U.id_user) 
        WHERE (LA.id_enviador = (SELECT id_user FROM tbl_users WHERE username = :user) 
        OR LA.id_receptor = (SELECT id_user FROM tbl_users WHERE username = :user)) 
        AND LA.status = 'aceptado'
        AND U.username LIKE :userbuscar");
        $stmtAmigos->bindParam(':user', $user);
        $stmtAmigos->bindParam(':userbuscar', $userbuscar);
        $stmtAmigos->execute();
    } else {
        $stmtAmigos = $conn->prepare("SELECT DISTINCT U.id_user, U.username FROM tbl_listaSolicitud AS LA 
        INNER JOIN tbl_users AS U ON (LA.id_enviador = U.id_user OR LA.id_receptor = U.id_user) 
        WHERE (LA.id_enviador = (SELECT id_user FROM tbl_users WHERE username = :user) 
        OR LA.id_receptor = (SELECT id_user FROM tbl_users WHERE username = :user)) 
        AND LA.status = 'aceptado'");
        $stmtAmigos->bindParam(':user', $user);
        $stmtAmigos->execute();
    }
    $resultAmigos = $stmtAmigos->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($resultAmigos);

} catch (PDOException $e) {
    echo json_encode(['error' => "Error: " . $e->getMessage()]);
}

?>
