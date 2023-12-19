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
    function enviarMensaje($conn, $id_enviador, $amigoId, $texto_mensaje) {

        $stmt = $conn->prepare("INSERT INTO tbl_mensaje (id_enviador, id_receptor, texto_mensaje, timestamp) VALUES (:id_enviador, :id_receptor, :texto_mensaje, NOW())");
        $stmt->bindParam(':id_enviador', $id_enviador);
        $stmt->bindParam(':id_receptor', $amigoId);
        $stmt->bindParam(':texto_mensaje', $texto_mensaje);
        $stmt->execute();
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
        $stmt = $conn->prepare("SELECT M.*, U.username FROM tbl_mensaje AS M
        INNER JOIN tbl_users AS U ON M.id_enviador = U.id_user
        WHERE (M.id_enviador = :id_enviador AND M.id_receptor = :id_receptor) OR (M.id_enviador = :id_receptor AND M.id_receptor = :id_enviador)
        ORDER BY M.timestamp DESC");
        $stmt->bindParam(':id_enviador', $id_enviador);
        $stmt->bindParam(':id_receptor', $id_receptor);
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach ($result as $row) {
            if ($row['id_enviador'] == $_SESSION['id_user']) {
                $mensajeClass = 'mensaje-usuario';
            } else {
                $mensajeClass = 'mensaje-amigo';
            }
            echo "<div class='mensaje $mensajeClass'>";
            echo "<p>{$row['username']} ({$row['timestamp']}): " . htmlspecialchars($row['texto_mensaje']) . "</p>";
            echo "</div>";
        }
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
</body>
</html>

<?php
}
// Cerrar la conexión a la base de datos.
$conn = null;
?>
