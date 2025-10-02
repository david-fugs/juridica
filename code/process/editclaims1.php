<?php
// Prevent PHP notices/warnings from corrupting JSON output
ini_set('display_errors', 0);
error_reporting(0);
session_start();
header('Content-Type: application/json; charset=utf-8');
if(!isset($_SESSION['id'])){
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}
include("../../conexion.php");

date_default_timezone_set("America/Bogota");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Expected fields
    $id_rec = isset($_POST['id_rec']) ? $_POST['id_rec'] : '';
    $fecha_rec = isset($_POST['fecha_rec']) ? $_POST['fecha_rec'] : '';
    $nom_rec = isset($_POST['nom_rec']) ? strtoupper(trim($_POST['nom_rec'])) : '';
    $reclamacion_rec = isset($_POST['reclamacion_rec']) ? strtoupper(trim($_POST['reclamacion_rec'])) : '';
    $rad_rec = isset($_POST['rad_rec']) ? trim($_POST['rad_rec']) : '';
    $doc_jur = isset($_POST['doc_jur']) ? trim($_POST['doc_jur']) : '';
    $est_res_rec = isset($_POST['est_res_rec']) ? strtoupper(trim($_POST['est_res_rec'])) : '';
    $obs_rec = isset($_POST['obs_rec']) ? strtoupper(trim($_POST['obs_rec'])) : '';

    // Minimal server-side validation
    $missing = [];
    if(empty($id_rec)) $missing[] = 'id_rec';
    if(empty($nom_rec)) $missing[] = 'nom_rec';
    if(empty($reclamacion_rec)) $missing[] = 'reclamacion_rec';

    if(!empty($missing)){
        echo json_encode(['success' => false, 'message' => 'Faltan campos: ' . implode(', ', $missing)]);
        exit;
    }

    // sanitize
    $id_rec_s = $mysqli->real_escape_string($id_rec);
    $fecha_rec_s = $mysqli->real_escape_string($fecha_rec);
    $nom_rec_s = $mysqli->real_escape_string($nom_rec);
    $reclamacion_rec_s = $mysqli->real_escape_string($reclamacion_rec);
    $rad_rec_s = $mysqli->real_escape_string($rad_rec);
    $doc_jur_s = $mysqli->real_escape_string($doc_jur);
    $est_res_rec_s = $mysqli->real_escape_string($est_res_rec);
    $obs_rec_s = $mysqli->real_escape_string($obs_rec);
    $fecha_edit = date('Y-m-d H:i:s');
    $id_usu = $_SESSION['id'];

    $sql = "UPDATE reclamaciones SET fecha_rec='$fecha_rec_s', nom_rec='$nom_rec_s', reclamacion_rec='$reclamacion_rec_s', rad_rec='$rad_rec_s', doc_jur='$doc_jur_s', est_res_rec='$est_res_rec_s', obs_rec='$obs_rec_s', fecha_edit_rec='$fecha_edit', id_usu='$id_usu' WHERE id_rec='$id_rec_s'";

    if($mysqli->query($sql)){
        echo json_encode(['success' => true, 'message' => 'Reclamación actualizada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $mysqli->error]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

?>
