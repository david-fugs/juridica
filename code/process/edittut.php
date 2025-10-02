<?php
session_start();
if (!isset($_SESSION['id'])) { 
    header('Location: ../../index.php'); 
    exit; 
}

include '../../conexion.php';

$id_tut = isset($_GET['id_tut']) ? intval($_GET['id_tut']) : 0;
$row = null;
if ($id_tut > 0) {
    $res = $mysqli->query("SELECT t.*, u.usuario as nom_jur FROM tutelas t LEFT JOIN usuarios u ON t.doc_jur = u.documento WHERE t.id_tut = {$id_tut} LIMIT 1");
    if ($res) $row = $res->fetch_assoc();
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Editar Tutela</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="../../js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-3">
        <h3>Editar Tutela</h3>
        <div class="card mt-2 shadow-sm">
            <div class="card-body">
                <?php if (!$row): ?>
                    <div class="alert alert-warning">Tutela no encontrada</div>
                    <a href="showtut.php" class="btn btn-secondary">Volver</a>
                <?php else: ?>
                    <form id="formEditTut">
                        <input type="hidden" name="id_tut" value="<?php echo htmlspecialchars($row['id_tut']); ?>" />

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Fecha</label>
                                <input type="date" name="fecha_tut" class="form-control" value="<?php echo htmlspecialchars($row['fecha_tut']); ?>" required />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tipo</label>
                                <select name="tipo_tut" class="form-control" required>
                                    <option value="">-- Seleccionar --</option>
                                    <option value="Ordinaria" <?php echo ($row['tipo_tut']=='Ordinaria')? 'selected':''; ?>>Ordinaria</option>
                                    <option value="Impugnación" <?php echo ($row['tipo_tut']=='Impugnación')? 'selected':''; ?>>Impugnación</option>
                                    <option value="Revisión" <?php echo ($row['tipo_tut']=='Revisión')? 'selected':''; ?>>Revisión</option>
                                    <option value="Incidente" <?php echo ($row['tipo_tut']=='Incidente')? 'selected':''; ?>>Incidente</option>
                                </select>
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
                                    $q = $mysqli->query("SELECT documento, usuario FROM usuarios WHERE estado='Activo' ORDER BY usuario");
                                    while ($u = $q->fetch_assoc()) {
                                        $sel = ($row['doc_jur']==$u['documento'])? 'selected':'';
                                        echo '<option value="'.htmlspecialchars($u['documento']).'" '.$sel.'>'.htmlspecialchars($u['usuario']).'</option>';
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

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="showtut.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    jQuery(function($){
        if (jQuery().select2) {
            $('#selectJur').select2({ width: '100%' });
        }

        $('#formEditTut').on('submit', function(e){
            e.preventDefault();
            var form = this;
            if (!form.checkValidity()) { form.reportValidity(); return; }

            Swal.fire({ title: 'Actualizar tutela?', icon: 'question', showCancelButton: true, confirmButtonText: 'Actualizar' })
            .then(function(res){
                if (!res.isConfirmed) return;
                $.ajax({ url: 'edittut1.php', method: 'POST', data: $('#formEditTut').serialize(), dataType: 'json' })
                .done(function(resp){
                    if (resp && resp.success) { 
                        Swal.fire('Actualizado', resp.message, 'success').then(function(){ window.location.href = 'showtut.php'; }); 
                    } else { 
                        Swal.fire('Error', resp.message || 'Error desconocido', 'error'); 
                    }
                }).fail(function(jqXHR){ 
                    Swal.fire('Error de red', 'Estado: '+jqXHR.status+' Respuesta: '+jqXHR.responseText, 'error'); 
                });
            });
        });
    });
    </script>
</body>
</html>
<?php
ini_set('display_errors',0);
error_reporting(0);
header('Content-Type: application/json');
include '../../conexion.php';

$id_tut = intval($_GET['id']);

// Query para obtener datos de la tutela usando estructura real
$sql = "SELECT t.*, u.usuario 
        FROM tutelas t 
        LEFT JOIN usuarios u ON t.doc_jur = u.documento 
        WHERE t.id_tut = {$id_tut}";

$result = $mysqli->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    ?>
    <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-edit text-primary"></i> Editar Tutela</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="editTutelaForm">
            <input type="hidden" name="id_tut" value="<?php echo $row['id_tut']; ?>">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha_tut"><i class="far fa-calendar-alt"></i> Fecha:</label>
                        <input type="date" class="form-control" name="fecha_tut" value="<?php echo $row['fecha_tut']; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tipo_tut"><i class="fas fa-tag"></i> Tipo de Tutela:</label>
                        <select name="tipo_tut" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <option value="Ordinaria" <?php echo ($row['tipo_tut'] == 'Ordinaria') ? 'selected' : ''; ?>>Ordinaria</option>
                            <option value="Impugnación" <?php echo ($row['tipo_tut'] == 'Impugnación') ? 'selected' : ''; ?>>Impugnación</option>
                            <option value="Revisión" <?php echo ($row['tipo_tut'] == 'Revisión') ? 'selected' : ''; ?>>Revisión</option>
                            <option value="Incidente" <?php echo ($row['tipo_tut'] == 'Incidente') ? 'selected' : ''; ?>>Incidente</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="nom_tut"><i class="fas fa-user"></i> Nombre del Tutelante:</label>
                <input type="text" class="form-control" name="nom_tut" value="<?php echo htmlspecialchars($row['nom_tut']); ?>" required>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="doc_jur"><i class="fas fa-user-tie"></i> Abogado:</label>
                        <select name="doc_jur" class="form-control" required>
                            <option value="">Seleccionar abogado...</option>
                            <?php
                            $usuarios_sql = "SELECT documento, usuario FROM usuarios WHERE estado = 'Activo' ORDER BY usuario";
                            $usuarios_result = $mysqli->query($usuarios_sql);
                            if ($usuarios_result) {
                                while ($usuario = $usuarios_result->fetch_assoc()) {
                                    $selected = ($row['doc_jur'] == $usuario['documento']) ? 'selected' : '';
                                    echo "<option value='{$usuario['documento']}' {$selected}>{$usuario['usuario']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="estado_tut"><i class="fas fa-info-circle"></i> Estado:</label>
                        <select name="estado_tut" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <option value="Activa" <?php echo ($row['estado_tut'] == 'Activa') ? 'selected' : ''; ?>>Activa</option>
                            <option value="En proceso" <?php echo ($row['estado_tut'] == 'En proceso') ? 'selected' : ''; ?>>En proceso</option>
                            <option value="Resuelta" <?php echo ($row['estado_tut'] == 'Resuelta') ? 'selected' : ''; ?>>Resuelta</option>
                            <option value="Fallada" <?php echo ($row['estado_tut'] == 'Fallada') ? 'selected' : ''; ?>>Fallada</option>
                            <option value="Archivada" <?php echo ($row['estado_tut'] == 'Archivada') ? 'selected' : ''; ?>>Archivada</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="obs_tut"><i class="fas fa-comments"></i> Observaciones:</label>
                <textarea class="form-control" name="obs_tut" rows="4"><?php echo htmlspecialchars($row['obs_tut']); ?></textarea>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i> Cancelar
        </button>
        <button type="button" class="btn btn-primary" onclick="updateTutela()">
            <i class="fas fa-save"></i> Guardar Cambios
        </button>
    </div>
    <?php
} else {
    echo json_encode(['error' => 'Tutela no encontrada']);
}
?>
        if (jQuery().select2) {
            $('#selectJur').select2({ width: '100%' });
        }

        // Intercept form submit and send via AJAX
        $('#formEditTut').on('submit', function(e){
            e.preventDefault();
            var form = this;
            if (!form.checkValidity()) { 
                form.reportValidity(); 
                return; 
            }
            
            Swal.fire({ 
                title: 'Actualizar tutela?', 
                icon: 'question', 
                showCancelButton: true, 
                confirmButtonText: 'Actualizar' 
            }).then(function(res){
                if (!res.isConfirmed) return;
                
                $.ajax({ 
                    url: 'edittut1.php', 
                    method: 'POST', 
                    data: $('#formEditTut').serialize(), 
                    dataType: 'json' 
                }).done(function(resp){
                    if (resp && resp.success) { 
                        Swal.fire('Actualizado', resp.message, 'success').then(function(){ 
                            window.location.href = 'showtut.php'; 
                        }); 
                    } else { 
                        Swal.fire('Error', resp.message || 'Error desconocido', 'error'); 
                    }
                }).fail(function(jqXHR){ 
                    Swal.fire('Error de red', 'Estado: '+jqXHR.status+' Respuesta: '+jqXHR.responseText, 'error'); 
                });
            });
        });
    });
    </script>
</body>
</html>