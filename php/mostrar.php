<?php
// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ./login.php");
    exit;
}
include("./connection.php");
$user_id = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/styles2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
    </div>
    <form id="formBuscarUsuarios">
        <label for="search_user">Buscar por Username o Nombre Real:</label> <br>
        <input type="text" id="search_user" name="search_user" required>
        <input type="submit" value="Buscar" id="soli_buscar">
    </form>
    <button id="listasolicitud">Lista Solicitudes</button>
    <div id="listasolicitudes"></div>
    <!-- Contenedor para mostrar los resultados de búsqueda -->
    <div id="mostrarUsuarios"></div>
    <form>
        <div id="usuariosbuscados"></div>
    </form>
    <div id="lista-amigos">
        <div>
            <form action="" method="post" id="frmbusqueda">
                <div class="form-group">
                    <label for="buscar">Buscar: </label><br>
                    <input type="text" name="buscar" id="buscar" placeholder="Buscar..." class="form-control">
                </div>
            </form>
        </div>
        <div>
            <table class="table table-hover table-responsive">
                <thead>
                    <tr>
                        <th>Amigos</th>
                    </tr>
                </thead>
                <tbody id="resultado">
                </tbody>
            </table>
        </div>
    </div>
    <div>
        <h2 style="text-align: center;" id="personachat"></h2>
        <div id="chat">
            <div id="mensajes">
                <div class='mensaje' id="estilo">
                    <p id="mensajesuser"></p>
                    <p id="mensajetiempo"></p>
                </div>
            </div>
            <form id="formulario-mensaje">
            </form>
            <br>
        </div>
    </div>
<body>
</body>
</html>

<script src="../ajax/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
