<?php
// Inicia la sesión.
session_start();

// Verifica si el usuario ha iniciado sesión, de lo contrario, redirige al inicio de sesión.
if (!isset($_POST['eliminar'])) {
    header("Location: ../../index.php");
    exit;
}

include("../connection.php");
$id_user = $_POST['id'];

try {
    $conn->beginTransaction();

    // Eliminar solicitudes de amistad
    $stmtsele2 = $conn->prepare("SELECT so.id_solicitud AS id_lista FROM tbl_users us INNER JOIN tbl_listasolicitud so ON us.id_user = so.id_enviador WHERE so.id_enviador=:id_user OR so.id_receptor = :id_user");
    $stmtsele2->bindParam(':id_user', $id_user);
    $stmtsele2->execute();
    $result2 = $stmtsele2->fetchAll();
    
    foreach ($result2 as $row) {
        $deleteid2 = $row["id_lista"];
        $stmt2 = $conn->prepare("DELETE FROM `tbl_listasolicitud` WHERE `id_solicitud` = :deleteid2");
        $stmt2->bindParam(':deleteid2', $deleteid2);
        $stmt2->execute();
    }

    // Eliminar mensajes
    $stmtsele3 = $conn->prepare("SELECT me.id_mensaje AS id_lista FROM tbl_users us INNER JOIN tbl_mensaje me ON us.id_user = me.id_enviador WHERE me.id_enviador= :id_user OR me.id_receptor = :id_user");
    $stmtsele3->bindParam(':id_user', $id_user);
    $stmtsele3->execute();
    $result3 = $stmtsele3->fetchAll();
    
    foreach ($result3 as $row) {
        $deleteid3 = $row["id_lista"];
        $stmt3 = $conn->prepare("DELETE FROM `tbl_mensaje` WHERE `id_mensaje` = :deleteid3");
        $stmt3->bindParam(':deleteid3', $deleteid3);
        $stmt3->execute();
    }

    // Eliminar usuario
    $stmt = $conn->prepare("DELETE FROM tbl_users WHERE id_user = :id_user");
    $stmt->bindParam(':id_user', $id_user);
    $stmt->execute();

    $conn->commit();
    header("Location: ./enviarsoli.php");

} catch (Exception $e) {
    $conn->rollBack();
    echo "Error: ". $e->getMessage() ."<br>";
}
?>
