<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit;
}

require_once '../../vendor/autoload.php';
include("../../conexion.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

$tipo_usuario = $_SESSION['tipo_usuario'];
$id_usuario = $_SESSION['id'];

// Obtener documento del usuario si es abogado
$doc_usuario_actual = null;
if ($tipo_usuario == 1 || $tipo_usuario == '1') {
    $sql_doc = "SELECT documento FROM usuarios WHERE id = ? LIMIT 1";
    $stmt_doc = $mysqli->prepare($sql_doc);
    $stmt_doc->bind_param("i", $id_usuario);
    $stmt_doc->execute();
    $res_doc = $stmt_doc->get_result();
    if ($res_doc && $res_doc->num_rows > 0) {
        $row_doc = $res_doc->fetch_assoc();
        $doc_usuario_actual = $row_doc['documento'];
    }
}

// Obtener parámetros de filtro
$estado_filter = isset($_GET['estado']) ? $_GET['estado'] : '';
$nom_rec = isset($_GET['nom_rec']) ? $_GET['nom_rec'] : '';
$rad_rec = isset($_GET['rad_rec']) ? $_GET['rad_rec'] : '';

// Construir WHERE
$where_conditions = [];
$params = [];
$types = '';

// Filtro por tipo de usuario
if (($tipo_usuario == 1 || $tipo_usuario == '1') && !empty($doc_usuario_actual)) {
    $where_conditions[] = "reclamaciones.doc_jur = ?";
    $params[] = $doc_usuario_actual;
    $types .= 's';
}

// Filtro por estado realizada
if ($estado_filter === 'realizada') {
    $where_conditions[] = "reclamaciones.realizada = 1";
} elseif ($estado_filter === 'activa') {
    $where_conditions[] = "(reclamaciones.realizada = 0 OR reclamaciones.realizada IS NULL)";
}

if (!empty($nom_rec)) {
    $where_conditions[] = "nom_rec LIKE ?";
    $params[] = "%$nom_rec%";
    $types .= 's';
}

if (!empty($rad_rec)) {
    $where_conditions[] = "rad_rec LIKE ?";
    $params[] = "%$rad_rec%";
    $types .= 's';
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Query principal
$sql = "SELECT reclamaciones.*, usuarios.nombre as nom_jur FROM reclamaciones 
        LEFT JOIN usuarios ON reclamaciones.doc_jur=usuarios.documento 
        $where_clause 
        ORDER BY fecha_rec DESC";

$stmt = $mysqli->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Crear nuevo Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reclamaciones');

// Configurar encabezados
$headers = ['#', 'Fecha', 'Solicitante', 'Tipo', 'Documento', 'Radicado', 
            'Abogado Asignado', 'Auto Admisorio', 'Días Transcurridos', 
            'Observaciones', 'Estado'];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Estilo de encabezado
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 12
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '1e3c72']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
];

$sheet->getStyle('A1:K1')->applyFromArray($headerStyle);

// Ajustar altura de encabezado
$sheet->getRowDimension(1)->setRowHeight(30);

// Ajustar ancho de columnas (más anchas)
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(35);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(18);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(25);
$sheet->getColumnDimension('H')->setWidth(18);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(40);
$sheet->getColumnDimension('K')->setWidth(15);

// Llenar datos
$row = 2;
$contador = 1;

while ($data = $result->fetch_assoc()) {
    // Calcular días transcurridos
    $days_passed = null;
    if (!empty($data['auto_admisorio']) && $data['auto_admisorio'] !== '0000-00-00') {
        $today = strtotime(date('Y-m-d'));
        $target = strtotime($data['auto_admisorio']);
        $diff_days = intval(round(($today - $target) / 86400));
        $days_passed = max(0, $diff_days);
    }
    
    $estado_texto = (isset($data['realizada']) && $data['realizada'] == 1) ? 'REALIZADA' : 'ACTIVA';
    
    // Determinar color de fondo
    $bgColor = 'FFFFFF';
    if (isset($data['realizada']) && $data['realizada'] == 1) {
        $bgColor = 'e9ecef'; // Gris - realizada
    } elseif (!is_null($days_passed)) {
        if ($days_passed >= 1 && $days_passed <= 11) {
            $bgColor = 'c7f0d6'; // Verde
        } elseif ($days_passed >= 12 && $days_passed <= 19) {
            $bgColor = 'ffe6b3'; // Naranja
        } elseif ($days_passed >= 20 && $days_passed <= 30) {
            $bgColor = 'f8d7da'; // Rojo
        }
    }
    
    // Datos de la fila
    $sheet->setCellValue('A' . $row, $contador);
    $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($data['fecha_rec'])));
    $sheet->setCellValue('C' . $row, $data['nom_rec'] ?? '');
    $sheet->setCellValue('D' . $row, $data['tipo_rec'] ?? '');
    $sheet->setCellValue('E' . $row, $data['doc_rec'] ?? '');
    
    // Radicado como TEXTO para evitar notación científica
    $sheet->setCellValueExplicit('F' . $row, $data['rad_rec'] ?? '', DataType::TYPE_STRING);
    
    $sheet->setCellValue('G' . $row, $data['nom_jur'] ?? '');
    $sheet->setCellValue('H' . $row, 
        !empty($data['auto_admisorio']) && $data['auto_admisorio'] !== '0000-00-00' 
        ? date('d/m/Y', strtotime($data['auto_admisorio'])) 
        : '—');
    $sheet->setCellValue('I' . $row, is_null($days_passed) ? '—' : $days_passed);
    $sheet->setCellValue('J' . $row, $data['obs_rec'] ?? '');
    $sheet->setCellValue('K' . $row, $estado_texto);
    
    // Aplicar color de fondo
    $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => $bgColor]
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000']
            ]
        ],
        'alignment' => [
            'vertical' => Alignment::VERTICAL_CENTER
        ]
    ]);
    
    // Ajustar altura de fila
    $sheet->getRowDimension($row)->setRowHeight(25);
    
    $row++;
    $contador++;
}

// Generar archivo
$filename = "Reclamaciones_" . date('Y-m-d_His') . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
