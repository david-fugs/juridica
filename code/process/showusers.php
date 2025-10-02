<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
}

$usuario      = $_SESSION['usuario'];
$nombre       = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>JURIDICA - Consultar Usuarios</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>

    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }

        .selector-for-some-widget {
            box-sizing: content-box;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Unified, subtle color palette and button styles */
        .badge-role {
            font-size: 0.85rem;
            padding: 0.4em 0.6em;
            border-radius: 0.6rem;
            color: #fff;
        }

        .badge-admin {
            background: #2c3e50;
            /* dark slate */
        }

        .badge-lawyer {
            background: #7f8c8d;
            /* gray */
        }

        /* Action buttons: neutral outline, hover filled */
        .btn-action {
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.9rem;
            border: 1px solid #ced4da;
            background: transparent;
            color: #2c3e50;
            transition: all 0.12s ease-in-out;
            margin-right: 6px;
        }

        .btn-action:hover {
            background: #ecf0f1;
            transform: translateY(-1px);
        }

        .btn-action .fa-solid {
            margin-right: 6px;
        }

        .btn-edit-custom {
            border-color: #2c7be5;
            color: #2c7be5;
        }

        .btn-edit-custom:hover {
            background: #2c7be5;
            color: #fff;
        }

        .btn-delete-custom {
            border-color: #e74c3c;
            color: #e74c3c;
        }

        .btn-delete-custom:hover {
            background: #e74c3c;
            color: #fff;
        }

        /* Reduce number of distinct colors used globally in this page */
        .card-header.bg-primary {
            background: #2c3e50 !important;
        }

        .btn-success {
            background: #16a085;
            border-color: #16a085;
        }

        /* Modal modern styles */
        :root {
            --modal-accent: #16a085;
            --modal-primary: #2c3e50;
        }

        .modal-content {
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(44, 62, 80, 0.12);
            border: none;
        }

        .modal-header {
            background: linear-gradient(90deg, var(--modal-primary), #243447);
            color: #fff;
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }

        .modal .form-control {
            border-radius: 8px;
        }

        #btnSaveUser {
            background: var(--modal-accent);
            border-color: var(--modal-accent);
            color: #fff;
            border-radius: 8px;
        }

        #btnSaveUser:hover {
            background: #13907a;
        }
    </style>
</head>

<body>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>

    <center>
        <img src='../../img/gobersecre.png' width=437 height=206 class='responsive'>
    </center>

    <section class="principal">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div align="center">
                        <h1 style="color: #412fd1; text-shadow: #FFFFFF 0.1em 0.1em 0.2em">
                            <b><i class="fa-solid fa-users"></i> CONSULTAR USUARIOS</b>
                        </h1>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white">
                            <h5><i class="fa-solid fa-search"></i> Búsqueda de Usuarios</h5>
                        </div>
                        <div class="card-body">
                            <form action="showusers.php" method="get" class="form-inline justify-content-center">
                                <div class="form-group mx-2">
                                    <input name="nombre" type="text" class="form-control" placeholder="Buscar por nombre" size=30
                                        value="<?php echo isset($_GET['nombre']) ? $_GET['nombre'] : ''; ?>">
                                </div>
                                <div class="form-group mx-2">
                                    <input name="usuario" type="text" class="form-control" placeholder="Buscar por usuario" size=20
                                        value="<?php echo isset($_GET['usuario']) ? $_GET['usuario'] : ''; ?>">
                                </div>
                                <div class="form-group mx-2">
                                    <select name="tipo_usuario" class="form-control">
                                        <option value="">Todos los tipos</option>
                                        <option value="1" <?php echo (isset($_GET['tipo_usuario']) && $_GET['tipo_usuario'] == '1') ? 'selected' : ''; ?>>Administrador</option>
                                        <option value="2" <?php echo (isset($_GET['tipo_usuario']) && $_GET['tipo_usuario'] == '2') ? 'selected' : ''; ?>>Abogado</option>
                                    </select>
                                </div>
                                <div class="form-group mx-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa-solid fa-search"></i> Buscar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php

                    date_default_timezone_set("America/Bogota");
                    include("../../conexion.php");

                    @$buscar_nombre = ($_GET['nombre']);
                    @$buscar_usuario = ($_GET['usuario']);
                    @$buscar_tipo = ($_GET['tipo_usuario']);

                    $query = "SELECT id, usuario, nombre, tipo_usuario, documento, email, area, responsabilidades, observaciones FROM usuarios WHERE 
	          (nombre LIKE '%" . $buscar_nombre . "%') AND 
	          (usuario LIKE '%" . $buscar_usuario . "%')";

                    if (!empty($buscar_tipo)) {
                        $query .= " AND (tipo_usuario = '" . $buscar_tipo . "')";
                    }

                    $query .= " ORDER BY nombre ASC";

                    $res = $mysqli->query($query);
                    $num_registros = mysqli_num_rows($res);

                    if ($num_registros > 0) {
                    ?>
                        <div class="table-container">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-primary">
                                    <i class="fa-solid fa-list"></i> Resultados encontrados: <?php echo $num_registros; ?>
                                </h5>
                                <a href="addusers.php" class="btn btn-success">
                                    <i class="fa-solid fa-user-plus"></i> Crear Nuevo Usuario
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th><i class="fa-solid fa-user"></i> Usuario</th>
                                            <th><i class="fa-solid fa-id-card"></i> Nombre Completo</th>
                                            <th><i class="fa-solid fa-user-tag"></i> Tipo de Usuario</th>
                                            <th><i class="fa-solid fa-gear"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 1;
                                        while ($row = $res->fetch_assoc()) {
                                            if ($row['tipo_usuario'] == 1) {
                                                $tipo_texto = '<span class="badge-role badge-admin">Administrador</span>';
                                            } else {
                                                $tipo_texto = '<span class="badge-role badge-lawyer">Abogado</span>';
                                            }
                                        ?>
                                            <tr>
                                                <td><?php echo $contador; ?></td>
                                                <td><strong><?php echo $row['usuario']; ?></strong></td>
                                                <td><?php echo $row['nombre']; ?></td>
                                                <td><?php echo $tipo_texto; ?></td>
                                                <td>
                                                    <button class="btn-action btn-edit-custom btn-edit"
                                                        data-id="<?php echo $row['id']; ?>"
                                                        data-usuario="<?php echo htmlspecialchars($row['usuario'], ENT_QUOTES); ?>"
                                                        data-nombre="<?php echo htmlspecialchars($row['nombre'], ENT_QUOTES); ?>"
                                                        data-tipo="<?php echo $row['tipo_usuario']; ?>"
                                                        data-documento="<?php echo htmlspecialchars(isset($row['documento']) ? $row['documento'] : '', ENT_QUOTES); ?>"
                                                        data-email="<?php echo htmlspecialchars(isset($row['email']) ? $row['email'] : '', ENT_QUOTES); ?>"
                                                        data-area="<?php echo htmlspecialchars(isset($row['area']) ? $row['area'] : '', ENT_QUOTES); ?>"
                                                        data-responsabilidades="<?php echo htmlspecialchars(isset($row['responsabilidades']) ? $row['responsabilidades'] : '', ENT_QUOTES); ?>"
                                                        data-observaciones="<?php echo htmlspecialchars(isset($row['observaciones']) ? $row['observaciones'] : '', ENT_QUOTES); ?>">
                                                        <i class="fa-solid fa-pen"></i> Editar
                                                    </button>
                                                    <button class="btn-action btn-delete-custom btn-delete" data-id="<?php echo $row['id']; ?>">
                                                        <i class="fa-solid fa-trash"></i> Eliminar
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php
                                            $contador++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="alert alert-warning text-center mt-4">
                            <h4><i class="fa-solid fa-exclamation-triangle"></i> No se encontraron usuarios</h4>
                            <p>No hay usuarios que coincidan con los criterios de búsqueda.</p>
                            <a href="addusers.php" class="btn btn-success">
                                <i class="fa-solid fa-user-plus"></i> Crear Primer Usuario
                            </a>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="text-center mt-4 mb-4">
                        <a href="../../access.php" class="btn btn-secondary btn-lg">
                            <i class="fa-solid fa-arrow-left"></i> Volver al Menú Principal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditUser">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label for="edit_documento">Documento</label>
                            <input type="text" class="form-control" id="edit_documento" name="documento">
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="edit_area">Área</label>
                                <input type="text" class="form-control" id="edit_area" name="area">
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="edit_responsabilidades">Responsabilidades</label>
                                <input type="text" class="form-control" id="edit_responsabilidades" name="responsabilidades">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_observaciones">Observaciones</label>
                            <textarea class="form-control" id="edit_observaciones" name="observaciones" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_nombre">Nombre completo</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_usuario">Usuario</label>
                            <input type="text" class="form-control" id="edit_usuario" name="usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_password">Contraseña (dejar en blanco para no cambiar)</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="edit_tipo">Tipo de usuario</label>
                            <select id="edit_tipo" name="tipo_usuario" class="form-control" required>
                                <option value="1">Administrador</option>
                                <option value="2">Abogado</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnSaveUser" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts: jQuery (full), Popper, Bootstrap, SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // abrir modal con datos
            $('.btn-edit').on('click', function() {
                var id = $(this).data('id');
                var usuario = $(this).data('usuario');
                var nombre = $(this).data('nombre');
                var tipo = $(this).data('tipo');

                $('#edit_id').val(id);
                $('#edit_usuario').val(usuario);
                $('#edit_nombre').val(nombre);
                $('#edit_tipo').val(tipo);
                $('#edit_password').val('');
                $('#edit_documento').val($(this).data('documento'));
                $('#edit_email').val($(this).data('email'));
                $('#edit_area').val($(this).data('area'));
                $('#edit_responsabilidades').val($(this).data('responsabilidades'));
                $('#edit_observaciones').val($(this).data('observaciones'));

                $('#editUserModal').modal('show');
            });

            // guardar cambios via AJAX
            $('#btnSaveUser').on('click', function() {
                var form = $('#formEditUser');
                var data = form.serialize();

                $.post('updateuser.php', data, function(resp) {
                    if (resp.success) {
                        $('#editUserModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Actualizado',
                            text: resp.message,
                            timer: 1800,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resp.message
                        });
                    }
                }, 'json').fail(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error en la petición'
                    });
                });
            });

            // eliminar usuario
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: '¿Seguro?',
                    text: 'Esta acción eliminará el usuario permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('deleteuser.php', {
                            id: id
                        }, function(resp) {
                            if (resp.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: resp.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: resp.message
                                });
                            }
                        }, 'json').fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error en la petición'
                            });
                        });
                    }
                });
            });

        });
    </script>
</body>

</html>