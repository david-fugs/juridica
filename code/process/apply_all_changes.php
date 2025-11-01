<?php
/**
 * Script automático para aplicar TODAS las modificaciones necesarias
 * a los archivos showclaims.php, showtut.php, showconciliation.php
 */

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Aplicar Modificaciones</title>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style></head><body>";
echo "<h1>🚀 Aplicador Automático de Modificaciones</h1>";
echo "<p>Este script aplicará todas las modificaciones necesarias a los 3 archivos.</p>";
echo "<hr>";

$base_path = __DIR__ . '/';
$files_to_modify = [
    'showclaims.php' => [
        'table' => 'reclamaciones',
        'id_field' => 'id_rec',
        'name_field' => 'nom_rec',
        'date_field' => 'fecha_rec',
        'type_field' => 'reclamacion_rec',
        'rad_field' => 'rad_rec',
        'status_field' => 'est_res_rec',
        'obs_field' => 'obs_rec',
        'export_file' => 'export_claims.php',
        'mark_file' => 'markclaim.php',
        'btn_class' => 'btn-mark-done-claim',
        'title' => 'RECLAMACIONES',
        'icon' => 'building-shield',
        'add_modal' => 'addClaimModal',
        'add_label' => 'Reclamación',
        'edit_file' => 'editclaims.php',
        'delete_file' => 'deleteclaim.php'
    ],
    'showtut.php' => [
        'table' => 'tutelas',
        'id_field' => 'id_tut',
        'name_field' => 'nom_tut',
        'date_field' => 'fecha_tut',
        'type_field' => 'tipo_tut',
        'rad_field' => null, // tutelas no tiene radicado generalmente
        'status_field' => 'estado_tut',
        'obs_field' => 'obs_tut',
        'export_file' => 'export_tutelas.php',
        'mark_file' => 'marktutela.php',
        'btn_class' => 'btn-mark-done-tut',
        'title' => 'TUTELAS',
        'icon' => 'gavel',
        'add_modal' => 'addTutModal',
        'add_label' => 'Tutela',
        'edit_file' => 'edittut.php',
        'delete_file' => 'deletetut.php'
    ],
    'showconciliation.php' => [
        'table' => 'conciliaciones',
        'id_field' => 'id_conc',
        'name_field' => 'nom_conc',
        'date_field' => 'fecha_conc',
        'type_field' => 'tipo_conc',
        'rad_field' => 'rad_conc',
        'status_field' => 'estado_conc',
        'obs_field' => 'obs_conc',
        'export_file' => 'export_conciliaciones.php',
        'mark_file' => 'markconciliation.php',
        'btn_class' => 'btn-mark-done-conc',
        'title' => 'CONCILIACIONES',
        'icon' => 'handshake',
        'add_modal' => 'addConcModal',
        'add_label' => 'Conciliación',
        'edit_file' => 'editconc.php',
        'delete_file' => 'deleteconc.php'
    ]
];

foreach ($files_to_modify as $filename => $config) {
    echo "<h2>📝 Procesando: $filename</h2>";
    
    $file_path = $base_path . $filename;
    
    if (!file_exists($file_path)) {
        echo "<p class='error'>✗ Archivo no encontrado: $filename</p>";
        continue;
    }
    
    // Crear backup
    $backup_path = $base_path . str_replace('.php', '_backup_' . date('Ymd_His') . '.php', $filename);
    if (copy($file_path, $backup_path)) {
        echo "<p class='success'>✓ Backup creado: " . basename($backup_path) . "</p>";
    } else {
        echo "<p class='error'>✗ No se pudo crear backup</p>";
        continue;
    }
    
    $content = file_get_contents($file_path);
    
    // Aplicar modificaciones
    $modifications_applied = 0;
    
    // 1. Agregar filtro de estado en el formulario
    if (strpos($content, 'name="estado"') === false) {
        $search_pattern = '/<div class="form-group mx-2">\s*<button type="submit" class="btn btn-success">/';
        $replacement = '<div class="form-group mx-2">
                    <select name="estado" class="form-control">
                        <option value="">Todos los estados</option>
                        <option value="activa" <?php echo (isset($_GET[\'estado\']) && $_GET[\'estado\'] == \'activa\') ? \'selected\' : \'\'; ?>>Activas</option>
                        <option value="realizada" <?php echo (isset($_GET[\'estado\']) && $_GET[\'estado\'] == \'realizada\') ? \'selected\' : \'\'; ?>>Realizadas</option>
                    </select>
                </div>
                <div class="form-group mx-2">
                    <button type="submit" class="btn btn-success">';
        
        $content = preg_replace($search_pattern, $replacement, $content);
        if ($content) {
            $modifications_applied++;
            echo "<p class='info'>→ Filtro de estado agregado al formulario</p>";
        }
    }
    
    // 2. Agregar variable $estado_filter
    if (strpos($content, '$estado_filter') === false) {
        $search = '@$nom_jur = ($_GET[\'nom_jur\']);';
        $replacement = '@$nom_jur = ($_GET[\'nom_jur\']);
    @$estado_filter = isset($_GET[\'estado\']) ? $_GET[\'estado\'] : \'\';';
        $content = str_replace($search, $replacement, $content);
        $modifications_applied++;
        echo "<p class='info'>→ Variable \$estado_filter agregada</p>";
    }
    
    // 3. Agregar filtro de estado en WHERE
    $table_name = $config['table'];
    if (strpos($content, "$table_name.realizada") === false) {
        // Buscar donde está el filtro por doc_jur y agregar después
        $search_pattern = "/if \(\\\$tipo_usuario == 1[^}]+}/s";
        if (preg_match($search_pattern, $content, $matches)) {
            $replacement = $matches[0] . "\n    \n    // Filtro por estado realizada\n    if (\$estado_filter === 'realizada') {\n        \$where_conditions[] = \"$table_name.realizada = 1\";\n    } elseif (\$estado_filter === 'activa') {\n        \$where_conditions[] = \"($table_name.realizada = 0 OR $table_name.realizada IS NULL)\";\n    }";
            $content = str_replace($matches[0], $replacement, $content);
            $modifications_applied++;
            echo "<p class='info'>→ Filtro de estado agregado al WHERE</p>";
        }
    }
    
    // 4. Agregar botón Exportar a Excel
    $export_file = $config['export_file'];
    if (strpos($content, $export_file) === false) {
        $add_modal = $config['add_modal'];
        $search = '<button class="btn btn-success" data-toggle="modal" data-target="#' . $add_modal . '">';
        $replacement = '<div>
                    <a href="' . $export_file . '?<?php echo http_build_query($_GET); ?>" class="btn btn-success">
                        <i class="fa-solid fa-file-excel"></i> Exportar a Excel
                    </a>
                    <button class="btn btn-success ml-2" data-toggle="modal" data-target="#' . $add_modal . '">';
        $content = str_replace($search, $replacement, $content);
        $modifications_applied++;
        echo "<p class='info'>→ Botón Exportar a Excel agregado</p>";
    }
    
    // 5. Agregar JavaScript para marcar como realizada
    $btn_class = $config['btn_class'];
    $mark_file = $config['mark_file'];
    $id_field = $config['id_field'];
    
    if (strpos($content, $btn_class) === false) {
        $js_code = "\n\n    // Marcar como realizada\n    \$(document).on('click', '.$btn_class', function(e){\n        e.preventDefault();\n        var id = \$(this).data('id');\n        Swal.fire({\n            title: '¿Marcar como realizada?',\n            text: 'Confirmar que está realizada.',\n            icon: 'question',\n            showCancelButton: true,\n            confirmButtonText: 'Sí, marcar',\n            cancelButtonText: 'Cancelar'\n        }).then(function(result){\n            if(result.isConfirmed){\n                \$.post('$mark_file', { $id_field: id }, function(resp){\n                    if(resp && resp.success){\n                        Swal.fire('Actualizado', resp.message, 'success').then(function(){\n                            location.reload();\n                        });\n                    } else {\n                        Swal.fire('Error', (resp && resp.message) ? resp.message : 'Error al marcar', 'error');\n                    }\n                }, 'json').fail(function(){\n                    Swal.fire('Error', 'Error en la petición', 'error');\n                });\n            }\n        });\n    });\n";
        
        // Buscar donde está el script de eliminar y agregar después
        $search = '});' . "\n\t\t});\n\t</script>";
        $replacement = '});' . $js_code . "\n\t\t});\n\t</script>";
        $content = str_replace($search, $replacement, $content);
        $modifications_applied++;
        echo "<p class='info'>→ JavaScript para marcar realizada agregado</p>";
    }
    
    // Guardar archivo modificado
    if (file_put_contents($file_path, $content)) {
        echo "<p class='success'>✓ Archivo actualizado exitosamente ($modifications_applied modificaciones aplicadas)</p>";
    } else {
        echo "<p class='error'>✗ Error al guardar el archivo</p>";
    }
    
    echo "<hr>";
}

echo "<h2>✅ Proceso Completado</h2>";
echo "<p>Se han aplicado las modificaciones básicas. Ahora debes:</p>";
echo "<ol>";
echo "<li>Agregar manualmente las columnas 'Auto Admisorio' y 'Días Transcurridos' en las tablas HTML</li>";
echo "<li>Agregar el cálculo de días y colores de fondo en el while de registros</li>";
echo "<li>Agregar el botón 'Marcar realizada' en la columna de acciones</li>";
echo "<li>Modificar el SELECT para incluir los campos auto_admisorio y realizada</li>";
echo "</ol>";
echo "<p><a href='GUIA_MODIFICACIONES_EXACTAS.md'>Ver guía completa de modificaciones</a></p>";
echo "</body></html>";
?>
