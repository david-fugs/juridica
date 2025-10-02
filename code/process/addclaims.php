<?php
    
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: index.php");
    }

    $usuario      = $_SESSION['usuario'];
    $nombre       = $_SESSION['nombre'];
    $tipo_usuario = $_SESSION['tipo_usuario'];
    header("Content-Type: text/html;charset=utf-8");

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>JURIDICA</title>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

       	<script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
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
    <body>
    
		<center>
	    	<img src='../../img/gobersecre.png' width=437 height=206 class="responsive">
		</center>

<?php

	date_default_timezone_set("America/Bogota");
	include("../../conexion.php");
	require_once("../../zebra.php");

?>

		<div class="container pt-2">
			<h1><b><i class="fa-solid fa-building-shield"></i> RECLAMACIONES</b></h1>
			<p><i><b><font size=3 color=#c68615>*Datos obligatorios</i></b></font></p>
	        
	        <div class="row">
	        	<div class="col-md-12">
                    <form id="form_contacto" action='addclaims1.php' method="POST">
	                   
	                	<div class="form-group">
                			<div class="row">
                    			<div class="col-12 col-sm-2">
	                   	        	<label for="fecha_rec">* FECHA ENTREGA:</label>
									<input type='date' name='fecha_rec' class='form-control' id="fecha_rec" required autofocus />
	                        	</div>
	                        	<div class="col-12 col-sm-5">
		                            <label for="nom_rec">* NOMBRE:</label>
									<input type='text' name='nom_rec' id="nom_rec" class='form-control' required />
	                        	</div>
	                        	<div class="col-12 col-sm-5">
		                            <label for="reclamacion_rec">* RECLAMACIÓN:</label>
									<input type='text' name='reclamacion_rec' class='form-control' id="reclamacion_rec" required />
	                        	</div>
	                    	</div>
	                    </div>

	                    <div class="form-group">
                			<div class="row">
                				<div class="col-12 col-sm-2">
		                            <label for="rad_rec">* RADICADO:</label>
									<input type='text' name='rad_rec' class='form-control' id="rad_rec" required />
	                        	</div>
                    			<div class="col-12 col-sm-5">
	                   	        	<label for="doc_jur">* ABOGADO:</label>
									<select id="selectJur"  class="form-control" name="doc_jur" >
										<option value = ""></option>
											<?php
												$sql = $mysqli->prepare("SELECT * FROM juridicos");
												if($sql->execute()){
													$g_result = $sql->get_result();
												}
												while($row = $g_result->fetch_array()){
											?>
										<option value = "<?php echo $row['doc_jur']?>"><?php echo $row['nom_jur']?></option>
											<?php
												}
												$mysqli->close();	
											?>
									</select>
	                        	</div>
	                        	<div class="col-12 col-sm-5">
		                            <label for="est_res_rec">* ESTADO:</label>
									<input type='text' name='est_res_rec' class='form-control' id="est_res_rec" required />
	                        	</div>
	                    	</div>
	                    </div>
	                        	
		                <div class="form-row">
		                    <div class="form-group col-md-12">
		                        <label for="obs_enc_cam">OBSERVACIONES y/o COMENTARIOS ADICIONALES:</label>
				              	<textarea class="form-control" id="exampleFormControlTextarea1" rows="2" name="obs_enc_cam" style="text-transform:uppercase;" /></textarea>
		                    </div>
		                </div>

                        <button type="submit" class="btn btn-primary">
							<span class="spinner-border spinner-border-sm"></span>
							INGRESAR REGISTRO
						</button>
						<button type="reset" class="btn btn-outline-dark" role='link' onclick="history.back();" type='reset'><img src='../../img/atras.png' width=27 height=27> REGRESAR
						</button>
	                </form>
	            </div>
        	</div>
   		</div>

    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
   
	</body>
</html>