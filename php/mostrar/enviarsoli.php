<?php
// Inicia la sesión.
session_start();
include("../connection.php");

// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}

    if (!empty($_POST['buscar'])) {
        // Obtener el input del formulario
            $search_user = $_POST['buscar'];
            $username = $_SESSION['user'];
            // Prepara la consulta SQL con una sentencia preparada
            $stmt = $conn->prepare("SELECT * FROM tbl_users us
            WHERE (username LIKE :search_user OR nom_real LIKE :search_user)
            AND username != :username");

            
            // Vincula los parámetros y ejecuta la consulta
            $param = "%" . $search_user . "%";
            $stmt->bindParam(':search_user', $param);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                echo json_encode($result);
            } else {
                echo json_encode("Sin resultado");
            }
    } 
