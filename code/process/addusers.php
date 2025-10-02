<?php
    
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: ../../index.php");
    }

    $usuario      = $_SESSION['usuario'];
    $nombre       = $_SESSION['nombre'];
    $tipo_usuario = $_SESSION['tipo_usuario'];
    header("Content-Type: text/html;charset=utf-8");

    require "../../conexion.php";

    $mensaje = "";
    
    if($_POST){
        $nombre_usuario = $_POST['nombre'];
        $usuario_nuevo = $_POST['usuario'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $tipo_usuario_nuevo = $_POST['tipo_usuario'];
        
        // Validaciones
        if(empty($nombre_usuario) || empty($usuario_nuevo) || empty($password) || empty($confirm_password) || empty($tipo_usuario_nuevo)){
            $mensaje = "<div class='alert alert-danger'>Todos los campos son obligatorios</div>";
        } else if($password != $confirm_password){
            $mensaje = "<div class='alert alert-danger'>Las contraseñas no coinciden</div>";
        } else if(strlen($password) < 6){
            $mensaje = "<div class='alert alert-danger'>La contraseña debe tener al menos 6 caracteres</div>";
        } else {
            // Verificar si el usuario ya existe
            $sql_check = "SELECT id FROM usuarios WHERE usuario = '$usuario_nuevo'";
            $resultado = $mysqli->query($sql_check);
            
            if($resultado->num_rows > 0){
                $mensaje = "<div class='alert alert-danger'>El usuario ya existe</div>";
            } else {
                // Encriptar contraseña con SHA1
                $password_encriptado = sha1($password);
                
                // Insertar usuario
                $sql = "INSERT INTO usuarios (nombre, usuario, password, tipo_usuario) VALUES ('$nombre_usuario', '$usuario_nuevo', '$password_encriptado', '$tipo_usuario_nuevo')";
                
                if($mysqli->query($sql)){
                    $mensaje = "<div class='alert alert-success'>Usuario creado exitosamente</div>";
                    // Limpiar formulario
                    $_POST = array();
                } else {
                    $mensaje = "<div class='alert alert-danger'>Error al crear usuario: " . $mysqli->error . "</div>";
                }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>JURIDICA - Crear Usuario</title>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

       	<script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
		<style>
        	.responsive {
           		max-width: 100%;
            	height: auto;
        	}
    	</style>
   </head>
    <body>
    
		<center>
	    	<img src='../../img/gobersecre.png' width=437 height=206 class="responsive">
		</center>
		
		<div class="container">
		    <div class="row">
		        <div class="col-md-8 offset-md-2">
		            <div class="card mt-4">
		                <div class="card-header bg-primary text-white">
		                    <h4><i class="fa-solid fa-user-plus"></i> Crear Nuevo Usuario</h4>
		                </div>
		                <div class="card-body">
		                    <?php echo $mensaje; ?>
		                    
		                    <form method="POST" action="">
		                        <div class="form-group">
		                            <label for="nombre"><strong>Nombre Completo:</strong></label>
		                            <input type="text" class="form-control" id="nombre" name="nombre" 
		                                   value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : ''; ?>" 
		                                   placeholder="Ingrese el nombre completo" required>
		                        </div>
		                        
		                        <div class="form-group">
		                            <label for="usuario"><strong>Usuario:</strong></label>
		                            <input type="text" class="form-control" id="usuario" name="usuario" 
		                                   value="<?php echo isset($_POST['usuario']) ? $_POST['usuario'] : ''; ?>" 
		                                   placeholder="Ingrese el nombre de usuario" required>
		                            <small class="form-text text-muted">Solo letras, números y guiones bajos</small>
		                        </div>
		                        
		                        <div class="form-group">
		                            <label for="password"><strong>Contraseña:</strong></label>
		                            <input type="password" class="form-control" id="password" name="password" 
		                                   placeholder="Ingrese la contraseña" required>
		                            <small class="form-text text-muted">Mínimo 6 caracteres</small>
		                        </div>
		                        
		                        <div class="form-group">
		                            <label for="confirm_password"><strong>Confirmar Contraseña:</strong></label>
		                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
		                                   placeholder="Confirme la contraseña" required>
		                        </div>
		                        
		                        <div class="form-group">
		                            <label for="tipo_usuario"><strong>Tipo de Usuario:</strong></label>
		                            <select class="form-control" id="tipo_usuario" name="tipo_usuario" required>
		                                <option value="">Seleccione el tipo de usuario</option>
		                                <option value="1" <?php echo (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == '1') ? 'selected' : ''; ?>>Administrador</option>
		                                <option value="2" <?php echo (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == '2') ? 'selected' : ''; ?>>Abogado</option>
		                            </select>
		                        </div>
		                        
		                        <div class="form-group text-center">
		                            <button type="submit" class="btn btn-success btn-lg">
		                                <i class="fa-solid fa-save"></i> Crear Usuario
		                            </button>
		                            <a href="../../access.php" class="btn btn-secondary btn-lg ml-2">
		                                <i class="fa-solid fa-arrow-left"></i> Volver
		                            </a>
		                        </div>
		                    </form>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>