<?php
// Inicia la sesión.
session_start();
include("../connection.php");

// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}

if ($_SESSION['user'] == "admin1") {
    try {
        $sqladmin = $conn->prepare("SELECT * FROM tbl_users WHERE NOT username = :user");
        $sqladmin->bindParam(':user', $_SESSION["user"]);
        $sqladmin->execute();
        $result = $sqladmin->fetchAll();

        foreach ($result as $rowadmin) {
            echo $rowadmin["username"] . "<br>";
            ?>
            <form action="modificar.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $rowadmin["id_user"] ?>">
                <input type="hidden" name="username" value="<?php echo $rowadmin["username"] ?>">
                <input type="hidden" name="nom_real" value="<?php echo $rowadmin["nom_real"] ?>">
                <input type="hidden" name="pwd" value="<?php echo $rowadmin["pwd"] ?>">
                <button type="submit">Modificar</button>
            </form>
            <form action="proc_eliminar.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $rowadmin["id_user"] ?>">
                <button type="submit" name="eliminar" value="eliminar">Eliminar</button>
            </form>
            <?php
        }

        if (isset($_GET['exist'])) {
            echo "Nombre de usuario ya existe";
        }
        ?>
        <br>
        <a href="../mostrar.php">Volver</a>
    <?php
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

} else {
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
        $search_user = htmlspecialchars($_POST['search_user'], ENT_QUOTES, 'UTF-8');

        try {
            // Prepara la consulta SQL con una sentencia preparada
            $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE username LIKE :search_user OR nom_real LIKE :search_user");

            // Vincula los parámetros y ejecuta la consulta
            $param = "%" . $search_user . "%";
            $stmt->bindParam(':search_user', $param);
            $stmt->execute();
            $result = $stmt->fetchAll();

            if (count($result) > 0) {
                foreach ($result as $row) {
                    if ($row["id_user"] != $_SESSION['id_user']) { // Verifica que el usuario no sea el mismo que el que ha iniciado sesión
                        echo "<br> - Username: " . $row["username"] . " - Nombre Real: " . $row["nom_real"] . "<br>";
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

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif (isset($_GET["enviado"])) {
        ?>
        <span>Solicitud de amistad enviada correctamente.</span>
        <?php
    } elseif (isset($_GET["exist"]) && $_GET["exist"] == 2) {
        ?>
        <span>No se encontraron usuarios.</span>
        <?php
    } elseif (isset($_GET["soliexist"])) {
        ?>
        <span>Ya existe una solicitud pendiente entre estos usuarios.</span>
        <?php
    } elseif (isset($_GET["exist"]) && $_GET["exist"] == 1) {
        ?>
        <span>No puedes buscarte a ti mismo.</span>
        <?php
    }

    ?>
    <br>
    <a href="../mostrar.php">Volver</a>
    </body>
    </html>
    <?php
}
?>
