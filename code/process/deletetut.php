<?php
ini_set('display_errors',0);
error_reporting(0);
header('Content-Type: application/json');
include '../../conexion.php';

$id = isset($_POST['id_tut']) ? intval($_POST['id_tut']) : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

$sql = "DELETE FROM tutelas WHERE id_tut = {$id} LIMIT 1";
if ($mysqli->query($sql)) {
    if ($mysqli->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Tutela eliminada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Tutela no encontrada']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $mysqli->error]);
}
?>