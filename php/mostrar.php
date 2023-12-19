<?php
// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ./login.php");
    exit;
}

// Carga información del usuario desde la base de datos (esto puede variar según tu base de datos y estructura).
$user_id = $_SESSION['user'];
// Realiza una consulta SQL para obtener la información del usuario, por ejemplo, username, nombre, imagen de perfil, etc.

// Aquí puedes realizar la consulta a la base de datos y obtener los datos del usuario.

// HTML de la página de dashboard.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <title>Home</title>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Bienvenido, <?php echo $user_id; ?></h1>
        </header>
        <nav>
            <ul>
                <li><a href="./mostrar/enviarSoli.php">Buscar Usuarios</a></li>
                <li><a href="./mostrar/listasoli.php">Solicitudes de Amistad</a></li>
                <li><a href="./mostrar/listaamigo.php">Lista de Amigos</a></li>
                <li><a href="./mostrar/cerrar.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
        <!-- Otras secciones y contenido específico de tu aplicación -->
    </div>
</body>
</html>