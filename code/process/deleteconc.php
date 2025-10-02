<?php
ini_set('display_errors',0);
error_reporting(0);
header('Content-Type: application/json');
include '../../conexion.php';

$id_conc = isset($_POST['id_conc']) ? intval($_POST['id_conc']) : 0;

if ($id_conc <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

$sql = "DELETE FROM conciliaciones WHERE id_conc = {$id_conc} LIMIT 1";

if ($mysqli->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Conciliación eliminada correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar conciliación: ' . $mysqli->error]);
}
?>
