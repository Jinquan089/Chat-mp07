<?php

// Inicia la sesión.
session_start();

// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if ($_SESSION['user'] != "admin1") {
    header("Location: ../../index.php");
    exit;
}
$nom_real = $_POST['nom_real'];
$username = $_POST['username'];
$id_user = $_POST['id'];
$pwd = $_POST['pwd'];
?>

<form action="proc_modificar.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id_user ?>" >
    <input type="hidden" name="pwd" value="<?php echo $pwd ?>" >
    <label for="nom_real">Nombre Real</label>
    <input type="text" name= "nom_real" value="<?php echo $nom_real ?>">
    <br>
    <label for="username">Usuario</label>
    <input type="text" name= "username" value="<?php echo $username ?>">
    <br>
    <label for="pwdnew">Nueva Contraseña (Opcional)</label>
    <br>
    <input type="text" name= "pwdnew" value="">
    <br>
    <button type="submit" name="modificar" value="modificar">Modificar</button>
</form>