<?php
    session_start();
    header('Content-Type: application/json; charset=utf-8');

    if(!isset($_SESSION['id'])){
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit;
    }

    require_once __DIR__ . '/../../conexion.php';

    $fecha_dem = isset($_POST['fecha_dem']) ? $mysqli->real_escape_string($_POST['fecha_dem']) : '';
    $accionante_dem = isset($_POST['accionante_dem']) ? $mysqli->real_escape_string(strtoupper(trim($_POST['accionante_dem']))) : '';
    $doc_dem = isset($_POST['doc_dem']) ? $mysqli->real_escape_string(strtoupper(trim($_POST['doc_dem']))) : '';
    $rad_dem = isset($_POST['rad_dem']) ? $mysqli->real_escape_string(trim($_POST['rad_dem'])) : '';
    $desp_judi_dem = isset($_POST['desp_judi_dem']) ? $mysqli->real_escape_string(trim($_POST['desp_judi_dem'])) : '';
    $est_act_proc_dem = isset($_POST['est_act_proc_dem']) ? $mysqli->real_escape_string(strtoupper(trim($_POST['est_act_proc_dem']))) : '';
    $doc_jur = isset($_POST['doc_jur']) ? $mysqli->real_escape_string($_POST['doc_jur']) : '';
    $interno_dem = isset($_POST['interno_dem']) ? $mysqli->real_escape_string(strtoupper(trim($_POST['interno_dem']))) : '';
    $obs_dem = isset($_POST['obs_dem']) ? $mysqli->real_escape_string(strtoupper(trim($_POST['obs_dem']))) : '';
    
    $id_usu = $_SESSION['id'];
    $fecha_alta_dem = date('Y-m-d H:i:s');
    $estado_dem = 1;

    // No server-side required-field validation: validation is handled client-side
    // If fecha_dem not provided, default to server date
    if(empty($fecha_dem)){
        $fecha_dem = date('Y-m-d');
    }

    // Insertar demanda
    $sql = "INSERT INTO demandas (fecha_dem, accionante_dem, doc_dem, rad_dem, desp_judi_dem, est_act_proc_dem, doc_jur, interno_dem, obs_dem, estado_dem, fecha_alta_dem, fecha_edit_dem, id_usu) 
            VALUES ('$fecha_dem', '$accionante_dem', '$doc_dem', '$rad_dem', '$desp_judi_dem', '$est_act_proc_dem', '$doc_jur', '$interno_dem', '$obs_dem', '$estado_dem', '$fecha_alta_dem', '$fecha_alta_dem', '$id_usu')";
    
    if($mysqli->query($sql)){
        echo json_encode(['success' => true, 'message' => 'Demanda creada exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear demanda: ' . $mysqli->error]);
    }

    exit;
?>