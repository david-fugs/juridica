<?php
ini_set('display_errors',0);
error_reporting(0);
header('Content-Type: application/json');
include '../../conexion.php';

$id_tut = isset($_POST['id_tut']) ? intval($_POST['id_tut']) : 0;
$fecha_tut = isset($_POST['fecha_tut']) ? $mysqli->real_escape_string($_POST['fecha_tut']) : '';
$nom_tut = isset($_POST['nom_tut']) ? $mysqli->real_escape_string($_POST['nom_tut']) : '';
$tipo_tut = isset($_POST['tipo_tut']) ? $mysqli->real_escape_string($_POST['tipo_tut']) : '';
$doc_jur = isset($_POST['doc_jur']) && trim($_POST['doc_jur']) !== '' ? $mysqli->real_escape_string(trim($_POST['doc_jur'])) : null;
$estado_tut = isset($_POST['estado_tut']) ? $mysqli->real_escape_string($_POST['estado_tut']) : '';
$obs_tut = isset($_POST['obs_tut']) ? $mysqli->real_escape_string($_POST['obs_tut']) : '';
$fecha_edit_tut = date('Y-m-d H:i:s');

if ($id_tut <= 0) {
    echo json_encode(['success'=>false, 'message'=>'ID inválido']);
    exit;
}

// doc_jur opcional: si se asigna por primera vez se registra fecha_asignacion_jur (se preserva si ya existía)
$doc_jur_val = is_null($doc_jur) ? 'NULL' : "'{$doc_jur}'";
$fecha_asignacion_expr = is_null($doc_jur) ? 'NULL' : 'COALESCE(fecha_asignacion_jur, NOW())';

// Estados terminales congelan el conteo de días (se preserva la primera fecha de cierre)
$estados_cerrados = ['Resuelta', 'Fallada', 'Archivada', 'Cerrada'];
$fecha_cierre_expr = in_array($estado_tut, $estados_cerrados, true) ? 'COALESCE(fecha_cierre, NOW())' : 'NULL';

$sql = "UPDATE tutelas SET fecha_tut = '{$fecha_tut}', nom_tut = '{$nom_tut}', tipo_tut = '{$tipo_tut}', doc_jur = {$doc_jur_val}, fecha_asignacion_jur = {$fecha_asignacion_expr}, estado_tut = '{$estado_tut}', fecha_cierre = {$fecha_cierre_expr}, obs_tut = '{$obs_tut}', fecha_edit_tut = '{$fecha_edit_tut}' WHERE id_tut = {$id_tut} LIMIT 1";
if ($mysqli->query($sql)) {
    echo json_encode(['success'=>true, 'message'=>'Tutela actualizada correctamente']);
} else {
    echo json_encode(['success'=>false, 'message'=>'Error al actualizar: ' . $mysqli->error]);
}
?>