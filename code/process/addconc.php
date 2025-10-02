<?php
ini_set('display_errors',0);
error_reporting(0);
header('Content-Type: application/json');
include '../../conexion.php';

// Read POST data - usando estructura real de conciliaciones
$accionante_conc = isset($_POST['accionante_conc']) ? $mysqli->real_escape_string($_POST['accionante_conc']) : '';
$doc_conc = isset($_POST['doc_conc']) ? $mysqli->real_escape_string($_POST['doc_conc']) : '';
$causa_litigio_conc = isset($_POST['causa_litigio_conc']) ? $mysqli->real_escape_string($_POST['causa_litigio_conc']) : '';
$medio_control_conc = isset($_POST['medio_control_conc']) ? $mysqli->real_escape_string($_POST['medio_control_conc']) : '';
$procuraduria_conc = isset($_POST['procuraduria_conc']) ? $mysqli->real_escape_string($_POST['procuraduria_conc']) : '';
$rad_conc = isset($_POST['rad_conc']) ? $mysqli->real_escape_string($_POST['rad_conc']) : '';
$fecha_conc = isset($_POST['fecha_conc']) ? $mysqli->real_escape_string($_POST['fecha_conc']) : '';
$doc_jur = isset($_POST['doc_jur']) ? $mysqli->real_escape_string($_POST['doc_jur']) : '';
$estado_conc = isset($_POST['estado_conc']) ? $mysqli->real_escape_string($_POST['estado_conc']) : '';
$obs_conc = isset($_POST['obs_conc']) ? $mysqli->real_escape_string($_POST['obs_conc']) : '';
$fecha_alta_conc = date('Y-m-d H:i:s');
$id_usu = $_SESSION['id'] ?? 1; // ID del usuario que crea

$sql = "INSERT INTO conciliaciones (accionante_conc, doc_conc, causa_litigio_conc, medio_control_conc, procuraduria_conc, rad_conc, fecha_conc, doc_jur, estado_conc, obs_conc, fecha_alta_conc, id_usu) 
        VALUES ('{$accionante_conc}', '{$doc_conc}', '{$causa_litigio_conc}', '{$medio_control_conc}', '{$procuraduria_conc}', '{$rad_conc}', '{$fecha_conc}', '{$doc_jur}', '{$estado_conc}', '{$obs_conc}', '{$fecha_alta_conc}', {$id_usu})";

if ($mysqli->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Conciliación creada correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear conciliación: ' . $mysqli->error]);
}
?>
