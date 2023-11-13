<?php
// Inicia la sesión.
session_start();
include("../../connection.php");
// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_SESSION['user'])) {
    header("Location: ../../../login.php");
    exit;
}

$username = $_SESSION['user'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $request_id = $_GET['id'];
    $user_id = $_SESSION['id_user'];
    // Realiza una consulta para verificar que la solicitud existe y está pendiente.
    $sql = "SELECT id_solicitud, id_enviador FROM tbl_listaSolicitud WHERE id_solicitud = ? AND status = 'pendiente'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $request_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $enviador_id = $row['id_enviador'];

        // Actualiza el estado de la solicitud a 'aceptado'.
        $sql_update = "UPDATE tbl_listaSolicitud SET status = 'aceptado' WHERE id_solicitud = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "i", $request_id);
        mysqli_stmt_execute($stmt_update);

        // Agrega una entrada en la tabla tbl_listaAmistad para establecer la relación de amistad.
        $sql_insert_amistad = "INSERT INTO tbl_listaAmistad (id_user1, id_user2, status) VALUES (?, ?, 'aceptado')";
        $stmt_insert_amistad = mysqli_prepare($conn, $sql_insert_amistad);
        mysqli_stmt_bind_param($stmt_insert_amistad, "ii", $enviador_id, $user_id);
        mysqli_stmt_execute($stmt_insert_amistad);

        // Puedes mostrar un mensaje al usuario para informarle que la solicitud se ha aceptado.
        echo "Solicitud de amistad aceptada con éxito.";
        echo "<br>";
        echo "<a href='../listasoli.php'>Volver</a>";

        // Redirige a una página o realiza otras acciones necesarias después de aceptar la solicitud.
        // header("Location: friend_requests.php");
    } else {
        echo "La solicitud de amistad no existe o ya ha sido aceptada.";
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
    <title>Aceptar solicitud</title>
</head>
<body>
</body>
</html>