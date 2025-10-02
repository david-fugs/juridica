<?php
    session_start();
    header('Content-Type: application/json; charset=utf-8');

    if(!isset($_SESSION['id'])){
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit;
    }

    require_once __DIR__ . '/../../conexion.php';

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if($id <= 0){
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        exit;
    }

    $sql = "DELETE FROM usuarios WHERE id = $id";
    if($mysqli->query($sql)){
        echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $mysqli->error]);
    }

    exit;
?>
