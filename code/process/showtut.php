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
	<title>JURIDICA - Consultar Tutelas</title>
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
			padding: 16px 12px;
			margin-top: 20px;
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
			width: 100%;
			max-width: none;
			/* permitir usar todo el ancho disponible */
		}

		/* Unified, subtle color palette and button styles */
		.badge-role {
			font-size: 0.85rem;
			padding: 0.4em 0.6em;
			border-radius: 0.6rem;
			color: #fff;
		}

		.badge-active {
			background: #16a085;
			/* teal */
		}

		.badge-inactive {
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

		/* Action group: keep action buttons inline with gap so they don't touch */
		.action-group {
			display: inline-flex;
			align-items: center;
			gap: 8px; /* space between buttons */
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

		/* Botón crear más grande y destacable */
		.btn-create-large {
			padding: 10px 18px;
			font-size: 1.05rem;
			border-radius: 8px;
		}

		/* Pagination styles */
		.pagination {
			justify-content: center;
			margin-top: 20px;
		}

		.pagination .page-link {
			color: #2c3e50;
			border-color: #dee2e6;
		}

		.pagination .page-item.active .page-link {
			background-color: #2c3e50;
			border-color: #2c3e50;
		}

		.pagination .page-link:hover {
			color: #fff;
			background-color: #2c3e50;
			border-color: #2c3e50;
		}

		.modal-lg {
			max-width: 1100px;
			/* Ampliado para modal más ancho */
		}

		/* Truncar textos largos en una sola línea con ellipsis y mostrar full-text en title */
		.single-line-ellipsis {
			display: inline-block;
			max-width: 420px;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			vertical-align: middle;
		}

		/* Slightly wider overall container for tables to fit more columns */
		.table-container {
			max-width: 1700px; /* aumentado para que no se corten botones de acción */
			margin-left: auto;
			margin-right: auto;
			padding-left: 8px; /* pequeño padding para respiración */
			padding-right: 8px;
		}

		/* Estilos mejorados para la tabla */
		.table {
			background: white;
			border-radius: 8px;
			overflow: hidden;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
		}

		.table thead th {
			background: linear-gradient(135deg, #2c3e50, #34495e);
			color: white;
			font-weight: 600;
			border: none;
			padding: 15px 12px;
			text-align: center;
		}

		.table tbody tr {
			transition: all 0.2s ease;
		}

		.table tbody tr:hover {
			background: #f8f9fa;
			transform: translateY(-1px);
			box-shadow: 0 2px 8px rgba(0,0,0,0.1);
		}

		.table td {
			padding: 12px;
			vertical-align: middle;
			border-top: 1px solid #ecf0f1;
		}

		.table tbody tr:first-child td {
			border-top: none;
		}
	</style>
</head>

<body>

	<center>
		<img src='../../img/gobersecre.png' width=437 height=206 class='responsive'>
	</center>

	<section class="principal">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div align="center">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<div>
								<h1 style="color: #412fd1; text-shadow: #FFFFFF 0.1em 0.1em 0.2em; display:inline-block; margin-right:12px;">
									<b><i class="fa-solid fa-building-shield"></i> CONSULTAR TUTELAS</b>
								</h1>
								<a href="../../access.php" class="btn btn-outline-secondary" style="margin-right:8px;"><i class="fa-solid fa-arrow-left"></i> Regresar</a>
								<button id="btnAddTut" class="btn btn-success btn-create-large" data-toggle="modal" data-target="#modalAddTut">
									<i class="fa-solid fa-plus fa-lg"></i> <strong>Crear Tutela</strong>
								</button>
							</div>
						</div>
					</div>

					<div class="card mt-4">
						<div class="card-header bg-primary text-white">
							<h5><i class="fa-solid fa-search"></i> Búsqueda de Tutelas</h5>
						</div>
						<div class="card-body">
							<form action="showtut.php" method="get" class="form-inline justify-content-center">
								<div class="form-group mx-2">
									<input name="nom_tut" type="text" class="form-control" placeholder="Nombre Tutela" size=30
										value="<?php echo isset($_GET['nom_tut']) ? $_GET['nom_tut'] : ''; ?>">
								</div>
								<div class="form-group mx-2">
									<input name="tipo_tut" type="text" class="form-control" placeholder="Tipo Tutela" size=20
										value="<?php echo isset($_GET['tipo_tut']) ? $_GET['tipo_tut'] : ''; ?>">
								</div>
								<div class="form-group mx-2">
									<input name="nom_jur" type="text" class="form-control" placeholder="Abogado Asignado" size=30
										value="<?php echo isset($_GET['nom_jur']) ? $_GET['nom_jur'] : ''; ?>">
								</div>
								<button type="submit" class="btn btn-primary mx-2">
									<i class="fa-solid fa-search"></i> Buscar
								</button>
								<a href="showtut.php" class="btn btn-secondary mx-2">
									<i class="fa-solid fa-refresh"></i> Limpiar
								</a>
							</form>
						</div>
					</div>

<?php
date_default_timezone_set("America/Bogota");
include("../../conexion.php");

// Obtener parámetros de búsqueda
$nom_tut = isset($_GET['nom_tut']) ? $_GET['nom_tut'] : '';
$tipo_tut = isset($_GET['tipo_tut']) ? $_GET['tipo_tut'] : '';
$nom_jur = isset($_GET['nom_jur']) ? $_GET['nom_jur'] : '';

// Construir consulta con filtros
$whereConditions = [];
if (!empty($nom_tut)) {
    $whereConditions[] = "t.nom_tut LIKE '%" . $mysqli->real_escape_string($nom_tut) . "%'";
}
if (!empty($tipo_tut)) {
    $whereConditions[] = "t.tipo_tut LIKE '%" . $mysqli->real_escape_string($tipo_tut) . "%'";
}
if (!empty($nom_jur)) {
    $whereConditions[] = "u.nombre LIKE '%" . $mysqli->real_escape_string($nom_jur) . "%'";
}

$whereClause = '';
if (!empty($whereConditions)) {
    $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
}

// --- PAGINACIÓN ---
$per_page = 25; // filas por página
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $per_page;

// Obtener total de filas para calcular páginas
$count_sql = "SELECT COUNT(*) AS cnt FROM tutelas t LEFT JOIN usuarios u ON t.doc_jur = u.documento {$whereClause}";
$count_res = $mysqli->query($count_sql);
$total_rows = 0;
if ($count_res) {
	$r = $count_res->fetch_assoc();
	$total_rows = intval($r['cnt']);
}
$total_pages = ($total_rows > 0) ? ceil($total_rows / $per_page) : 1;

// construir querystring base para mantener filtros en links
$qs_params = $_GET;
unset($qs_params['page']);
$base_qs = http_build_query($qs_params);
if ($base_qs) $base_qs .= '&';

?>

					<div class="table-container">
						<div class="table-responsive">
							<table class="table table-hover table-sm">
								<thead>
									<tr>
										<th>Fecha</th>
										<th>Nombre</th>
										<th>Tipo</th>
										<th>Abogado</th>
										<th>Estado</th>
										<th>Observaciones</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql = "SELECT t.id_tut, t.fecha_tut, t.nom_tut, t.tipo_tut, t.estado_tut, t.obs_tut, u.nombre as nom_jur 
										FROM tutelas t 
										LEFT JOIN usuarios u ON t.doc_jur = u.documento 
										$whereClause 
										ORDER BY t.id_tut DESC 
										LIMIT {$per_page} OFFSET {$offset}";
									$res = $mysqli->query($sql);
									if ($res && $res->num_rows > 0) {
										while ($row = $res->fetch_assoc()) {
											echo '<tr>';
											echo '<td>' . htmlspecialchars($row['fecha_tut']) . '</td>';
											echo '<td><span class="single-line-ellipsis" title="' . htmlspecialchars($row['nom_tut']) . '">' . htmlspecialchars($row['nom_tut']) . '</span></td>';
											echo '<td>' . htmlspecialchars($row['tipo_tut']) . '</td>';
											echo '<td>' . htmlspecialchars($row['nom_jur']) . '</td>';
											echo '<td>' . htmlspecialchars($row['estado_tut']) . '</td>';
											echo '<td><span class="single-line-ellipsis" title="' . htmlspecialchars($row['obs_tut']) . '">' . htmlspecialchars($row['obs_tut']) . '</span></td>';
											echo '<td>';
											echo '<div class="action-group">';
											echo '<button data-id="' . $row['id_tut'] . '" class="btn-action btn-edit-custom open-edit-tut"><i class="fa-solid fa-edit"></i> Editar</button>';
											echo '<button class="btn-action btn-delete-custom btn-delete-tut" data-id="' . $row['id_tut'] . '"><i class="fa-solid fa-trash"></i> Eliminar</button>';
											echo '</div>';
											echo '</td>';
											echo '</tr>';
										}
									} else {
										echo '<tr><td colspan="7" class="text-center">No se encontraron tutelas</td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
						<!-- Pagination controls -->
						<div class="d-flex justify-content-between align-items-center mt-3">
							<div>
								Mostrando página <?php echo $current_page; ?> de <?php echo $total_pages; ?> (<?php echo $total_rows; ?> registros)
							</div>
							<nav>
								<ul class="pagination mb-0">
									<?php
									$start = max(1, $current_page - 3);
									$end = min($total_pages, $current_page + 3);
									if ($current_page > 1) {
										echo '<li class="page-item"><a class="page-link" href="?'.$base_qs.'page=' . ($current_page - 1) . '">&laquo; Anterior</a></li>';
									} else {
										echo '<li class="page-item disabled"><span class="page-link">&laquo; Anterior</span></li>';
									}
									for ($p = $start; $p <= $end; $p++) {
										if ($p == $current_page) {
											echo '<li class="page-item active"><span class="page-link">' . $p . '</span></li>';
										} else {
											echo '<li class="page-item"><a class="page-link" href="?'.$base_qs.'page=' . $p . '">' . $p . '</a></li>';
										}
									}
									if ($current_page < $total_pages) {
										echo '<li class="page-item"><a class="page-link" href="?'.$base_qs.'page=' . ($current_page + 1) . '">Siguiente &raquo;</a></li>';
									} else {
										echo '<li class="page-item disabled"><span class="page-link">Siguiente &raquo;</span></li>';
									}
									?>
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Add Tutela Modal -->
	<div class="modal fade" id="modalAddTut" tabindex="-1" role="dialog" aria-labelledby="modalAddTutLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalAddTutLabel">Crear Tutela</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="formAddTut">
					<div class="modal-body">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Fecha Tutela</label>
								<input type="date" name="fecha_tut" class="form-control" required />
							</div>
							<div class="form-group col-md-6">
								<label>Nombre Tutela</label>
								<input name="nom_tut" class="form-control" required />
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Tipo Tutela</label>
								<input name="tipo_tut" class="form-control" required />
							</div>
							<div class="form-group col-md-6">
								<label>Estado</label>
								<select name="estado_tut" class="form-control" required>
									<option value="">-- Seleccione estado --</option>
									<option value="Activa">Activa</option>
									<option value="En proceso">En proceso</option>
									<option value="Resuelta">Resuelta</option>
									<option value="Cerrada">Cerrada</option>
								</select>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<label>Abogado Asignado</label>
								<select name="doc_jur" class="form-control" required>
									<option value="">-- Seleccione abogado --</option>
									<?php
									$q = $mysqli->query("SELECT documento, nombre FROM usuarios WHERE tipo_usuario = '2' ORDER BY nombre");
									while ($u = $q->fetch_assoc()) {
										echo '<option value="' . htmlspecialchars($u['documento']) . '">' . htmlspecialchars($u['nombre']) . '</option>';
									}
									?>
								</select>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<label>Observaciones</label>
								<textarea name="obs_tut" class="form-control" rows="3"></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
	$(function(){
		// Submit add tutela via AJAX
		$('#formAddTut').on('submit', function(e){
			e.preventDefault();
			if (!this.checkValidity()) { 
				this.reportValidity(); 
				return; 
			}
			
			Swal.fire({ 
				title: 'Crear tutela?', 
				text: 'Se creará una nueva tutela con los datos ingresados', 
				icon: 'question', 
				showCancelButton: true, 
				confirmButtonText: 'Crear' 
			}).then(function(result){
				if (!result.isConfirmed) return;
				
				var data = $('#formAddTut').serialize();
				$.post('addtut.php', data).done(function(resp){
					try { 
						var j = (typeof resp === 'string') ? JSON.parse(resp) : resp; 
					} catch(e){ 
						j = {success:false, message: 'Respuesta no válida del servidor'}; 
					}
					
					if (j.success) { 
						Swal.fire('Creado', j.message, 'success').then(function(){ 
							location.reload(); 
						}); 
					} else { 
						Swal.fire('Error', j.message || 'Error al crear', 'error'); 
					}
				}).fail(function(xhr){ 
					Swal.fire('Error', 'HTTP '+xhr.status+': '+xhr.responseText, 'error'); 
				});
			});
		});

		// Delete tutela
		$(document).on('click', '.btn-delete-tut', function(){
			var id = $(this).data('id');
			Swal.fire({ 
				title:'Eliminar?', 
				text:'¿Desea eliminar esta tutela?', 
				icon:'warning', 
				showCancelButton:true, 
				confirmButtonText:'Eliminar' 
			}).then(function(res){
				if (!res.isConfirmed) return;
				
				$.post('deletetut.php', { id_tut: id }).done(function(resp){
					try { 
						var j = (typeof resp === 'string') ? JSON.parse(resp) : resp; 
					} catch(e){ 
						j = {success:false, message:'Respuesta no válida'}; 
					}
					
					if (j.success) { 
						Swal.fire('Eliminado', j.message, 'success').then(function(){ 
							location.reload(); 
						}); 
					} else { 
						Swal.fire('Error', j.message || 'No se pudo eliminar', 'error'); 
					}
				}).fail(function(xhr){ 
					Swal.fire('Error', 'HTTP '+xhr.status+': '+xhr.responseText, 'error'); 
				});
			});
		});
	});
	</script>

	<!-- Edit Tutela Modal (contenido cargado vía AJAX) -->
	<div class="modal fade" id="modalEditTut" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content" id="modalEditTutContent">
				<!-- Contenido cargado dinámicamente -->
			</div>
		</div>
	</div>

	<script>
	$(function(){
		// Abrir modal de edición y cargar el fragmento
		$(document).on('click', '.open-edit-tut', function(){
			var id = $(this).data('id');
			$('#modalEditTutContent').html('<div class="p-4 text-center">Cargando...</div>');
			$('#modalEditTut').modal('show');
			$.get('edittut_modal.php', { id_tut: id }).done(function(html){
				$('#modalEditTutContent').html(html);
				// init select2 if available
				if (jQuery().select2) { $('#modalEditTutContent').find('#selectJur').select2({ width: '100%' }); }
			}).fail(function(xhr){
				$('#modalEditTutContent').html('<div class="p-3 text-danger">Error cargando formulario: HTTP '+xhr.status+'</div>');
			});
		});

		// Delegate submit from modal form
		$(document).on('submit', '#modalEditTutContent form#formEditTut', function(e){
			e.preventDefault();
			var form = this;
			if (!form.checkValidity()) { form.reportValidity(); return; }
			var data = $(form).serialize();
			$.post('edittut1.php', data).done(function(resp){
				try { var j = (typeof resp === 'string') ? JSON.parse(resp) : resp; } catch(e){ j = {success:false, message:'Respuesta no valida'}; }
				if (j.success) { Swal.fire('Actualizado', j.message, 'success').then(function(){ location.reload(); }); }
				else { Swal.fire('Error', j.message || 'No se pudo actualizar', 'error'); }
			}).fail(function(xhr){ Swal.fire('Error', 'HTTP '+xhr.status+': '+xhr.responseText, 'error'); });
		});
	});
	</script>

</body>
</html>
