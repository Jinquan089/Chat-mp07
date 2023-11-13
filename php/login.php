<?php
session_start();
include './connection.php';

if (!isset($_POST['login'])) {
    header('Location: ../index.php'); // Si no se envió el formulario de inicio de sesión, redirige a la página de inicio
} else {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

    // Consulta SQL para seleccionar el nombre de usuario y la contraseña hash de la base de datos
    $sql = "SELECT id_user, username, pwd FROM tbl_users WHERE username = ?";
    
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $resul1 = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resul1) == 1) {
        $row = mysqli_fetch_assoc($resul1);
        $hashed_pwd = $row['pwd'];
        // Verificar la contraseña utilizando password_verify
        if (password_verify($pwd, $hashed_pwd)) {
            $_SESSION['user'] = $user; // Iniciar sesión si la contraseña es correcta
            $_SESSION['id_user'] = $row['id_user'];
            header('Location: ./mostrar.php'); // Redirigir a la página de mostrar
        } else {
            header('Location: ../index.php?fallo=0'); // Redirigir a la página de inicio con un mensaje de error
        }
    } else {
        header('Location: ../index.php?fallo=0'); // Redirigir a la página de inicio con un mensaje de error si el usuario no existe
    }
}
