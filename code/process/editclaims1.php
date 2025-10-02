<?php
    date_default_timezone_set("America/Bogota");
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: index.php");
    }
    
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
    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

   	<?php
        include("../../conexion.php");
        header("Content-Type: text/html;charset=utf-8");
	    if(isset($_POST['btn-update']))
        {
            $id_rec             =   $_POST['id_rec'];
            $fecha_rec          =   $_POST['fecha_rec'];
            $nom_rec            =   mb_strtoupper($_POST['nom_rec']);
            $reclamacion_rec    =   mb_strtoupper($_POST['reclamacion_rec']);
            $rad_rec            =   $_POST['rad_rec'];
            $doc_jur            =   $_POST['doc_jur'];
            $est_res_rec        =   mb_strtoupper($_POST['est_res_rec']);
            $estado_rec         =   1;
            $obs_rec            =   mb_strtoupper($_POST['obs_rec']);
            $fecha_edit_rec     =   date('Y-m-d h:i:s');
            $id_usu             =   $_SESSION['id'];
           
            $update = "UPDATE reclamaciones SET fecha_rec='".$fecha_rec."', nom_rec='".$nom_rec."', reclamacion_rec='".$reclamacion_rec."', rad_rec='".$rad_rec."', doc_jur='".$doc_jur."', est_res_rec='".$est_res_rec."', estado_rec='".$estado_rec."', obs_rec='".$obs_rec."', fecha_edit_rec='".$fecha_edit_rec."', id_usu='".$id_usu."' WHERE id_rec='".$id_rec."'";

            $up = mysqli_query($mysqli, $update);

            echo "
                <!DOCTYPE html>
                    <html lang='es'>
                        <head>
                            <meta charset='utf-8' />
                            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                            <meta http-equiv='X-UA-Compatible' content='ie=edge'>
                            <link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet'>
                            <link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet'>
                            <link rel='stylesheet' href='../../css/bootstrap.min.css'>
                            <link href='../../fontawesome/css/all.css' rel='stylesheet'>
                            <title>HELP DESK</title>
                            <style>
                                .responsive {
                                    max-width: 100%;
                                    height: auto;
                                }
                            </style>
                        </head>
                        <body>
                            <center>
                               <img src='../../img/gobersecre.png' width='400' height='188' class='responsive'>
                            <div class='container'>
                                <br />
                                <h3><b><i class='fas fa-users'></i> SE ACTUALIZÓ DE FORMA EXITOSA EL REGISTRO</b></h3><br />
                                <p align='center'><a href='../../access.php'><img src='../../img/atras.png' width=96 height=96></a></p>
                            </div>
                            </center>
                        </body>
                    </html>
        ";
        }
    ?>

</body>
</html>