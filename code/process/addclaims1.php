<?php
    
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: index.php");
    }
    
    $usuario      = $_SESSION['usuario'];
    $nombre       = $_SESSION['nombre'];
    $tipo_usuario = $_SESSION['tipo_usuario'];

    include("../../conexion.php");
    header("Content-Type: text/html;charset=utf-8");
    date_default_timezone_set("America/Bogota");

    $fecha_rec          =   $_POST['fecha_rec'];
    $nom_rec            =   mb_strtoupper($_POST['nom_rec']);
    $reclamacion_rec    =   mb_strtoupper($_POST['reclamacion_rec']);
    $rad_rec            =   $_POST['rad_rec'];
    $doc_jur            =   $_POST['doc_jur'];
    $est_res_rec        =   mb_strtoupper($_POST['est_res_rec']);
    $obs_rec        =   mb_strtoupper($_POST['obs_rec']);
    $estado_rec     =   1;
    $fecha_alta_rec =   date('Y-m-d h:i:s');
    $fecha_edit_rec =   ('0000-00-00 00:00:00');
    $id_usu             =   $_SESSION['id'];

    $sql = "INSERT INTO reclamaciones (fecha_rec, nom_rec, reclamacion_rec, rad_rec, doc_jur, est_res_rec, obs_rec, estado_rec, fecha_alta_rec, fecha_edit_rec, id_usu) values ('$fecha_rec', '$nom_rec', '$reclamacion_rec', '$rad_rec', '$doc_jur','$est_res_rec','$obs_rec','$estado_rec', '$fecha_alta_rec', '$fecha_edit_rec', '$id_usu')";
    $resultado = $mysqli->query($sql);

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
                    <title>JURIDICA</title>
                    <style>
                        .responsive {
                            max-width: 100%;
                            height: auto;
                        }
                    </style>
                </head>
                <body>
                    <center>
                        <img src='../../img/gobersecre.png' width=437 height=206 class='responsive'>
                    <div class='container'>
                        <br />
                        <h3><b><i class='fas fa-check-circle'></i> SE GUARDÓ DE FORMA EXITOSA EL REGISTRO</b></h3><br />
                        <p align='center'><a href='../../access.php'><img src='../../img/atras.png' width=96 height=96></a></p>
                    </div>
                    </center>
                </body>
            </html>
        ";
?>