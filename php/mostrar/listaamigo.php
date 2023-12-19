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

try {
    // Consulta SQL para recuperar la lista de amigos aceptados para el usuario actual.
    $stmtAmigos = $conn->prepare("SELECT DISTINCT U.id_user, U.username
    FROM tbl_listaAmistad AS LA
    INNER JOIN tbl_users AS U ON (LA.id_user1 = U.id_user OR LA.id_user2 = U.id_user)
    WHERE (LA.id_user1 = (SELECT id_user FROM tbl_users WHERE username = :user) 
    OR LA.id_user2 = (SELECT id_user FROM tbl_users WHERE username = :user))
    AND LA.status = 'aceptado'");
    $stmtAmigos->bindParam(':user', $user);
    $stmtAmigos->execute();
    $resultAmigos = $stmtAmigos->fetchAll();

    // Muestra la lista de amigos aceptados.
    if (count($resultAmigos) > 0) {
        echo "<h2>Lista de Amigos:</h2>";
        foreach ($resultAmigos as $row) {
            $amigoId = $row['id_user'];
            $amigoname = $row['username'];
            $amigoUsername = htmlspecialchars($row['username']);
            echo "<p>Amigo: <a href='./chat.php?id=$amigoId&name=$amigoname'>$amigoUsername</a></p>";
        }
    } else {
        echo "<p>No tienes amigos aceptados.</p>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Cierra la conexión a la base de datos.
$conn = null;

echo "<br>";
echo "<a href='../mostrar.php'>Volver</a>";
?>
