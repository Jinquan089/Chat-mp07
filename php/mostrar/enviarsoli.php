<?php
// Inicia la sesión.
session_start();
include("../connection.php");
// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar usuarios</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<h1>Buscar Usuarios</h1>
<form action="./enviarsoli.php?buscar=1" method="POST">
    <label for="search_term">Buscar por Username o Nombre Real:</label>
    <input type="text" id="search_user" name="search_user" required>
    <input type="submit" value="Buscar">
</form>
<?php

if (isset($_GET['buscar'])) {
    // Obtener el input del formulario
    $search_user = mysqli_real_escape_string($conn, $_POST['search_user']);
    // Prepara la consulta SQL con una sentencia preparada
    $sql = "SELECT * FROM tbl_users WHERE username LIKE ? OR nom_real LIKE ?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    // Vincula los parámetros y ejecuta la consulta
    $param = "%" . $search_user . "%";
    mysqli_stmt_bind_param($stmt, "ss", $param, $param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                if ($row["id_user"] != $_SESSION['id_user']) { // Verifica que el usuario no sea el mismo que el que ha iniciado sesión
                    echo "<br>" . " - Username: " . $row["username"] . " - Nombre Real: " . $row["nom_real"]."<br>";
                    // Agregar un boton para enviar solicitud de amistad
                    echo "<form action='proc_enviarsoli.php' method='POST'>";
                    echo "<input type='hidden' name='id_user_destino' value='" . $row["id_user"] . "'>";
                    echo "<input type='submit' value='Enviar Solicitud de Amistad'>";
                    echo "</form>";
                } else {
                    header("Location: ./enviarsoli.php?exist=1");
                }
            }
        } else {
            header("Location: ./enviarsoli.php?exist=2");
        }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} elseif (isset($_GET["enviado"])) {
    ?>
    <span>Solicitud de amistad enviada correctamente.</span>
    <?php
} elseif (isset($_GET["exist"]) == 2) {
    ?>
    <span>No se encontraron usuarios.</span>
    <?php
} elseif (isset($_GET["soliexist"])) {
    ?>
    <span>Ya existe una solicitud pendiente entre estos usuarios.</span>
    <?php

} elseif (isset($_GET["exist"]) == 1) {
    ?>
    <span>No puedes buscarte a ti mismo.</span>
    <?php
} else {
    ?>
    <span></span>
    <?php
}
?>
<br>
<a href="../mostrar.php">Volver</a>
</body>
</html>