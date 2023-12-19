<?php
include 'connection.php'; // Incluye el archivo de conexión a la base de datos

if (isset($_POST['registrar'])) { // Verifica si se ha enviado el formulario
    $user = $_POST['user'];
    $pwd_reg = $_POST['pwd_reg'];
    $confirm_pwd = $_POST['confirm_pwd'];
    $nomreal = $_POST['nomreal'];

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
    $sqlreg = $conn->prepare("SELECT username FROM tbl_users WHERE username = :user");
    $sqlreg->bindParam(':user', $user);
    $sqlreg->execute();

    $resultadoreg = $sqlreg->fetchAll();

    if (count($resultadoreg) > 0) {
        header('Location: ./registrar.php?error=existe'); // El nombre de usuario ya existe
        exit();
    }

    // Encriptar la contraseña
    $pwdenc = password_hash($pwd_reg, PASSWORD_BCRYPT);

    // Usar una sentencia preparada para insertar el usuario en la base de datos
    $sql = "INSERT INTO tbl_users (username, pwd, nom_real) VALUES (:user, :pwdenc, :nomreal)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':pwdenc', $pwdenc);
        $stmt->bindParam(':nomreal', $nomreal);

        if ($stmt->execute()) {
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
