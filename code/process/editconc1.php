<?php
session_start();
header('Content-Type: application/json');
include '../../conexion.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_conc = isset($_POST['id_conc']) ? intval($_POST['id_conc']) : 0;
    $accionante_conc = isset($_POST['accionante_conc']) ? trim($_POST['accionante_conc']) : '';
    $doc_conc = isset($_POST['doc_conc']) ? trim($_POST['doc_conc']) : '';
    $causa_litigio_conc = isset($_POST['causa_litigio_conc']) ? trim($_POST['causa_litigio_conc']) : '';
    $medio_control_conc = isset($_POST['medio_control_conc']) ? trim($_POST['medio_control_conc']) : '';
    $procuraduria_conc = isset($_POST['procuraduria_conc']) ? trim($_POST['procuraduria_conc']) : '';
    $rad_conc = isset($_POST['rad_conc']) ? trim($_POST['rad_conc']) : '';
    $fecha_conc = isset($_POST['fecha_conc']) ? trim($_POST['fecha_conc']) : '';
    $doc_jur = isset($_POST['doc_jur']) ? trim($_POST['doc_jur']) : '';
    $estado_conc = isset($_POST['estado_conc']) ? trim($_POST['estado_conc']) : '';
    $obs_conc = isset($_POST['obs_conc']) ? trim($_POST['obs_conc']) : '';
    
    if ($id_conc > 0 && $accionante_conc !== '' && $doc_conc !== '') {
        $fecha_edit_conc = date('Y-m-d H:i:s');
        
        $stmt = $mysqli->prepare("UPDATE conciliaciones SET accionante_conc=?, doc_conc=?, causa_litigio_conc=?, medio_control_conc=?, procuraduria_conc=?, rad_conc=?, fecha_conc=?, doc_jur=?, estado_conc=?, obs_conc=?, fecha_edit_conc=? WHERE id_conc=? LIMIT 1");
        $stmt->bind_param('sssssssssssi', $accionante_conc, $doc_conc, $causa_litigio_conc, $medio_control_conc, $procuraduria_conc, $rad_conc, $fecha_conc, $doc_jur, $estado_conc, $obs_conc, $fecha_edit_conc, $id_conc);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Conciliación actualizada correctamente';
        } else {
            $response['message'] = 'Error al actualizar la conciliación: ' . $mysqli->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Datos incompletos';
    }
} else {
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
$mysqli->close();
?>
