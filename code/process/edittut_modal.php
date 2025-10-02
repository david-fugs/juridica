<?php
session_start();
include '../../conexion.php';

$id_tut = isset($_GET['id_tut']) ? intval($_GET['id_tut']) : 0;
$row = null;
if ($id_tut > 0) {
    // Usar la columna 'nombre' que es la que existe en la tabla usuarios
    $res = $mysqli->query("SELECT t.*, u.nombre as nom_jur FROM tutelas t LEFT JOIN usuarios u ON t.doc_jur = u.documento WHERE t.id_tut = {$id_tut} LIMIT 1");
    if ($res) {
        $row = $res->fetch_assoc();
    } else {
        // registrar error para depuración (no mostrar al usuario crudo)
        error_log('edittut_modal.php - SQL error: ' . $mysqli->error);
    }
}

if (!$row) {
    echo '<div class="modal-header"><h5 class="modal-title">Editar Tutela</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    echo '<div class="modal-body"><div class="alert alert-warning">Tutela no encontrada</div></div>';
    echo '<div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div>';
    exit;
}

// Output only the fragment (modal inner HTML)
?>
<style>
.modal-lg { max-width: 1000px; }
.modal-header { background: linear-gradient(135deg, #2c3e50, #3498db); color: white; border-bottom: none; }
.modal-title { font-weight: 600; }
.modal-body { padding: 25px; background: #f8f9fa; }
.form-group label { font-weight: 500; color: #34495e; margin-bottom: 8px; }
.form-control { border: 1px solid #bdc3c7; border-radius: 6px; transition: all 0.2s; }
.form-control:focus { border-color: #3498db; box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25); }
.modal-footer { background: #ecf0f1; border-top: 1px solid #bdc3c7; }
.btn { border-radius: 6px; padding: 8px 16px; }
</style>
<div class="modal-header">
    <h5 class="modal-title">Editar Tutela</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<form id="formEditTut">
<div class="modal-body">
    <input type="hidden" name="id_tut" value="<?php echo htmlspecialchars($row['id_tut']); ?>">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Fecha</label>
            <input type="date" name="fecha_tut" class="form-control" value="<?php echo htmlspecialchars($row['fecha_tut']); ?>" required />
        </div>
        <div class="form-group col-md-6">
            <label>Tipo</label>
            <input type="text" name="tipo_tut" class="form-control" value="<?php echo htmlspecialchars($row['tipo_tut']); ?>" required />
        </div>
    </div>
    <div class="form-group">
        <label>Nombre Tutelante</label>
        <input type="text" name="nom_tut" class="form-control" value="<?php echo htmlspecialchars($row['nom_tut']); ?>" required />
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Abogado</label>
            <select id="selectJur" name="doc_jur" class="form-control" required>
                <option value="">-- Seleccione abogado --</option>
                <?php
                // Obtener todos los abogados sin filtro de estado
                $q = $mysqli->query("SELECT documento, nombre FROM usuarios ORDER BY nombre");
                if ($q) {
                    while ($u = $q->fetch_assoc()) {
                        $sel = ($row['doc_jur']==$u['documento'])? 'selected':'';
                        echo '<option value="'.htmlspecialchars($u['documento']).'" '.$sel.'>'.htmlspecialchars($u['nombre']).'</option>';
                    }
                } else {
                    // Si falla la consulta, mostrar opción de error y loguear
                    error_log('edittut_modal.php - usuarios SQL error: ' . $mysqli->error);
                    echo '<option disabled>-- Error cargando abogados --</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>Estado</label>
            <select name="estado_tut" class="form-control" required>
                <option value="">-- Seleccione --</option>
                <option value="Activa" <?php echo ($row['estado_tut']=='Activa')? 'selected':''; ?>>Activa</option>
                <option value="En proceso" <?php echo ($row['estado_tut']=='En proceso')? 'selected':''; ?>>En proceso</option>
                <option value="Resuelta" <?php echo ($row['estado_tut']=='Resuelta')? 'selected':''; ?>>Resuelta</option>
                <option value="Fallada" <?php echo ($row['estado_tut']=='Fallada')? 'selected':''; ?>>Fallada</option>
                <option value="Archivada" <?php echo ($row['estado_tut']=='Archivada')? 'selected':''; ?>>Archivada</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label>Observaciones</label>
        <textarea name="obs_tut" class="form-control" rows="4"><?php echo htmlspecialchars($row['obs_tut']); ?></textarea>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary">Guardar cambios</button>
</div>
</form>
