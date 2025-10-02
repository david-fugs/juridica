<?php
ini_set('display_errors',0);
error_reporting(0);
header('Content-Type: application/json');
include '../../conexion.php';

$id_tut = isset($_POST['id_tut']) ? intval($_POST['id_tut']) : 0;
$fecha_tut = isset($_POST['fecha_tut']) ? $mysqli->real_escape_string($_POST['fecha_tut']) : '';
$nom_tut = isset($_POST['nom_tut']) ? $mysqli->real_escape_string($_POST['nom_tut']) : '';
$tipo_tut = isset($_POST['tipo_tut']) ? $mysqli->real_escape_string($_POST['tipo_tut']) : '';
$doc_jur = isset($_POST['doc_jur']) ? $mysqli->real_escape_string($_POST['doc_jur']) : '';
$estado_tut = isset($_POST['estado_tut']) ? $mysqli->real_escape_string($_POST['estado_tut']) : '';
$obs_tut = isset($_POST['obs_tut']) ? $mysqli->real_escape_string($_POST['obs_tut']) : '';
$fecha_edit_tut = date('Y-m-d H:i:s');

if ($id_tut <= 0) {
    echo json_encode(['success'=>false, 'message'=>'ID inválido']);
    exit;
}

$sql = "UPDATE tutelas SET fecha_tut = '{$fecha_tut}', nom_tut = '{$nom_tut}', tipo_tut = '{$tipo_tut}', doc_jur = '{$doc_jur}', estado_tut = '{$estado_tut}', obs_tut = '{$obs_tut}', fecha_edit_tut = '{$fecha_edit_tut}' WHERE id_tut = {$id_tut} LIMIT 1";
if ($mysqli->query($sql)) {
    echo json_encode(['success'=>true, 'message'=>'Tutela actualizada correctamente']);
} else {
    echo json_encode(['success'=>false, 'message'=>'Error al actualizar: ' . $mysqli->error]);
}
?>