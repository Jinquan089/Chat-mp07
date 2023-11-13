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
    $sql = "SELECT id_solicitud FROM tbl_listaSolicitud WHERE id_solicitud = ? AND status = 'pendiente'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $request_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Borra la solicitud de amistad.
        $sql_delete = "DELETE FROM tbl_listaSolicitud WHERE id_solicitud = ?";
        $stmt_delete = mysqli_prepare($conn, $sql_delete);
        mysqli_stmt_bind_param($stmt_delete, "i", $request_id);
        mysqli_stmt_execute($stmt_delete);

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
mysqli_close($conn);
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