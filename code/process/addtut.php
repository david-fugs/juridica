<?php
ini_set('display_errors',0);
error_reporting(0);
header('Content-Type: application/json');
include '../../conexion.php';

// Read POST data - usando estructura real de tutelas
$fecha_tut = isset($_POST['fecha_tut']) ? $mysqli->real_escape_string($_POST['fecha_tut']) : '';
$nom_tut = isset($_POST['nom_tut']) ? $mysqli->real_escape_string($_POST['nom_tut']) : '';
$tipo_tut = isset($_POST['tipo_tut']) ? $mysqli->real_escape_string($_POST['tipo_tut']) : '';
$doc_jur = isset($_POST['doc_jur']) ? $mysqli->real_escape_string($_POST['doc_jur']) : '';
$estado_tut = isset($_POST['estado_tut']) ? $mysqli->real_escape_string($_POST['estado_tut']) : '';
$obs_tut = isset($_POST['obs_tut']) ? $mysqli->real_escape_string($_POST['obs_tut']) : '';
$fecha_alta_tut = date('Y-m-d H:i:s');
$id_usu = $_SESSION['id'] ?? 1; // ID del usuario que crea

$sql = "INSERT INTO tutelas (fecha_tut, nom_tut, tipo_tut, doc_jur, estado_tut, obs_tut, fecha_alta_tut, id_usu) VALUES ('{$fecha_tut}', '{$nom_tut}', '{$tipo_tut}', '{$doc_jur}', '{$estado_tut}', '{$obs_tut}', '{$fecha_alta_tut}', {$id_usu})";

if ($mysqli->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Tutela creada correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear tutela: ' . $mysqli->error]);
}
?>