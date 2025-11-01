<?php
session_start();

echo "<h2>Diagnóstico de Sesión y Datos</h2>";

echo "<h3>Variables de Sesión:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (!isset($_SESSION['id'])) {
	echo "<p style='color:red;'>NO HAY SESIÓN ACTIVA - Debes iniciar sesión primero</p>";
	exit;
}

include("../../conexion.php");

$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];

echo "<h3>Información del Usuario Logueado:</h3>";
echo "<p><strong>ID:</strong> " . $id_usuario . "</p>";
echo "<p><strong>Tipo Usuario:</strong> " . var_export($tipo_usuario, true) . " (tipo: " . gettype($tipo_usuario) . ")</p>";

// Obtener datos completos del usuario desde la BD
$sql_user = "SELECT * FROM usuarios WHERE id = '$id_usuario'";
$res_user = $mysqli->query($sql_user);
if ($res_user && $res_user->num_rows > 0) {
	$user_data = $res_user->fetch_assoc();
	echo "<h3>Datos del Usuario en BD:</h3>";
	echo "<pre>";
	print_r($user_data);
	echo "</pre>";
}

// Contar registros en demandas
$sql_count = "SELECT COUNT(*) as total FROM demandas";
$res_count = $mysqli->query($sql_count);
$total = 0;
if ($res_count) {
	$row_count = $res_count->fetch_assoc();
	$total = $row_count['total'];
}
echo "<h3>Total de Demandas en BD:</h3>";
echo "<p><strong>$total</strong> registros</p>";

// Ver algunas demandas con doc_jur
echo "<h3>Muestra de Demandas (primeras 10):</h3>";
$sql_sample = "SELECT id_dem, accionante_dem, rad_dem, doc_jur, id_usu FROM demandas LIMIT 10";
$res_sample = $mysqli->query($sql_sample);
if ($res_sample && $res_sample->num_rows > 0) {
	echo "<table border='1' cellpadding='5'>";
	echo "<tr><th>ID</th><th>Accionante</th><th>Radicado</th><th>doc_jur</th><th>id_usu</th></tr>";
	while ($row = $res_sample->fetch_assoc()) {
		echo "<tr>";
		echo "<td>" . $row['id_dem'] . "</td>";
		echo "<td>" . htmlspecialchars($row['accionante_dem']) . "</td>";
		echo "<td>" . htmlspecialchars($row['rad_dem']) . "</td>";
		echo "<td>" . (empty($row['doc_jur']) ? '<span style="color:red;">VACÍO</span>' : htmlspecialchars($row['doc_jur'])) . "</td>";
		echo "<td>" . $row['id_usu'] . "</td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "<p>No hay registros para mostrar</p>";
}

// Simular el filtro como en showdemands.php
echo "<h3>Simulación del Filtro:</h3>";

$where_conditions = [];
$doc_usuario_actual = null;

echo "<p><strong>Verificando tipo_usuario:</strong> ";
if ($tipo_usuario == 1 || $tipo_usuario == '1') {
	echo "Es abogado (1) - SE APLICARÁ FILTRO</p>";
	
	$sql_doc = "SELECT documento FROM usuarios WHERE id = '$id_usuario' LIMIT 1";
	$res_doc = $mysqli->query($sql_doc);
	if ($res_doc && $res_doc->num_rows > 0) {
		$row_doc = $res_doc->fetch_assoc();
		$doc_usuario_actual = $row_doc['documento'];
		echo "<p><strong>Documento del abogado:</strong> " . $doc_usuario_actual . "</p>";
		
		$where_conditions[] = "demandas.doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'";
	}
} else if ($tipo_usuario == 2 || $tipo_usuario == '2') {
	echo "Es admin (2) - NO SE APLICA FILTRO (ve todo)</p>";
} else {
	echo "Tipo desconocido: " . var_export($tipo_usuario, true) . "</p>";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
echo "<p><strong>WHERE Clause:</strong> " . ($where_clause ? $where_clause : '(ninguno - sin filtro)') . "</p>";

// Ejecutar la query simulada
$sql_test = "SELECT COUNT(*) as total FROM demandas LEFT JOIN usuarios ON demandas.doc_jur=usuarios.documento $where_clause";
echo "<p><strong>Query de prueba:</strong> $sql_test</p>";
$res_test = $mysqli->query($sql_test);
if ($res_test) {
	$test_count = $res_test->fetch_assoc();
	echo "<p><strong>Registros que vería el usuario:</strong> " . $test_count['total'] . "</p>";
	
	if ($test_count['total'] == 0 && $total > 0) {
		echo "<p style='color:red;'><strong>PROBLEMA DETECTADO:</strong> Hay demandas en la BD pero el filtro no devuelve ninguna.</p>";
		
		if (!empty($doc_usuario_actual)) {
			// Verificar cuántas demandas tienen ese doc_jur
			$sql_check = "SELECT COUNT(*) as total FROM demandas WHERE doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'";
			$res_check = $mysqli->query($sql_check);
			$check_count = $res_check->fetch_assoc();
			echo "<p>Demandas con doc_jur = '$doc_usuario_actual': <strong>" . $check_count['total'] . "</strong></p>";
		}
	}
} else {
	echo "<p style='color:red;'>Error en query: " . $mysqli->error . "</p>";
}

echo "<hr>";
echo "<p><a href='showdemands.php'>Volver a Demandas</a> | <a href='../../access.php'>Menú Principal</a></p>";
?>
