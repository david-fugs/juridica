<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if(!isset($_SESSION['id'])){
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

include("../../conexion.php");
date_default_timezone_set("America/Bogota");

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Recoger datos del POST
$fecha_rec = isset($_POST['fecha_rec']) ? $_POST['fecha_rec'] : '';
$nom_rec = isset($_POST['nom_rec']) ? strtoupper(trim($_POST['nom_rec'])) : '';
$reclamacion_rec = isset($_POST['reclamacion_rec']) ? strtoupper(trim($_POST['reclamacion_rec'])) : '';
$rad_rec = isset($_POST['rad_rec']) ? trim($_POST['rad_rec']) : '';
$doc_jur = isset($_POST['doc_jur']) ? trim($_POST['doc_jur']) : '';
$est_res_rec = isset($_POST['est_res_rec']) ? strtoupper(trim($_POST['est_res_rec'])) : '';
$obs_rec = isset($_POST['obs_rec']) ? strtoupper(trim($_POST['obs_rec'])) : '';

// Si no se proporciona fecha, usar la fecha actual
if(empty($fecha_rec)){
    $fecha_rec = date('Y-m-d');
}

// Sanitizar datos
$fecha_rec_s = $mysqli->real_escape_string($fecha_rec);
$nom_rec_s = $mysqli->real_escape_string($nom_rec);
$reclamacion_rec_s = $mysqli->real_escape_string($reclamacion_rec);
$rad_rec_s = $mysqli->real_escape_string($rad_rec);
$doc_jur_s = $mysqli->real_escape_string($doc_jur);
$est_res_rec_s = $mysqli->real_escape_string($est_res_rec);
$obs_rec_s = $mysqli->real_escape_string($obs_rec);

$fecha_add = date('Y-m-d H:i:s');
$id_usu = $_SESSION['id'];

// Insertar en la tabla reclamaciones
$sql = "INSERT INTO reclamaciones (fecha_rec, nom_rec, reclamacion_rec, rad_rec, doc_jur, est_res_rec, obs_rec, fecha_alta_rec, id_usu) 
        VALUES ('$fecha_rec_s', '$nom_rec_s', '$reclamacion_rec_s', '$rad_rec_s', '$doc_jur_s', '$est_res_rec_s', '$obs_rec_s', '$fecha_add', '$id_usu')";

if($mysqli->query($sql)){
    echo json_encode(['success' => true, 'message' => 'Reclamación creada exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear la reclamación: ' . $mysqli->error]);
}

?>