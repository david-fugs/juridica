<?php
session_start();
include("../../conexion.php");

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Sesión no válida']);
    exit;
}

if (isset($_POST['id_rec'])) {
    $id_rec = intval($_POST['id_rec']);
    
    $sql = "UPDATE reclamaciones SET realizada = 1 WHERE id_rec = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id_rec);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Reclamación marcada como realizada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $mysqli->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
}
?>
