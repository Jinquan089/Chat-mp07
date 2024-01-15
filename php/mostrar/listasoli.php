<?php
// Inicia la sesion.
session_start();

// Verifica si el usuario ha iniciado sesion, de lo contrario, redirige al inicio de sesion.
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}

// Conexión a la base de datos (debes configurar esto segun tu entorno).
include("../connection.php");

$user = $_SESSION['user'];

    // Consulta SQL para recuperar las solicitudes de amistad pendientes para el usuario actual.
    $stmt = $conn->prepare("SELECT S.id_solicitud, U.username FROM tbl_listaSolicitud AS S INNER JOIN tbl_users AS U ON S.id_enviador = U.id_user 
                            WHERE S.id_receptor = (SELECT id_user FROM tbl_users WHERE username = :user) AND S.status = 'pendiente'");
    $stmt->bindParam(':user', $user);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        $resultado = "";
        foreach ($result as $row) {
            $resultado .= "<p>Solicitud de amistad de: " . $row['username'] . "</p>";
            $resultado .= "<button name=aceptar id=" . $row['id_solicitud'] . ">Aceptar</button>   ";
            $resultado .= "<button name=rechazar id=" . $row['id_solicitud'] . ">Rechazar</button>";
        }
    } else {
        $resultado = "<p>No tienes solicitudes</p>";
    }
    echo json_encode($resultado);
?>