<?php
// Inicia la sesión.
session_start();

// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}

// Conexión a la base de datos (debes configurar esto según tu entorno).
include("../connection.php");

$user = $_SESSION['user'];

try {
    // Consulta SQL para recuperar las solicitudes de amistad pendientes para el usuario actual.
    $stmt = $conn->prepare("SELECT S.id_solicitud, U.username FROM tbl_listaSolicitud AS S INNER JOIN tbl_users AS U ON S.id_enviador = U.id_user WHERE S.id_receptor = (SELECT id_user FROM tbl_users WHERE username = :user) AND S.status = 'pendiente'");
    $stmt->bindParam(':user', $user);
    $stmt->execute();
    $result = $stmt->fetchAll();

    // Utiliza un bucle foreach para recorrer los resultados
    if (count($result) > 0) {
        foreach ($result as $row) {
            echo "<p>Solicitud de amistad de: " . $row['username'] . "</p>";
            echo "<a href='./solicitudes/acceptar.php?id=" . $row['id_solicitud'] . "'>Aceptar</a>";
            echo "       ";
            echo "<a href='./solicitudes/rechazar.php?id=" . $row['id_solicitud'] . "'>Rechazar</a><br>";
        }
    } else {
        echo "<p>No tienes solicitudes</p>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Cierra la conexión a la base de datos.
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/solicitud.css">
    <title>Lista de solicitudes</title>
</head>
<body>
    <br>
    <a href='../mostrar.php'>Volver</a>
</body>
</html>
