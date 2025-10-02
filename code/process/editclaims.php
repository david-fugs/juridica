<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
}

header("Content-Type: text/html;charset=utf-8");
$usuario      = $_SESSION['usuario'];
$nombre       = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JURIDICA</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../fontawesome/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <!-- Using Select2 from a CDN-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }
    </style>
    <script>
        function ordenarSelect(id_componente) {
            var selectToSort = jQuery('#' + id_componente);
            var optionActual = selectToSort.val();
            selectToSort.html(selectToSort.children('option').sort(function(a, b) {
                return a.text === b.text ? 0 : a.text < b.text ? -1 : 1;
            })).val(optionActual);
        }
        $(document).ready(function() {
            ordenarSelect('selectJur');
            // Initialize Select2 on the abogado select for better UX
            if (window.jQuery && jQuery().select2) {
                jQuery('#selectJur').select2({
                    width: '100%'
                });
            }
            // SweetAlert2 include (CDN)
            if (typeof Swal === 'undefined') {
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                document.head.appendChild(script);
            }

            // Intercept form submit and send via AJAX
            $('#selectJur').closest('form').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                var $form = $(form);
                // Basic HTML5 validity check
                if (!form.checkValidity()) {
                    // Let browser show validation UI
                    form.reportValidity();
                    return;
                }

                // Collect form data
                var formData = $form.serialize();

                // Show confirmation before sending
                var nombre = $('#nom_rec').val();
                var reclamacion = $('#reclamacion_rec').val();
                var abogadoText = $('#selectJur option:selected').text();

                Swal.fire({
                    title: '¿Actualizar reclamación?',
                    html: '<b>Solicitante:</b> ' + $('<div>').text(nombre).html() + '<br/><b>Reclamación:</b> ' + $('<div>').text(reclamacion).html() + '<br/><b>Abogado:</b> ' + $('<div>').text(abogadoText).html(),
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send AJAX
                        $.ajax({
                            url: 'editclaims1.php',
                            method: 'POST',
                            data: formData,
                            dataType: 'json'
                        }).done(function(resp) {
                            if (resp && resp.success) {
                                Swal.fire({
                                    title: 'Actualizado',
                                    text: resp.message || 'Reclamación actualizada correctamente',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    // Optionally redirect back to list or stay; go back in history
                                    window.location.href = 'showclaims.php';
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: (resp && resp.message) ? resp.message : 'Error desconocido al actualizar',
                                    icon: 'error'
                                });
                            }
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            var details = 'Estado: ' + jqXHR.status + '\n' + 'Error: ' + textStatus + '\n' + (jqXHR.responseText ? ('Respuesta: ' + jqXHR.responseText) : '');
                            Swal.fire({
                                title: 'Error de red',
                                text: 'No se pudo conectar con el servidor: ' + textStatus + '\n\n' + details,
                                icon: 'error'
                            });
                        });
                    }
                });
            });
        });
    </script>
</head>

<body>
    <?php
    include("../../conexion.php");
    date_default_timezone_set("America/Bogota");
    $time = time();
    $id_rec  = $_GET['id_rec'];
    if (isset($_GET['id_rec'])) {
        // Cambiar consulta para usar tabla usuarios
        $sql = mysqli_query($mysqli, "SELECT reclamaciones.*, usuarios.nombre as nom_jur FROM reclamaciones LEFT JOIN usuarios ON reclamaciones.doc_jur = usuarios.documento WHERE reclamaciones.id_rec = '$id_rec'");
        $row = mysqli_fetch_array($sql);
    }

    ?>

    <div class="container">
        <center>
            <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
        </center>

        <h1><b><img src="../../img/claims.png" width=35 height=35> ACTUALIZAR INFORMACIÓN RECLAMACIONES <img src="../../img/claims.png" width=35 height=35></b></h1>
        <p><i><b>
                    <font size=3 color=#c68615>* Datos obligatorios</i></b></font>
        </p>

        <div class="card mt-3 shadow-sm">
            <div class="card-body">
                <form action='editclaims1.php' method="POST">

                    <hr style="border: 2px solid #16087B; border-radius: 2px;">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-sm-3">
                                <input type='number' name='id_rec' id="id_rec" class='form-control' value='<?php echo $row['id_rec']; ?>' readonly hidden />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-sm-2">
                                <label for="fecha_rec">FECHA OFICINA</label>
                                <input type='date' name='fecha_rec' id="fecha_rec" class='form-control' value='<?php echo utf8_encode($row['fecha_rec']); ?>' autofocus />
                            </div>
                            <div class="col-12 col-sm-5">
                                <label for="nom_rec">NOMBRES Y APELLIDOS:</label>
                                <input type='text' name='nom_rec' id="nom_rec" class='form-control' value='<?php echo utf8_encode($row['nom_rec']); ?>' style="text-transform:uppercase;" />
                            </div>
                            <div class="col-12 col-sm-5">
                                <label for="reclamacion_rec">RECLAMACIÓN:</label>
                                <input type='text' name='reclamacion_rec' id="reclamacion_rec" class='form-control' value='<?php echo utf8_encode($row['reclamacion_rec']); ?>' style="text-transform:uppercase;" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-sm-3">
                                <label for="rad_rec">RADICACIÓN:</label>
                                <input type='text' name='rad_rec' id="rad_rec" class='form-control' value='<?php echo utf8_encode($row['rad_rec']); ?>' autofocus />
                            </div>
                            <div class="col-12 col-sm-5">
                                <label for="doc_jur">ABOGADO ASIGNADO:</label>
                                <select name='doc_jur' class='form-control' id='selectJur' required>
                                    <option value=''></option>
                                    <?php
                                    header('Content-Type: text/html;charset=utf-8');
                                    // Cambiar consulta para usar tabla usuarios
                                    $consulta = "SELECT documento, nombre FROM usuarios WHERE tipo_usuario = '2' ORDER BY nombre ASC";
                                    $res = mysqli_query($mysqli, $consulta);
                                    if ($res) {
                                        while ($row1 = $res->fetch_assoc()) {
                                    ?>
                                            <option value='<?php echo htmlspecialchars($row1['documento']); ?>' <?php if ($row['doc_jur'] == $row1['documento']) {
                                                                                                                    echo 'selected';
                                                                                                                } ?>>
                                                <?php echo htmlspecialchars($row1['nombre']); ?>
                                            </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="est_res_rec">ESTADO / RESPUESTA:</label>
                                <input type='text' name='est_res_rec' id="est_res_rec" class='form-control' value='<?php echo utf8_encode($row['est_res_rec']); ?>' style="text-transform:uppercase;" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-12">
                                <label for="obs_rec">OBSERVACIONES y/o COMENTARIOS ADICIONALES:</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" name="obs_rec" style="text-transform:uppercase;" /><?php echo $row['obs_rec']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <hr style="border: 2px solid #16087B; border-radius: 2px;">

                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <button type="submit" class="btn btn-primary btn-lg" name="btn-update">
                                <i class="fa-solid fa-save"></i>
                                ACTUALIZAR INFORMACIÓN
                            </button>
                            <button type="reset" class="btn btn-outline-secondary ml-2" role='link' onclick="history.back();">
                                <img src='../../img/atras.png' width=24 height=24 /> REGRESAR
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>