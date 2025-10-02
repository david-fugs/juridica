<?php
    session_start();
    header('Content-Type: application/json; charset=utf-8');

    if(!isset($_SESSION['id'])){
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit;
    }

    require_once __DIR__ . '/../../conexion.php';

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = isset($_POST['nombre']) ? $mysqli->real_escape_string(trim($_POST['nombre'])) : '';
    $usuario = isset($_POST['usuario']) ? $mysqli->real_escape_string(trim($_POST['usuario'])) : '';
    $tipo_usuario = isset($_POST['tipo_usuario']) ? intval($_POST['tipo_usuario']) : 0;
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if($id <= 0 || empty($nombre) || empty($usuario) || ($tipo_usuario !== 1 && $tipo_usuario !== 2)){
        echo json_encode(['success' => false, 'message' => 'Datos incompletos o inválidos']);
        exit;
    }

    // Verificar usuario duplicado (excluir el propio id)
    $sql_check = "SELECT id FROM usuarios WHERE usuario = '$usuario' AND id <> $id";
    $res_check = $mysqli->query($sql_check);
    if($res_check && $res_check->num_rows > 0){
        echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya está en uso']);
        exit;
    }

    $fields = [];
    $fields[] = "nombre = '$nombre'";
    $fields[] = "usuario = '$usuario'";
    $fields[] = "tipo_usuario = '$tipo_usuario'";

    if(!empty($password)){
        // encriptar con sha1
        $pass_enc = sha1($password);
        $fields[] = "password = '$pass_enc'";
    }

    $sql = "UPDATE usuarios SET " . implode(', ', $fields) . " WHERE id = $id";
    if($mysqli->query($sql)){
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $mysqli->error]);
    }

    exit;
?>
