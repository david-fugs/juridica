<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if(!isset($_SESSION['id'])){
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

include("../../conexion.php");

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$id_rec = isset($_POST['id_rec']) ? $_POST['id_rec'] : '';
if(empty($id_rec)){
    echo json_encode(['success' => false, 'message' => 'id_rec no proporcionado']);
    exit;
}

$id_rec_s = $mysqli->real_escape_string($id_rec);

// Eliminar el registro
$sql = "DELETE FROM reclamaciones WHERE id_rec = '$id_rec_s'";

if($mysqli->query($sql)){
    echo json_encode(['success' => true, 'message' => 'Reclamación eliminada correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $mysqli->error]);
}

?>