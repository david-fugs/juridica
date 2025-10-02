<?php
session_start();
include '../../conexion.php';

$id_conc = isset($_GET['id_conc']) ? intval($_GET['id_conc']) : 0;
$row = null;
if ($id_conc > 0) {
    $res = $mysqli->query("SELECT c.*, u.nombre as nom_jur FROM conciliaciones c LEFT JOIN usuarios u ON c.doc_jur = u.documento WHERE c.id_conc = {$id_conc} LIMIT 1");
    if ($res) {
        $row = $res->fetch_assoc();
    } else {
        error_log('editconc_modal.php - SQL error: ' . $mysqli->error);
    }
}

if (!$row) {
    echo '<div class="modal-header"><h5 class="modal-title">Editar Conciliación</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    echo '<div class="modal-body"><div class="alert alert-warning">Conciliación no encontrada</div></div>';
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
    <h5 class="modal-title">Editar Conciliación</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<form id="formEditConc">
<div class="modal-body">
    <input type="hidden" name="id_conc" value="<?php echo htmlspecialchars($row['id_conc']); ?>">
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Accionante</label>
            <input type="text" name="accionante_conc" class="form-control" value="<?php echo htmlspecialchars($row['accionante_conc']); ?>" required />
        </div>
        <div class="form-group col-md-6">
            <label>Documento</label>
            <input type="text" name="doc_conc" class="form-control" value="<?php echo htmlspecialchars($row['doc_conc']); ?>" required />
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Causa/Litigio</label>
            <input type="text" name="causa_litigio_conc" class="form-control" value="<?php echo htmlspecialchars($row['causa_litigio_conc']); ?>" required />
        </div>
        <div class="form-group col-md-6">
            <label>Medio de Control</label>
            <input type="text" name="medio_control_conc" class="form-control" value="<?php echo htmlspecialchars($row['medio_control_conc']); ?>" required />
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Procuraduría</label>
            <input type="text" name="procuraduria_conc" class="form-control" value="<?php echo htmlspecialchars($row['procuraduria_conc']); ?>" required />
        </div>
        <div class="form-group col-md-6">
            <label>Radicado</label>
            <input type="text" name="rad_conc" class="form-control" value="<?php echo htmlspecialchars($row['rad_conc']); ?>" required />
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Fecha</label>
            <input type="date" name="fecha_conc" class="form-control" value="<?php echo htmlspecialchars($row['fecha_conc']); ?>" required />
        </div>
        <div class="form-group col-md-6">
            <label>Estado</label>
            <select name="estado_conc" class="form-control" required>
                <option value="">-- Seleccione --</option>
                <option value="Activa" <?php echo ($row['estado_conc']=='Activa')? 'selected':''; ?>>Activa</option>
                <option value="En proceso" <?php echo ($row['estado_conc']=='En proceso')? 'selected':''; ?>>En proceso</option>
                <option value="Resuelta" <?php echo ($row['estado_conc']=='Resuelta')? 'selected':''; ?>>Resuelta</option>
                <option value="Cerrada" <?php echo ($row['estado_conc']=='Cerrada')? 'selected':''; ?>>Cerrada</option>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label>Abogado</label>
        <select name="doc_jur" class="form-control" required>
            <option value="">-- Seleccione abogado --</option>
            <?php
            $q = $mysqli->query("SELECT documento, nombre FROM usuarios ORDER BY nombre");
            if ($q) {
                while ($u = $q->fetch_assoc()) {
                    $sel = ($row['doc_jur']==$u['documento'])? 'selected':'';
                    echo '<option value="'.htmlspecialchars($u['documento']).'" '.$sel.'>'.htmlspecialchars($u['nombre']).'</option>';
                }
            } else {
                error_log('editconc_modal.php - usuarios SQL error: ' . $mysqli->error);
                echo '<option disabled>-- Error cargando abogados --</option>';
            }
            ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Observaciones</label>
        <textarea name="obs_conc" class="form-control" rows="4"><?php echo htmlspecialchars($row['obs_conc']); ?></textarea>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary">Guardar cambios</button>
</div>
</form>
