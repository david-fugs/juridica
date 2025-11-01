<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit;
}

include("../../conexion.php");
$tipo_usuario = $_SESSION['tipo_usuario'];
$id_usuario = $_SESSION['id'];

// Obtener documento del usuario si es abogado
$doc_usuario_actual = null;
if ($tipo_usuario == 1 || $tipo_usuario == '1') {
    $sql_doc = "SELECT documento FROM usuarios WHERE id = '$id_usuario' LIMIT 1";
    $res_doc = $mysqli->query($sql_doc);
    if ($res_doc && $res_doc->num_rows > 0) {
        $row_doc = $res_doc->fetch_assoc();
        $doc_usuario_actual = $row_doc['documento'];
    }
}

// Obtener parámetros de filtro
$estado_filter = isset($_GET['estado']) ? $_GET['estado'] : '';
$nombre = isset($_GET['nom_conc']) ? $_GET['nom_conc'] : '';
$tipo = isset($_GET['tipo_conc']) ? $_GET['tipo_conc'] : '';

// Construir WHERE
$where_conditions = [];
if (($tipo_usuario == 1 || $tipo_usuario == '1') && !empty($doc_usuario_actual)) {
    $where_conditions[] = "conciliaciones.doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'";
}
if ($estado_filter === 'realizada') {
    $where_conditions[] = "conciliaciones.realizada = 1";
} elseif ($estado_filter === 'activa') {
    $where_conditions[] = "(conciliaciones.realizada = 0 OR conciliaciones.realizada IS NULL)";
}
if (!empty($nombre)) {
    $where_conditions[] = "nom_conc LIKE '%" . $mysqli->real_escape_string($nombre) . "%'";
}
if (!empty($tipo)) {
    $where_conditions[] = "tipo_conc LIKE '%" . $mysqli->real_escape_string($tipo) . "%'";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

$sql = "SELECT conciliaciones.*, usuarios.nombre as nom_jur FROM conciliaciones 
        LEFT JOIN usuarios ON conciliaciones.doc_jur=usuarios.documento 
        $where_clause 
        ORDER BY fecha_conc DESC";
$result = $mysqli->query($sql);

$filename = "Conciliaciones_" . date('Y-m-d_His') . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
echo "\xEF\xBB\xBF";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #1e3c72; color: white; font-weight: bold; padding: 10px; border: 1px solid #000; }
        td { padding: 8px; border: 1px solid #000; }
        .realizada { background-color: #d4edda; }
        .activa { background-color: #fff3cd; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Abogado Asignado</th>
                <th>Auto Admisorio</th>
                <th>Días Transcurridos</th>
                <th>Observaciones</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contador = 1;
            while ($row = $result->fetch_assoc()) {
                $days_passed = null;
                if (!empty($row['auto_admisorio']) && $row['auto_admisorio'] !== '0000-00-00') {
                    $today = strtotime(date('Y-m-d'));
                    $target = strtotime($row['auto_admisorio']);
                    $diff_days = intval(round(($today - $target) / 86400));
                    $days_passed = max(0, $diff_days);
                }
                $estado_texto = (isset($row['realizada']) && $row['realizada'] == 1) ? 'REALIZADA' : 'ACTIVA';
                $class = (isset($row['realizada']) && $row['realizada'] == 1) ? 'realizada' : 'activa';
            ?>
                <tr class="<?php echo $class; ?>">
                    <td><?php echo $contador; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['fecha_conc'])); ?></td>
                    <td><?php echo htmlspecialchars($row['nom_conc']); ?></td>
                    <td><?php echo htmlspecialchars($row['tipo_conc']); ?></td>
                    <td><?php echo htmlspecialchars($row['nom_jur']); ?></td>
                    <td><?php echo !empty($row['auto_admisorio']) && $row['auto_admisorio'] !== '0000-00-00' ? date('d/m/Y', strtotime($row['auto_admisorio'])) : '—'; ?></td>
                    <td><?php echo is_null($days_passed) ? '—' : $days_passed; ?></td>
                    <td><?php echo htmlspecialchars($row['obs_conc']); ?></td>
                    <td><strong><?php echo $estado_texto; ?></strong></td>
                </tr>
            <?php $contador++; } ?>
        </tbody>
    </table>
</body>
</html>
