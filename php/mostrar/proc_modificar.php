<?php
// Inicia la sesión.
session_start();

// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_POST['modificar'])) {
    header("Location: ../../index.php");
    exit;
}

$id_user = $_POST['id'];
$username = $_POST['username'];
$nom_real = $_POST['nom_real'];

if ($_POST['pwdnew'] != NULL) {
    $pwdnew = $_POST['pwdnew'];
    $pwd = password_hash($pwdnew, PASSWORD_BCRYPT);
} else {
    $pwd = $_POST['pwd'];
}

include("../connection.php");

// Verificar si el nuevo nombre de usuario ya existe en la base de datos
$stmt = $conn->prepare("SELECT `username` FROM tbl_users WHERE `username` = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$result = $stmt->fetchAll();

if ($username == $result['username']) {
    header("Location: ./enviarsoli.php?exist=1");
} else {
    $sqlmodi = "UPDATE `tbl_users` SET `username` = :username, `nom_real` = :nom_real, `pwd` = :pwd WHERE (`id_user` = :id_user)";
    $stmt = $conn->prepare($sqlmodi);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':nom_real', $nom_real);
    $stmt->bindParam(':pwd', $pwd);
    $stmt->bindParam(':id_user', $id_user);
    $stmt->execute();
    header("Location: ./enviarsoli.php");
}
