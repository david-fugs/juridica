<?php
session_start();
if(!isset($_SESSION['id'])){
    header("Location: ../../index.php");
    exit;
}
$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>JURÍDICA - Conciliaciones</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .container-fluid { max-width: 1700px; margin: 0 auto; padding: 20px; }
        .page-header { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; }
        .page-header img { max-width: 600px; height: auto; }
        .page-title { color: #2c3e50; margin-top: 15px; font-weight: 600; }
        .search-card { background: white; border-radius: 10px; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .search-card h5 { color: #2c3e50; margin-bottom: 20px; font-weight: 600; }
        .table-card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .table-responsive { overflow-x: auto; }
        .table { margin-bottom: 0; }
        .table thead th { background: linear-gradient(135deg, #2c3e50, #34495e); color: white; border: none; padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; white-space: nowrap; }
        .table tbody tr { transition: all 0.2s; }
        .table tbody tr:hover { background-color: #f8f9fa; transform: translateX(2px); }
        .table tbody td { padding: 12px; vertical-align: middle; border-bottom: 1px solid #ecf0f1; }
        .btn-action { padding: 5px 10px; margin: 0 2px; border-radius: 5px; transition: all 0.2s; }
        .btn-action:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
        .btn-create-large { padding: 10px 18px; font-size: 1.05rem; font-weight: 600; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s; }
        .btn-create-large:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,0,0,0.2); }
        .badge { padding: 6px 12px; font-size: 0.85rem; font-weight: 500; }
        .pagination { margin-top: 20px; }
        .page-link { color: #2c3e50; border-radius: 5px; margin: 0 3px; }
        .page-link:hover { background-color: #3498db; color: white; }
        .page-item.active .page-link { background-color: #2c3e50; border-color: #2c3e50; }
        .btn-back { position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.3); transition: all 0.3s; z-index: 1000; }
        .btn-back:hover { transform: scale(1.1); box-shadow: 0 6px 16px rgba(0,0,0,0.4); color: white; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <img src="img/logo_educacion.png" alt="Logo Educación">
            <h2 class="page-title"><i class="fas fa-handshake"></i> GESTIÓN DE CONCILIACIONES</h2>
        </div>

        <div class="search-card">
            <h5><i class="fas fa-search"></i> Filtros de Búsqueda</h5>
            <form method="get" action="showconciliation.php">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Accionante</label>
                            <input type="text" name="accionante_conc" class="form-control" value="<?php echo isset($_GET['accionante_conc']) ? htmlspecialchars($_GET['accionante_conc']) : ''; ?>" placeholder="Buscar por accionante">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Documento</label>
                            <input type="text" name="doc_conc" class="form-control" value="<?php echo isset($_GET['doc_conc']) ? htmlspecialchars($_GET['doc_conc']) : ''; ?>" placeholder="Buscar por documento">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Abogado</label>
                            <input type="text" name="nom_jur" class="form-control" value="<?php echo isset($_GET['nom_jur']) ? htmlspecialchars($_GET['nom_jur']) : ''; ?>" placeholder="Buscar por abogado">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                        <a href="showconciliation.php" class="btn btn-secondary"><i class="fas fa-eraser"></i> Limpiar</a>
                        <button type="button" class="btn btn-success btn-create-large" data-toggle="modal" data-target="#modalAddConc"><i class="fas fa-plus-circle"></i> Crear Conciliación</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-card">
            <?php
            include("conexion.php");
            
            $accionante_conc = isset($_GET['accionante_conc']) ? trim($_GET['accionante_conc']) : '';
            $doc_conc = isset($_GET['doc_conc']) ? trim($_GET['doc_conc']) : '';
            $nom_jur = isset($_GET['nom_jur']) ? trim($_GET['nom_jur']) : '';
            
            $where = "WHERE 1=1";
            if ($accionante_conc !== '') {
                $where .= " AND c.accionante_conc LIKE '%".mysqli_real_escape_string($mysqli, $accionante_conc)."%'";
            }
            if ($doc_conc !== '') {
                $where .= " AND c.doc_conc LIKE '%".mysqli_real_escape_string($mysqli, $doc_conc)."%'";
            }
            if ($nom_jur !== '') {
                $where .= " AND u.nombre LIKE '%".mysqli_real_escape_string($mysqli, $nom_jur)."%'";
            }
            
            $per_page = 25;
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            if ($page < 1) $page = 1;
            $offset = ($page - 1) * $per_page;
            
            $count_query = "SELECT COUNT(*) as total FROM conciliaciones c LEFT JOIN usuarios u ON c.doc_jur = u.documento {$where}";
            $count_res = $mysqli->query($count_query);
            $total_rows = ($count_res) ? $count_res->fetch_assoc()['total'] : 0;
            $total_pages = ceil($total_rows / $per_page);
            
            $query = "SELECT c.*, u.nombre as nom_jur FROM conciliaciones c LEFT JOIN usuarios u ON c.doc_jur = u.documento {$where} ORDER BY c.fecha_conc DESC LIMIT {$per_page} OFFSET {$offset}";
            $result = $mysqli->query($query);
            ?>
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="fas fa-list"></i> Listado de Conciliaciones (<?php echo $total_rows; ?> registros)</h5>
                <span class="text-muted">Página <?php echo $page; ?> de <?php echo $total_pages; ?></span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Fecha</th>
                            <th>Accionante</th>
                            <th>Documento</th>
                            <th>Causa/Litigio</th>
                            <th>Medio Control</th>
                            <th>Procuraduría</th>
                            <th>Radicado</th>
                            <th>Abogado</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            $num = $offset + 1;
                            while ($row = $result->fetch_assoc()) {
                                $estado_badge = 'secondary';
                                if ($row['estado_conc'] == 'Activa') $estado_badge = 'success';
                                elseif ($row['estado_conc'] == 'En proceso') $estado_badge = 'warning';
                                elseif ($row['estado_conc'] == 'Resuelta') $estado_badge = 'info';
                                elseif ($row['estado_conc'] == 'Cerrada') $estado_badge = 'dark';
                                
                                echo '<tr>';
                                echo '<td>'.$num++.'</td>';
                                echo '<td>'.htmlspecialchars($row['fecha_conc']).'</td>';
                                echo '<td>'.htmlspecialchars($row['accionante_conc']).'</td>';
                                echo '<td>'.htmlspecialchars($row['doc_conc']).'</td>';
                                echo '<td>'.htmlspecialchars($row['causa_litigio_conc']).'</td>';
                                echo '<td>'.htmlspecialchars($row['medio_control_conc']).'</td>';
                                echo '<td>'.htmlspecialchars($row['procuraduria_conc']).'</td>';
                                echo '<td>'.htmlspecialchars($row['rad_conc']).'</td>';
                                echo '<td>'.htmlspecialchars($row['nom_jur']).'</td>';
                                echo '<td><span class="badge badge-'.$estado_badge.'">'.htmlspecialchars($row['estado_conc']).'</span></td>';
                                echo '<td>';
                                echo '<button class="btn btn-sm btn-primary btn-action open-edit-conc" data-id="'.$row['id_conc'].'" title="Editar"><i class="fas fa-edit"></i></button>';
                                echo '<button class="btn btn-sm btn-danger btn-action delete-conc" data-id="'.$row['id_conc'].'" title="Eliminar"><i class="fas fa-trash"></i></button>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="11" class="text-center text-muted">No se encontraron conciliaciones</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($total_pages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    $query_params = $_GET;
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $query_params['page'] = $i;
                        $active = ($i == $page) ? 'active' : '';
                        echo '<li class="page-item '.$active.'"><a class="page-link" href="?'.http_build_query($query_params).'">'.$i.'</a></li>';
                    }
                    ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>

    <a href="../../access.php" class="btn-back" title="Regresar">
        <i class="fas fa-arrow-left fa-lg"></i>
    </a>

    <!-- Modal Crear Conciliación -->
    <div class="modal fade" id="modalAddConc" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
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
                    <h5 class="modal-title">Crear Nueva Conciliación</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="formAddConc">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Accionante *</label>
                            <input type="text" name="accionante_conc" class="form-control" required />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Documento *</label>
                            <input type="text" name="doc_conc" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Causa/Litigio *</label>
                            <input type="text" name="causa_litigio_conc" class="form-control" required />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Medio de Control *</label>
                            <input type="text" name="medio_control_conc" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Procuraduría *</label>
                            <input type="text" name="procuraduria_conc" class="form-control" required />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Radicado *</label>
                            <input type="text" name="rad_conc" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Fecha *</label>
                            <input type="date" name="fecha_conc" class="form-control" required />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Estado *</label>
                            <select name="estado_conc" class="form-control" required>
                                <option value="">-- Seleccione --</option>
                                <option value="Activa">Activa</option>
                                <option value="En proceso">En proceso</option>
                                <option value="Resuelta">Resuelta</option>
                                <option value="Cerrada">Cerrada</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Abogado *</label>
                        <select name="doc_jur" class="form-control" required>
                            <option value="">-- Seleccione abogado --</option>
                            <?php
                            $usuarios_res = $mysqli->query("SELECT documento, nombre FROM usuarios ORDER BY nombre");
                            if ($usuarios_res) {
                                while ($u = $usuarios_res->fetch_assoc()) {
                                    echo '<option value="'.htmlspecialchars($u['documento']).'">'.htmlspecialchars($u['nombre']).'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="obs_conc" class="form-control" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Crear Conciliación</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Conciliación -->
    <div class="modal fade" id="modalEditConc" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="editConcModalContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        // Crear Conciliación
        $('#formAddConc').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'code/process/addconc.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de comunicación con el servidor'
                    });
                }
            });
        });

        // Abrir modal editar
        $('.open-edit-conc').on('click', function() {
            const id = $(this).data('id');
            $.ajax({
                url: 'code/process/editconc_modal.php?id_conc=' + id,
                type: 'GET',
                success: function(html) {
                    $('#editConcModalContent').html(html);
                    $('#modalEditConc').modal('show');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar el formulario de edición'
                    });
                }
            });
        });

        // Editar Conciliación (delegated event)
        $(document).on('submit', '#formEditConc', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'code/process/editconc1.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de comunicación con el servidor'
                    });
                }
            });
        });

        // Eliminar Conciliación
        $('.delete-conc').on('click', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Está seguro?',
                text: "Esta acción eliminará la conciliación permanentemente",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'code/process/deleteconc.php',
                        type: 'POST',
                        data: { id_conc: id },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error de comunicación con el servidor'
                            });
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>