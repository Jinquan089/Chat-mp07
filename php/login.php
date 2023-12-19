<?php
session_start();
include './connection.php';

if (!isset($_POST['login'])) {
    header('Location: ../index.php'); // Si no se envió el formulario de inicio de sesión, redirige a la página de inicio
} else {
    $user = $_POST['user'];
    $pwd = $_POST['pwd'];

    try {
        // Consulta SQL para seleccionar el nombre de usuario y la contraseña hash de la base de datos
        $sql = $conn->prepare("SELECT id_user, username, pwd FROM tbl_users WHERE username = :user");
        $sql->bindParam(":user", $user);
        $sql->execute();
        $result = $sql->fetch();
        if ($result) {
            $hashed_pwd = $result['pwd'];
            // Verificar la contraseña utilizando password_verify
            if (password_verify($pwd, $hashed_pwd)) {
                $_SESSION['user'] = $user; // Iniciar sesión si la contraseña es correcta
                $_SESSION['id_user'] = $result['id_user'];
                header('Location: ./mostrar.php'); // Redirigir a la página de mostrar
            } else {
                header('Location: ../index.php?fallo='.$result['pwd']); // Redirigir a la página de inicio con un mensaje de error
            }
        } else {
            header('Location: ../index.php?fallo=2'); // Redirigir a la página de inicio con un mensaje de error si el usuario no existe
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}
?>
