<?php
// Inicia la sesión.
session_start();
include("../../connection.php");

// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_SESSION['user'])) {
    header("Location: ../../../login.php");
    exit;
}

    $request_id = $_GET['id'];
