<?php
// Conexion a la base de datos
include_once("../connection.php");
session_start();

// Obtener el ID del usuario que envía la solicitud
$username = $_SESSION['user'];
$user_id = $_SESSION['id_user'];
$id_user_destino = $_POST['id_user_destino'];
        // Verificar si ya existe una solicitud pendiente entre los dos usuarios
        $sql_verificar = "SELECT * FROM tbl_listaSolicitud WHERE (id_enviador = ? AND id_receptor = ?) OR (id_enviador = ? AND id_receptor = ?)";
        $stmt_verificar = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt_verificar, $sql_verificar);
        mysqli_stmt_bind_param($stmt_verificar, "iiii", $user_id, $id_user_destino, $id_user_destino, $user_id);
        mysqli_stmt_execute($stmt_verificar);
        $result_verificar = mysqli_stmt_get_result($stmt_verificar);
            if (mysqli_num_rows($result_verificar) > 0) {
                header("Location: ./enviarsoli.php?soliexist=1");
            } else {
                // Preparar la consulta SQL para insertar la solicitud en tbl_listaSolicitud
                $sql_insert = "INSERT INTO tbl_listaSolicitud (id_enviador, id_receptor, status) VALUES (?, ?, 'pendiente')";
                $stmt_insert = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt_insert, $sql_insert);
                mysqli_stmt_bind_param($stmt_insert, "ii", $user_id, $id_user_destino);
                mysqli_stmt_execute($stmt_insert);
                header("Location: ./enviarsoli.php?enviado=1");
            }
mysqli_close($conn);
?>
