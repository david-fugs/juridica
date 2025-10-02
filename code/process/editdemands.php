<?php
    
    session_start();
    
    if(!isset($_SESSION['id'])){
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
        function ordenarSelect(id_componente)
          {
            var selectToSort = jQuery('#' + id_componente);
            var optionActual = selectToSort.val();
            selectToSort.html(selectToSort.children('option').sort(function (a, b) {
              return a.text === b.text ? 0 : a.text < b.text ? -1 : 1;
            })).val(optionActual);
          }
          $(document).ready(function () {
            ordenarSelect('selectJur');
          });
    </script>
</head>
<body >
    <?php
        include("../../conexion.php");
        date_default_timezone_set("America/Bogota");
        $time = time();
        $id_dem  = $_GET['id_dem'];
        if(isset($_GET['id_dem']))
        { 
            $sql = mysqli_query($mysqli, "SELECT * FROM `demandas` INNER JOIN `juridicos` ON demandas.doc_jur=juridicos.doc_jur WHERE demandas.id_dem = '$id_dem'");
            $row = mysqli_fetch_array($sql);
            //$row = $result->fetch_assoc();
        }

    ?>

   	<div class="container">
        <center>
            <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
        </center>

        <h1><b><img src="../../img/claims.png" width=35 height=35> ACTUALIZAR INFORMACIÓN DEMANDAS <img src="../../img/claims.png" width=35 height=35></b></h1>
        <p><i><b><font size=3 color=#c68615>* Datos obligatorios</i></b></font></p>
        
        <form action='editclaims1.php' method="POST">
            
            <hr style="border: 2px solid #16087B; border-radius: 2px;">
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <input type='number' name='id_dem' id="id_dem" class='form-control' value='<?php echo $row['id_dem']; ?>' readonly hidden/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-2">
                        <label for="fecha_dem">FECHA OFICINA</label>
                        <input type='date' name='fecha_dem' id="fecha_dem" class='form-control' value='<?php echo utf8_encode($row['fecha_dem']); ?>' autofocus/>
                    </div>
                    <div class="col-12 col-sm-7">
                        <label for="accionante_dem">ACCIONANTE | DEMANDANTE:</label>
                        <input type='text' name='accionante_dem' id="accionante_dem" class='form-control' value='<?php echo utf8_encode($row['accionante_dem']); ?>' style="text-transform:uppercase;"/>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="doc_dem">DOCUMENTO:</label>
                        <input type='text' name='doc_dem' id="doc_dem" class='form-control' value='<?php echo utf8_encode($row['doc_dem']); ?>' style="text-transform:uppercase;"/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-5">
                        <label for="rad_dem">RADICACIÓN:</label>
                        <input type='text' name='rad_dem' id="rad_dem" class='form-control' value='<?php echo utf8_encode($row['rad_dem']); ?>' autofocus/>
                    </div>
                    <div class="col-12 col-sm-7">
                        <label for="desp_judi_dem">DESPACHO JUDICIAL:</label>
                        <input type='text' name='desp_judi_dem' id="desp_judi_dem" class='form-control' value='<?php echo utf8_encode($row['desp_judi_dem']); ?>' autofocus/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <label for="est_act_proc_dem">ESTADO ACTUAL DEL PROCESO:</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" name="est_act_proc_dem" style="text-transform:uppercase;" /><?php echo $row['est_act_proc_dem']; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <label for="doc_jur">ABOGADO ASIGNADO:</label>
                        <select name='doc_jur' class='form-control' id="selectJur" required />
                            <option value=''></option>
                            <?php
                                header('Content-Type: text/html;charset=utf-8');
                                $consulta='SELECT * FROM juridicos';
                                $res = mysqli_query($mysqli,$consulta);
                                $num_reg = mysqli_num_rows($res);
                                while($row1 = $res->fetch_array())
                                {
                                ?> 
                                    <option value='<?php echo $row1['doc_jur']; ?>'<?php if($row['doc_jur']==$row1['doc_jur']){echo 'selected';} ?>>
                                        <?php echo $row1['nom_jur']; ?>
                                    </option>
                                <?php
                                }
                            ?>    
                        </select>
                    </div>
                    <div class="col-12 col-sm-2">
                        <label for="interno_dem">INTERNO:</label>
                        <input type='text' name='interno_dem' id="interno_dem" class='form-control' value='<?php echo utf8_encode($row['interno_dem']); ?>'/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12">
                        <label for="obs_dem">OBSERVACIONES y/o COMENTARIOS ADICIONALES:</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" name="obs_dem" style="text-transform:uppercase;" /><?php echo $row['obs_dem']; ?></textarea>
                    </div>
                </div>
            </div>

            <hr style="border: 2px solid #16087B; border-radius: 2px;">

            <button type="submit" class="btn btn-primary" name="btn-update">
                <span class="spinner-border spinner-border-sm"></span>
                ACTUALIZAR INFORMACIÓN RECLAMACIONES
            </button>
            <button type="reset" class="btn btn-outline-dark" role='link' onclick="history.back();" type='reset'><img src='../../img/atras.png' width=27 height=27> REGRESAR
            </button>
        </form>
    </div>
</body>
</html>