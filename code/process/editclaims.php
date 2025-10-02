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
</head>
<body >
    <?php
        include("../../conexion.php");
        date_default_timezone_set("America/Bogota");
        $time = time();
        $id_rec  = $_GET['id_rec'];
        if(isset($_GET['id_rec']))
        { 
            $sql = mysqli_query($mysqli, "SELECT * FROM `reclamaciones` INNER JOIN `juridicos` ON reclamaciones.doc_jur=juridicos.doc_jur WHERE reclamaciones.id_rec = '$id_rec'");
            $row = mysqli_fetch_array($sql);
            //$row = $result->fetch_assoc();
        }

    ?>

   	<div class="container">
        <center>
            <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
        </center>

        <h1><b><img src="../../img/claims.png" width=35 height=35> ACTUALIZAR INFORMACIÓN RECLAMACIONES <img src="../../img/claims.png" width=35 height=35></b></h1>
        <p><i><b><font size=3 color=#c68615>* Datos obligatorios</i></b></font></p>
        
        <form action='editclaims1.php' method="POST">
            
            <hr style="border: 2px solid #16087B; border-radius: 2px;">
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <input type='number' name='id_rec' id="id_rec" class='form-control' value='<?php echo $row['id_rec']; ?>' readonly hidden/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-2">
                        <label for="fecha_rec">FECHA OFICINA</label>
                        <input type='date' name='fecha_rec' id="fecha_rec" class='form-control' value='<?php echo utf8_encode($row['fecha_rec']); ?>' autofocus/>
                    </div>
                    <div class="col-12 col-sm-5">
                        <label for="nom_rec">NOMBRES Y APELLIDOS:</label>
                        <input type='text' name='nom_rec' id="nom_rec" class='form-control' value='<?php echo utf8_encode($row['nom_rec']); ?>' style="text-transform:uppercase;"/>
                    </div>
                    <div class="col-12 col-sm-5">
                        <label for="reclamacion_rec">RECLAMACIÓN:</label>
                        <input type='text' name='reclamacion_rec' id="reclamacion_rec" class='form-control' value='<?php echo utf8_encode($row['reclamacion_rec']); ?>' style="text-transform:uppercase;"/>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <label for="rad_rec">RADICACIÓN:</label>
                        <input type='text' name='rad_rec' id="rad_rec" class='form-control' value='<?php echo utf8_encode($row['rad_rec']); ?>' autofocus/>
                    </div>
                    <div class="col-12 col-sm-5">
                        <label for="doc_jur">ABOGADO ASIGNADO:</label>
                        <select name='doc_jur' class='form-control' id='selectMunicipio' required />
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
                    <div class="col-12 col-sm-4">
                        <label for="est_res_rec">ESTADO / RESPUESTA:</label>
                        <input type='text' name='est_res_rec' id="est_res_rec" class='form-control' value='<?php echo utf8_encode($row['est_res_rec']); ?>' style="text-transform:uppercase;"/>
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