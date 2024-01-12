<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}

include("../connection.php");

// Obtener el ID del amigo desde la URL
if (isset($_POST['id_user'])) {
        $id_receptor=$_POST['id_user'];
        $id_enviador=$_SESSION['id_user'];
        $stmt = $conn->prepare("SELECT M.*, U.username FROM tbl_mensaje AS M INNER JOIN tbl_users AS U ON M.id_enviador = U.id_user
        WHERE (M.id_enviador = :id_enviador AND M.id_receptor = :id_receptor) OR (M.id_enviador = :id_receptor AND M.id_receptor = :id_enviador)
        ORDER BY M.timestamp DESC");
        $stmt->bindParam(':id_enviador', $id_enviador);
        $stmt->bindParam(':id_receptor', $id_receptor);
        $stmt->execute(); 
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() == 0) {
            $stmt2 = $conn->prepare("SELECT username FROM tbl_users WHERE id_user = :id_receptor");
            $stmt2->bindParam(':id_receptor', $id_receptor);
            $stmt2->execute();
            $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result2);
        } else {
            echo json_encode($result);
        }
        
    }
?>
