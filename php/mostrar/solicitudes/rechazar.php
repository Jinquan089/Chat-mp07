<?php
// Inicia la sesión.
session_start();
include("../../connection.php");

// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_SESSION['user'])) {
    header("Location: ../../../login.php");
    exit;
}

// Obtén el ID de la solicitud de amistad que se va a rechazar desde la URL.
$username = $_SESSION['user'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $request_id = $_GET['id'];

    // Realiza una consulta para verificar que la solicitud existe y está pendiente.
    $stmt_check = $conn->prepare("SELECT id_solicitud FROM tbl_listaSolicitud WHERE id_solicitud = :request_id AND status = 'pendiente'");
    $stmt_check->bindParam(':request_id', $request_id);
    $stmt_check->execute();
    $result = $stmt_check->fetch();

    if ($result) {
        // Borra la solicitud de amistad.
        $stmt_delete = $conn->prepare("DELETE FROM tbl_listaSolicitud WHERE id_solicitud = :request_id");
        $stmt_delete->bindParam(':request_id', $request_id);
        $stmt_delete->execute();

        // Puedes mostrar un mensaje al usuario para informarle que la solicitud se ha eliminado.
        echo "Solicitud de amistad eliminada.";
        echo "<br>";
        echo "<a href='../listasoli.php'>Volver</a>";

        // Redirige a una página o realiza otras acciones necesarias después de eliminar la solicitud.
        // header("Location: friend_requests.php");
    } else {
        echo "La solicitud de amistad no existe o ya ha sido procesada.";
        echo "<br>";
        echo "<a href='../listasoli.php'>Volver</a>";
    }
} else {
    header("../listasoli.php");
}

// Cierra la conexión a la base de datos.
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/solicitud.css">
    <title>Rechazar solicitud</title>
</head>
<body>
</body>
</html>
