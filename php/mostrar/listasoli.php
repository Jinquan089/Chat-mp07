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

// Consulta SQL para recuperar las solicitudes de amistad pendientes para el usuario actual.
$sql = "SELECT S.id_solicitud, U.username
        FROM tbl_listaSolicitud AS S
        INNER JOIN tbl_users AS U ON S.id_enviador = U.id_user
        WHERE S.id_receptor = (SELECT id_user FROM tbl_users WHERE username = ?) 
        AND S.status = 'pendiente'";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Utiliza un bucle foreach para recorrer los resultados
if (mysqli_num_rows($result) > 0) {
    foreach ($result as $row) {
        echo "Solicitud de amistad de: " . $row['username'];
        echo "       ";
        echo "<a href='./solicitudes/acceptar.php?id=" . $row['id_solicitud'] . "'>Aceptar</a>";
        echo "       ";
        echo "<a href='./solicitudes/rechazar.php?id=" . $row['id_solicitud'] . "'>Rechazar</a><br>";
    }
} else {
    echo "No tienes solicitudes";
}


// Cierra la sentencia preparada
mysqli_stmt_close($stmt);

// Cierra la conexión a la base de datos.
mysqli_close($conn);
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
</body>
</html>
<?php
echo "<br>";
echo "<a href='../mostrar.php'>Volver</a>";