<?php
session_start();

// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}

include("../connection.php");

// Obtiene el ID del amigo desde la URL
if (isset($_GET['id'])) {
    $amigoId = $_GET['id'];
    $name = $_GET['name'];
    function enviarMensaje($conn, $id_enviador, $id_receptor, $texto_mensaje) {
        $sql = "INSERT INTO tbl_mensaje (id_enviador, id_receptor, texto_mensaje, timestamp) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iis", $id_enviador, $id_receptor, $texto_mensaje);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    // Procesar el envío de mensajes si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mensaje'])) {
        $id_enviador = $_SESSION['id_user'];
        $texto_mensaje = $_POST['mensaje'];

        // Función para enviar mensajes
        enviarMensaje($conn, $id_enviador, $amigoId, $texto_mensaje);

        // Redirige después del envío para evitar reenvíos al recargar la página
        header("Location: chat.php?id=$amigoId&name=$name");
        exit;
    }

    // Función para cargar mensajes
    function cargarMensajes($conn, $id_enviador, $id_receptor) {
        $sql = "SELECT *
                FROM tbl_mensaje AS M
                INNER JOIN tbl_users AS U ON M.id_enviador = U.id_user
                WHERE (M.id_enviador = ? AND M.id_receptor = ?) OR (M.id_enviador = ? AND M.id_receptor = ?)
                ORDER BY M.timestamp DESC"; // Cambiado a ASC para mostrar los mensajes en orden cronológico

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiii", $id_enviador, $id_receptor, $id_receptor, $id_enviador);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['id_enviador'] == $_SESSION['id_user']) {
                $mensajeClass = 'mensaje-usuario';
            } else {
                $mensajeClass = 'mensaje-amigo';
            }
            echo "<div class='mensaje $mensajeClass'>";
            echo "<p>{$row['username']} ({$row['timestamp']}): " . htmlspecialchars($row['texto_mensaje']) . "</p>";
            echo "</div>";
        }

        mysqli_stmt_close($stmt);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/chat.css">
    <title>Chat con <?php echo htmlspecialchars($name); ?></title>
</head>
<body>
    <h2 style="text-align: center;">Chat con <?php echo htmlspecialchars($name); ?></h2>

    <div id="chat">
    <form method="post" action="">
            <input type="text" name="mensaje" placeholder="Escribe tu mensaje" required>
            <button type="submit">Enviar</button>
        </form>
        <br>
            <div id="mensajes">
                <?php cargarMensajes($conn, $_SESSION['id_user'], $amigoId); ?>
            </div>
        
    </div>
    <a href="./listaamigo.php">Volver</a>

    <?php
    // Cierra la conexión a la base de datos.
    mysqli_close($conn);
    ?>
</body>
</html>
<?php
}
?>
