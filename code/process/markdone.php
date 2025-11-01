<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
if(!isset($_SESSION['id'])){
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}
include("../../conexion.php");

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['success'=>false,'message'=>'Método no permitido']);
    exit;
}
$id_dem = isset($_POST['id_dem']) ? $mysqli->real_escape_string($_POST['id_dem']) : '';
if(empty($id_dem)){
    echo json_encode(['success'=>false,'message'=>'Falta id_dem']);
    exit;
}

$sql = "UPDATE demandas SET realizada=1 WHERE id_dem='".$id_dem."'";
if($mysqli->query($sql)){
    echo json_encode(['success'=>true,'message'=>'Demanda marcada como realizada']);
} else {
    echo json_encode(['success'=>false,'message'=>'Error al actualizar: '.$mysqli->error]);
}

exit;
?>