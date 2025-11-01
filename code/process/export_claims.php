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
$accionante = isset($_GET['accionante_rec']) ? $_GET['accionante_rec'] : '';
$radicado = isset($_GET['rad_rec']) ? $_GET['rad_rec'] : '';

// Construir WHERE
$where_conditions = [];

// Filtro por tipo de usuario
if (($tipo_usuario == 1 || $tipo_usuario == '1') && !empty($doc_usuario_actual)) {
    $where_conditions[] = "reclamaciones.doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'";
}

// Filtro por estado realizada
if ($estado_filter === 'realizada') {
    $where_conditions[] = "reclamaciones.realizada = 1";
} elseif ($estado_filter === 'activa') {
    $where_conditions[] = "(reclamaciones.realizada = 0 OR reclamaciones.realizada IS NULL)";
}

if (!empty($accionante)) {
    $where_conditions[] = "accionante_rec LIKE '%" . $mysqli->real_escape_string($accionante) . "%'";
}
if (!empty($radicado)) {
    $where_conditions[] = "rad_rec LIKE '%" . $mysqli->real_escape_string($radicado) . "%'";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Query principal
$sql = "SELECT reclamaciones.*, usuarios.nombre as nom_jur FROM reclamaciones 
        LEFT JOIN usuarios ON reclamaciones.doc_jur=usuarios.documento 
        $where_clause 
        ORDER BY fecha_rec DESC";

$result = $mysqli->query($sql);

// Configurar headers para descarga Excel
$filename = "Reclamaciones_" . date('Y-m-d_His') . ".xls";
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
                <th>Accionante</th>
                <th>Documento</th>
                <th>Radicado</th>
                <th>Despacho Judicial</th>
                <th>Abogado Asignado</th>
                <th>Auto Admisorio</th>
                <th>Días Transcurridos</th>
                <th>Estado Actual</th>
                <th>Observaciones</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contador = 1;
            while ($row = $result->fetch_assoc()) {
                // Calcular días
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
                    <td><?php echo date('d/m/Y', strtotime($row['fecha_rec'])); ?></td>
                    <td><?php echo htmlspecialchars($row['accionante_rec']); ?></td>
                    <td><?php echo htmlspecialchars($row['doc_rec']); ?></td>
                    <td><?php echo htmlspecialchars($row['rad_rec']); ?></td>
                    <td><?php echo htmlspecialchars($row['desp_judi_rec']); ?></td>
                    <td><?php echo htmlspecialchars($row['nom_jur']); ?></td>
                    <td><?php echo !empty($row['auto_admisorio']) && $row['auto_admisorio'] !== '0000-00-00' ? date('d/m/Y', strtotime($row['auto_admisorio'])) : '—'; ?></td>
                    <td><?php echo is_null($days_passed) ? '—' : $days_passed; ?></td>
                    <td><?php echo htmlspecialchars($row['est_act_proc_rec']); ?></td>
                    <td><?php echo htmlspecialchars($row['obs_rec']); ?></td>
                    <td><strong><?php echo $estado_texto; ?></strong></td>
                </tr>
            <?php
                $contador++;
            }
            ?>
        </tbody>
    </table>
</body>
</html>
