<?php
session_start();
include '../../conexion.php';

$tipo_usuario = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : null;

// Obtener documento del usuario actual si es abogado (tipo_usuario = 1)
$doc_usuario_actual = null;
if ($tipo_usuario == 1 && isset($_SESSION['id'])) {
    $id_usuario = intval($_SESSION['id']);
    $res_doc = $mysqli->query("SELECT documento FROM usuarios WHERE id = {$id_usuario} LIMIT 1");
    if ($res_doc && $res_doc->num_rows > 0) {
        $row_doc = $res_doc->fetch_assoc();
        $doc_usuario_actual = $row_doc['documento'];
    }
}

$id_dem = isset($_GET['id_dem']) ? intval($_GET['id_dem']) : 0;
$row = null;
if ($id_dem > 0) {
    $res = $mysqli->query("SELECT * FROM demandas WHERE id_dem = {$id_dem} LIMIT 1");
    if ($res) {
        $row = $res->fetch_assoc();
    } else {
        error_log('editdemands_modal.php - SQL error: ' . $mysqli->error);
    }
}

if (!$row) {
    echo '<div class="modal-header"><h5 class="modal-title">Editar Demanda</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    echo '<div class="modal-body"><div class="alert alert-warning">Demanda no encontrada</div></div>';
    echo '<div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div>';
    exit;
}
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
    <h5 class="modal-title"><i class="fa-solid fa-pen"></i> Editar Demanda</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<form id="formEditDemand">
<div class="modal-body">
    <input type="hidden" name="id_dem" value="<?php echo htmlspecialchars($row['id_dem']); ?>">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Fecha Oficina <span class="text-danger">*</span></label>
                <input type="date" name="fecha_dem" class="form-control" value="<?php echo htmlspecialchars($row['fecha_dem']); ?>" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Accionante | Demandante <span class="text-danger">*</span></label>
                <input type="text" name="accionante_dem" class="form-control" style="text-transform:uppercase;" value="<?php echo htmlspecialchars($row['accionante_dem']); ?>" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Documento</label>
                <input type="text" name="doc_dem" class="form-control" style="text-transform:uppercase;" value="<?php echo htmlspecialchars($row['doc_dem']); ?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label>Radicación <span class="text-danger">*</span></label>
                <input type="text" name="rad_dem" class="form-control" value="<?php echo htmlspecialchars($row['rad_dem']); ?>" required>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group">
                <label>Despacho Judicial</label>
                <input type="text" name="desp_judi_dem" class="form-control" value="<?php echo htmlspecialchars($row['desp_judi_dem']); ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Estado Actual del Proceso</label>
        <textarea name="est_act_proc_dem" class="form-control" style="text-transform:uppercase;" rows="3"><?php echo htmlspecialchars($row['est_act_proc_dem']); ?></textarea>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label>Abogado Asignado <span class="text-danger">*</span></label>
                <select name="doc_jur" id="selectJur" class="form-control" <?php echo ($tipo_usuario == 1) ? 'disabled' : ''; ?>>
                    <option value="">Seleccione un abogado</option>
                    <?php
                    // Filtramos por tipo_usuario = 1 (abogados)
                    $consulta_jur = "SELECT documento, nombre FROM usuarios WHERE tipo_usuario = '1' ORDER BY nombre ASC";
                    $res_jur = $mysqli->query($consulta_jur);
                    if ($res_jur) {
                        while ($row_jur = $res_jur->fetch_assoc()) {
                            $sel = ($row['doc_jur'] == $row_jur['documento']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($row_jur['documento']) . '" ' . $sel . '>' . htmlspecialchars($row_jur['nombre']) . '</option>';
                        }
                    }
                    ?>
                </select>
                <?php if ($tipo_usuario == 1): ?>
                    <input type="hidden" name="doc_jur" value="<?php echo htmlspecialchars($row['doc_jur']); ?>">
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Interno</label>
                <input type="text" name="interno_dem" class="form-control" style="text-transform:uppercase;" value="<?php echo htmlspecialchars($row['interno_dem']); ?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Auto admisorio (fecha)</label>
                <input type="date" name="auto_admisorio" class="form-control" value="<?php echo isset($row['auto_admisorio']) ? htmlspecialchars($row['auto_admisorio']) : ''; ?>">
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-center">
            <div class="form-group form-check mt-4">
                <input type="checkbox" name="realizada" id="edit_realizada" class="form-check-input" value="1" <?php echo (isset($row['realizada']) && $row['realizada'] == 1) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="edit_realizada">Realizada</label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Observaciones y/o Comentarios Adicionales</label>
        <textarea name="obs_dem" class="form-control" style="text-transform:uppercase;" rows="2"><?php echo htmlspecialchars($row['obs_dem']); ?></textarea>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Guardar cambios</button>
</div>
</form>
