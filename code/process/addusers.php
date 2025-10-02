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
		// Nuevos campos: documento, email, area, responsabilidades, observaciones
		$documento = isset($_POST['documento']) ? $mysqli->real_escape_string(trim($_POST['documento'])) : '';
		$email = isset($_POST['email']) ? $mysqli->real_escape_string(trim($_POST['email'])) : '';
		$area = isset($_POST['area']) ? $mysqli->real_escape_string(trim($_POST['area'])) : '';
		$responsabilidades = isset($_POST['responsabilidades']) ? $mysqli->real_escape_string(trim($_POST['responsabilidades'])) : '';
		$observaciones = isset($_POST['observaciones']) ? $mysqli->real_escape_string(trim($_POST['observaciones'])) : '';

		$nombre_usuario = isset($_POST['nombre']) ? $mysqli->real_escape_string(trim($_POST['nombre'])) : '';
		$usuario_nuevo = isset($_POST['usuario']) ? $mysqli->real_escape_string(trim($_POST['usuario'])) : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
		$tipo_usuario_nuevo = isset($_POST['tipo_usuario']) ? $mysqli->real_escape_string($_POST['tipo_usuario']) : '';

		// Validaciones: documento, usuario, nombre, password, tipo
		if(empty($documento) || empty($nombre_usuario) || empty($usuario_nuevo) || empty($password) || empty($confirm_password) || empty($tipo_usuario_nuevo)){
			$mensaje = "<div class='alert alert-danger'>Los campos Documento, Nombre, Usuario, Contraseña y Tipo son obligatorios</div>";
		} else if($password != $confirm_password){
			$mensaje = "<div class='alert alert-danger'>Las contraseñas no coinciden</div>";
		} else if(strlen($password) < 6){
			$mensaje = "<div class='alert alert-danger'>La contraseña debe tener al menos 6 caracteres</div>";
		} else {
			// Verificar si el usuario ya existe
			$sql_check = "SELECT id FROM usuarios WHERE usuario = '$usuario_nuevo'";
			$resultado = $mysqli->query($sql_check);

			if($resultado && $resultado->num_rows > 0){
				$mensaje = "<div class='alert alert-danger'>El usuario ya existe</div>";
			} else {
				// Encriptar contraseña con SHA1
				$password_encriptado = sha1($password);

				// Insertar usuario con la nueva estructura
				$sql = "INSERT INTO usuarios (usuario, documento, email, area, responsabilidades, observaciones, password, nombre, tipo_usuario) ";
				$sql .= "VALUES ('$usuario_nuevo', '$documento', '$email', '$area', '$responsabilidades', '$observaciones', '$password_encriptado', '$nombre_usuario', '$tipo_usuario_nuevo')";

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
			:root{
				--primary:#2c3e50;
				--accent:#16a085;
				--muted:#7f8c8d;
			}
			.responsive { max-width:100%; height:auto; }
			body { background: #f6f8f9; color: #2c3e50; }
			.card-modern { border: none; border-radius: 12px; box-shadow: 0 6px 18px rgba(44,62,80,0.08); overflow: hidden; }
			.card-header-modern { background: linear-gradient(90deg, var(--primary), #243447); color: #fff; padding: 18px 24px; }
			.card-body-modern { padding: 24px; }
			.form-control { border-radius: 8px; border: 1px solid #e6eef1; box-shadow: none; }
			.form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(22,160,133,0.08); }
			label { font-weight: 600; color: #34495e; }
			.small-muted { color: var(--muted); font-size: 0.85rem; }
			.btn-primary-modern { background: var(--accent); border-color: var(--accent); color: #fff; border-radius: 8px; padding: 10px 18px; }
			.btn-primary-modern:hover { background: #13907a; }
			.btn-outline-modern { border-radius: 8px; padding: 10px 18px; border-color: #dfeff0; color: #2c3e50; background: #fff; }
			@media (max-width: 576px){ .card-body-modern{ padding: 16px; } }
		</style>
   </head>
    <body>
    
		<center>
	    	<img src='../../img/gobersecre.png' width=437 height=206 class="responsive">
		</center>
		
		<div class="container">
		    <div class="row">
				<div class="col-md-8 offset-md-2">
					<div class="card-modern mt-4">
						<div class="card-header-modern">
							<h4 style="margin:0;"><i class="fa-solid fa-user-plus"></i> Crear Nuevo Usuario</h4>
						</div>
						<div class="card-body-modern">
		                    <?php echo $mensaje; ?>
		                    
		                    <form method="POST" action="">
								<div class="form-row">
									<div class="form-group col-md-4">
										<label for="documento"><strong>Documento:</strong></label>
										<input type="text" class="form-control" id="documento" name="documento"
											   value="<?php echo isset($_POST['documento']) ? $_POST['documento'] : ''; ?>"
											   placeholder="Documento" required>
									</div>
									<div class="form-group col-md-8">
										<label for="nombre"><strong>Nombre Completo:</strong></label>
										<input type="text" class="form-control" id="nombre" name="nombre" 
											   value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : ''; ?>" 
											   placeholder="Ingrese el nombre completo" required>
									</div>
								</div>

								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="email"><strong>Email:</strong></label>
										<input type="email" class="form-control" id="email" name="email"
											   value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"
											   placeholder="usuario@dominio.com">
									</div>
									<div class="form-group col-md-6">
										<label for="area"><strong>Área:</strong></label>
										<input type="text" class="form-control" id="area" name="area"
											   value="<?php echo isset($_POST['area']) ? $_POST['area'] : ''; ?>"
											   placeholder="Área/Departamento">
									</div>
								</div>

								<div class="form-group">
									<label for="responsabilidades"><strong>Responsabilidades:</strong></label>
									<input type="text" class="form-control" id="responsabilidades" name="responsabilidades"
										   value="<?php echo isset($_POST['responsabilidades']) ? $_POST['responsabilidades'] : ''; ?>"
										   placeholder="Responsabilidades principales">
								</div>

								<div class="form-group">
									<label for="observaciones"><strong>Observaciones:</strong></label>
									<textarea class="form-control" id="observaciones" name="observaciones" rows="2" placeholder="Observaciones adicionales"><?php echo isset($_POST['observaciones']) ? $_POST['observaciones'] : ''; ?></textarea>
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
									<button type="submit" class="btn-primary-modern">
										<i class="fa-solid fa-save"></i> Crear Usuario
									</button>
									<a href="../../access.php" class="btn-outline-modern ml-2">
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