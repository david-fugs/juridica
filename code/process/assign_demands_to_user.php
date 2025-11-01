<?php
// Script para asignar las primeras 10 demandas al documento '1' (para probar el filtro de abogado)

include("../../conexion.php");

echo "<h2>Asignar Demandas al Abogado con documento '1'</h2>";

// Actualizar las primeras 10 demandas
$sql = "UPDATE demandas SET doc_jur = '1' WHERE id_dem <= 10";

if ($mysqli->query($sql)) {
    $affected = $mysqli->affected_rows;
    echo "<h3 style='color:green;'>✓ Demandas actualizadas: $affected registros</h3>";
    echo "<p>Las primeras 10 demandas ahora están asignadas al abogado con documento '1'</p>";
    echo "<hr>";
    echo "<p><a href='showdemands.php'>Ver Demandas</a> | <a href='debug_session.php'>Ver Diagnóstico</a></p>";
} else {
    echo "<h3 style='color:red;'>✗ Error al actualizar</h3>";
    echo "<p>" . $mysqli->error . "</p>";
}

// Mostrar las demandas actualizadas
echo "<h3>Demandas asignadas al documento '1':</h3>";
$sql_check = "SELECT id_dem, accionante_dem, rad_dem, doc_jur FROM demandas WHERE doc_jur = '1' LIMIT 20";
$result = $mysqli->query($sql_check);
if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Accionante</th><th>Radicado</th><th>doc_jur</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id_dem'] . "</td>";
        echo "<td>" . htmlspecialchars($row['accionante_dem']) . "</td>";
        echo "<td>" . htmlspecialchars($row['rad_dem']) . "</td>";
        echo "<td>" . $row['doc_jur'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay demandas asignadas al documento '1'</p>";
}
?>
