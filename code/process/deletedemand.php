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

$id_dem = isset($_POST['id_dem']) ? $_POST['id_dem'] : '';
if(empty($id_dem)){
    echo json_encode(['success' => false, 'message' => 'id_dem no proporcionado']);
    exit;
}

$id_dem_s = $mysqli->real_escape_string($id_dem);

// Optionally, you could move the record to a soft-delete flag instead of deleting
$sql = "DELETE FROM demandas WHERE id_dem = '$id_dem_s'";

if($mysqli->query($sql)){
    echo json_encode(['success' => true, 'message' => 'Demanda eliminada correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $mysqli->error]);
}

?>
