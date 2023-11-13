<?php
include 'connection.php'; // Incluye el archivo de conexión a la base de datos

if (isset($_POST['registrar'])) { // Verifica si se ha enviado el formulario
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pwd_reg = mysqli_real_escape_string($conn, $_POST['pwd_reg']);
    $confirm_pwd = mysqli_real_escape_string($conn, $_POST['confirm_pwd']);
    $nomreal = mysqli_real_escape_string($conn, $_POST['nomreal']);

    // Validar que los campos no estén vacíos
    if (empty($user) || empty($pwd_reg) || empty($confirm_pwd) || empty($nomreal)) {
        header('Location: ./registrar.php?error=vacio');
        exit();
    }

    if ($pwd_reg !== $confirm_pwd) {
        header('Location: ./registrar.php?error=pass');
        exit();
    }
    
    // Verificar si el nombre de usuario ya existe en la base de datos
    $sql_check_user = "SELECT username FROM tbl_users WHERE username = ?";
    $stmt_check_user = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt_check_user, $sql_check_user);
    mysqli_stmt_bind_param($stmt_check_user, "s", $user);
    mysqli_stmt_execute($stmt_check_user);
    $resul_check_user = mysqli_stmt_get_result($stmt_check_user);

    if (mysqli_num_rows($resul_check_user) > 0) {
        header('Location: ./registrar.php?error=existe'); // El nombre de usuario ya existe
        exit();
    }

    // Encriptar la contraseña
    $pwdenc = password_hash($pwd_reg, PASSWORD_BCRYPT);

    // Usar una sentencia preparada para insertar el usuario en la base de datos
    $sql = "INSERT INTO tbl_users (username, pwd, nom_real) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $user, $pwdenc, $nomreal);
        if (mysqli_stmt_execute($stmt)) {
            header('Location: ../index.php'); // Redirige a la página principal si la inserción fue exitosa
            exit();
        } else {
            header('Location: ./registrar.php?error=2'); // Redirige a la página de registro con un mensaje de error de base de datos si la inserción falló
            exit();
        }
    } else {
        header('Location: ./registrar.php?error=2'); // Redirige a la página de registro con un mensaje de error de base de datos si la inserción falló
        exit();
    }
} else { 
    header('Location: ../index.php');
}
