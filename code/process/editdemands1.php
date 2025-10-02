<?php
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
    $id_dem = isset($_POST['id_dem']) ? $_POST['id_dem'] : '';
    $fecha_dem = isset($_POST['fecha_dem']) ? $_POST['fecha_dem'] : '';
    $accionante_dem = isset($_POST['accionante_dem']) ? strtoupper(trim($_POST['accionante_dem'])) : '';
    $doc_dem = isset($_POST['doc_dem']) ? strtoupper(trim($_POST['doc_dem'])) : '';
    $rad_dem = isset($_POST['rad_dem']) ? trim($_POST['rad_dem']) : '';
    $desp_judi_dem = isset($_POST['desp_judi_dem']) ? trim($_POST['desp_judi_dem']) : '';
    $est_act_proc_dem = isset($_POST['est_act_proc_dem']) ? strtoupper(trim($_POST['est_act_proc_dem'])) : '';
    $doc_jur = isset($_POST['doc_jur']) ? trim($_POST['doc_jur']) : '';
    $interno_dem = isset($_POST['interno_dem']) ? strtoupper(trim($_POST['interno_dem'])) : '';
    $obs_dem = isset($_POST['obs_dem']) ? strtoupper(trim($_POST['obs_dem'])) : '';

    // Minimal server-side validation
    $missing = [];
    if(empty($id_dem)) $missing[] = 'id_dem';
    if(empty($rad_dem)) $missing[] = 'rad_dem';
    if(empty($accionante_dem)) $missing[] = 'accionante_dem';

    if(!empty($missing)){
        echo json_encode(['success' => false, 'message' => 'Faltan campos: ' . implode(', ', $missing)]);
        exit;
    }

    // sanitize
    $id_dem_s = $mysqli->real_escape_string($id_dem);
    $fecha_dem_s = $mysqli->real_escape_string($fecha_dem);
    $accionante_dem_s = $mysqli->real_escape_string($accionante_dem);
    $doc_dem_s = $mysqli->real_escape_string($doc_dem);
    $rad_dem_s = $mysqli->real_escape_string($rad_dem);
    $desp_judi_dem_s = $mysqli->real_escape_string($desp_judi_dem);
    $est_act_proc_dem_s = $mysqli->real_escape_string($est_act_proc_dem);
    $doc_jur_s = $mysqli->real_escape_string($doc_jur);
    $interno_dem_s = $mysqli->real_escape_string($interno_dem);
    $obs_dem_s = $mysqli->real_escape_string($obs_dem);
    $fecha_edit = date('Y-m-d H:i:s');
    $id_usu = $_SESSION['id'];

    $sql = "UPDATE demandas SET fecha_dem='$fecha_dem_s', accionante_dem='$accionante_dem_s', doc_dem='$doc_dem_s', rad_dem='$rad_dem_s', desp_judi_dem='$desp_judi_dem_s', est_act_proc_dem='$est_act_proc_dem_s', doc_jur='$doc_jur_s', interno_dem='$interno_dem_s', obs_dem='$obs_dem_s', fecha_edit_dem='$fecha_edit', id_usu='$id_usu' WHERE id_dem='$id_dem_s'";

    if($mysqli->query($sql)){
        echo json_encode(['success' => true, 'message' => 'Demanda actualizada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $mysqli->error]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

?>
