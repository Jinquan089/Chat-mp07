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
    $stmt_check = $conn->prepare("SELECT id_solicitud, id_enviador FROM tbl_listaSolicitud WHERE id_solicitud = :request_id AND status = 'pendiente'");
    $stmt_check->bindParam(':request_id', $request_id);
    $stmt_check->execute();
    $resultcheck = $stmt_check->fetch();

    if ($resultcheck) {
        $enviador_id = $resultcheck['id_enviador'];

        // Actualiza el estado de la solicitud a 'aceptado'.
        $stmt_update = $conn->prepare("UPDATE tbl_listaSolicitud SET status = 'aceptado' WHERE id_solicitud = :request_id");
        $stmt_update->bindParam(':request_id', $request_id);
        $stmt_update->execute();

        // Agrega una entrada en la tabla tbl_listaAmistad para establecer la relación de amistad.
        $stmt_insert_amistad = $conn->prepare("INSERT INTO tbl_listaAmistad (id_user1, id_user2, status) VALUES (:enviador_id, :user_id, 'aceptado')");
        $stmt_insert_amistad->bindParam(':enviador_id', $enviador_id);
        $stmt_insert_amistad->bindParam(':user_id', $user_id);
        $stmt_insert_amistad->execute();

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
$conn = null;
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
