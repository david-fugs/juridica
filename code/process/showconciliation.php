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
	<title>JURIDICA - Consultar Conciliaciones</title>
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
		}

		.badge-inactive {
			background: #7f8c8d;
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

		/* Action group: keep action buttons inline with gap */
		.action-group {
			display: inline-flex;
			align-items: center;
			gap: 8px;
		}

		.btn-delete-custom {
			border-color: #e74c3c;
			color: #e74c3c;
		}

		.btn-delete-custom:hover {
			background: #e74c3c;
			color: #fff;
		}

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
		}

		/* Truncar textos largos en una sola línea con ellipsis */
		.single-line-ellipsis {
			display: inline-block;
			max-width: 420px;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			vertical-align: middle;
		}

		.table-container {
			max-width: 1700px;
			margin-left: auto;
			margin-right: auto;
			padding-left: 8px;
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
								<b><i class="fa-solid fa-building-shield"></i> CONSULTAR CONCILIACIONES</b>
							</h1>
							<a href="../../access.php" class="btn btn-outline-secondary" style="margin-right:8px;"><i class="fa-solid fa-arrow-left"></i> Regresar</a>
							<a href="export_conciliaciones_excel.php?<?php echo http_build_query($_GET); ?>" class="btn btn-success" style="margin-right:8px;">
								<i class="fa-solid fa-file-excel"></i> Exportar a Excel
							</a>
							<button id="btnAddConc" class="btn btn-success btn-create-large" data-toggle="modal" data-target="#modalAddConc">
								<i class="fa-solid fa-plus fa-lg"></i> <strong>Crear Conciliación</strong>
							</button>
						</div>
						</div>
					</div>

					<div class="card mt-4">
						<div class="card-header bg-primary text-white">
							<h5><i class="fa-solid fa-search"></i> Búsqueda de Conciliaciones</h5>
						</div>
						<div class="card-body">
							<form action="showconciliation.php" method="get" class="form-inline justify-content-center">
								<div class="form-group mx-2">
									<input name="accionante_conc" type="text" class="form-control" placeholder="Accionante | Demandante" size=30
										value="<?php echo isset($_GET['accionante_conc']) ? $_GET['accionante_conc'] : ''; ?>">
								</div>
								<div class="form-group mx-2">
									<input name="doc_conc" type="text" class="form-control" placeholder="Documento" size=20
										value="<?php echo isset($_GET['doc_conc']) ? $_GET['doc_conc'] : ''; ?>">
								</div>
								<div class="form-group mx-2">
									<input name="nom_jur" type="text" class="form-control" placeholder="Abogado Asignado" size=30
										value="<?php echo isset($_GET['nom_jur']) ? $_GET['nom_jur'] : ''; ?>">
								</div>
								<button type="submit" class="btn btn-primary mx-2">
									<i class="fa-solid fa-search"></i> Buscar
								</button>
								<a href="showconciliation.php" class="btn btn-secondary mx-2">
									<i class="fa-solid fa-refresh"></i> Limpiar
								</a>
							</form>
						</div>
					</div>

<?php
date_default_timezone_set("America/Bogota");
include("../../conexion.php");

// Obtener documento del usuario actual si es tipo 1 (abogado)
$doc_usuario_actual = null;
if ($tipo_usuario == 1) {
	$id_usuario = $_SESSION['id'];
	$sql_doc = "SELECT documento FROM usuarios WHERE id = '$id_usuario' LIMIT 1";
	$res_doc = $mysqli->query($sql_doc);
	if ($res_doc && $res_doc->num_rows > 0) {
		$row_doc = $res_doc->fetch_assoc();
		$doc_usuario_actual = $row_doc['documento'];
	}
    
    // Filtro por estado realizada
    if ($estado_filter === 'realizada') {
        $where_conditions[] = "conciliaciones.realizada = 1";
    } elseif ($estado_filter === 'activa') {
        $where_conditions[] = "(conciliaciones.realizada = 0 OR conciliaciones.realizada IS NULL)";
    }
}

// Obtener parámetros de búsqueda
$accionante_conc = isset($_GET['accionante_conc']) ? $_GET['accionante_conc'] : '';
$doc_conc = isset($_GET['doc_conc']) ? $_GET['doc_conc'] : '';
$nom_jur = isset($_GET['nom_jur']) ? $_GET['nom_jur'] : '';

// Construir consulta con filtros
$whereConditions = [];

// Si es tipo_usuario = 1 (abogado), solo ver conciliaciones asignadas a él (filtrar por doc_jur)
if ($tipo_usuario == 1 && !empty($doc_usuario_actual)) {
    $whereConditions[] = "c.doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'";
}

if (!empty($accionante_conc)) {
    $whereConditions[] = "c.accionante_conc LIKE '%" . $mysqli->real_escape_string($accionante_conc) . "%'";
}
if (!empty($doc_conc)) {
    $whereConditions[] = "c.doc_conc LIKE '%" . $mysqli->real_escape_string($doc_conc) . "%'";
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
$count_sql = "SELECT COUNT(*) AS cnt FROM conciliaciones c LEFT JOIN usuarios u ON c.doc_jur = u.documento {$whereClause}";
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
									<th>Accionante</th>
									<th>Documento</th>
									<th>Causa/Litigio</th>
									<th>Medio Control</th>
									<th>Procuraduría</th>
									<th>Radicado</th>
									<th>Abogado</th>
									<th>Observaciones</th>
									<th>Acciones</th>
								</tr>
							</thead>
								<tbody>
									<?php
									$sql = "SELECT c.id_conc, c.accionante_conc, c.doc_conc, c.causa_litigio_conc, c.medio_control_conc, 
											c.procuraduria_conc, c.rad_conc, c.fecha_conc, c.estado_conc, c.obs_conc, u.nombre as nom_jur 
										FROM conciliaciones c 
										LEFT JOIN usuarios u ON c.doc_jur = u.documento 
										$whereClause 
										ORDER BY c.fecha_conc DESC, c.id_conc DESC 
										LIMIT {$per_page} OFFSET {$offset}";
									$res = $mysqli->query($sql);
									if ($res && $res->num_rows > 0) {
										while ($row = $res->fetch_assoc()) {
											echo '<tr>';
											echo '<td>' . htmlspecialchars($row['fecha_conc']) . '</td>';
											echo '<td><span class="single-line-ellipsis" title="' . htmlspecialchars($row['accionante_conc']) . '">' . htmlspecialchars($row['accionante_conc']) . '</span></td>';
											echo '<td>' . htmlspecialchars($row['doc_conc']) . '</td>';
											echo '<td><span class="single-line-ellipsis" title="' . htmlspecialchars($row['causa_litigio_conc']) . '">' . htmlspecialchars($row['causa_litigio_conc']) . '</span></td>';
										echo '<td>' . htmlspecialchars($row['medio_control_conc']) . '</td>';
										echo '<td>' . htmlspecialchars($row['procuraduria_conc']) . '</td>';
										echo '<td>' . htmlspecialchars($row['rad_conc']) . '</td>';
										echo '<td>' . htmlspecialchars($row['nom_jur']) . '</td>';
										echo '<td><span class="single-line-ellipsis" title="' . htmlspecialchars($row['obs_conc']) . '">' . htmlspecialchars($row['obs_conc']) . '</span></td>';
										echo '<td>';
										echo '<div class="action-group">';
										echo '<button data-id="' . $row['id_conc'] . '" class="btn-action btn-edit-custom open-edit-conc"><i class="fa-solid fa-edit"></i> Editar</button>';
										echo '<button class="btn-action btn-delete-custom btn-delete-conc" data-id="' . $row['id_conc'] . '"><i class="fa-solid fa-trash"></i> Eliminar</button>';
											echo '</div>';
											echo '</td>';
											echo '</tr>';
										}
									} else {
										echo '<tr><td colspan="11" class="text-center">No se encontraron conciliaciones</td></tr>';
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

	<!-- Add Conciliación Modal -->
	<div class="modal fade" id="modalAddConc" tabindex="-1" role="dialog" aria-labelledby="modalAddConcLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalAddConcLabel">Crear Conciliación</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="formAddConc">
					<div class="modal-body">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Fecha Conciliación</label>
								<input type="date" name="fecha_conc" class="form-control" required />
							</div>
							<div class="form-group col-md-6">
								<label>Accionante / Demandante</label>
								<input name="accionante_conc" class="form-control" required />
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Documento</label>
								<input name="doc_conc" class="form-control" required />
							</div>
							<div class="form-group col-md-6">
								<label>Causa / Litigio</label>
								<input name="causa_litigio_conc" class="form-control" required />
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Medio Control</label>
								<input name="medio_control_conc" class="form-control" required />
							</div>
							<div class="form-group col-md-6">
								<label>Procuraduría</label>
								<input name="procuraduria_conc" class="form-control" required />
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Radicado</label>
								<input name="rad_conc" class="form-control" required />
							</div>
							<div class="form-group col-md-6">
								<label>Estado</label>
								<select name="estado_conc" class="form-control" required>
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
								<textarea name="obs_conc" class="form-control" rows="3"></textarea>
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
		// Submit add conciliación via AJAX
		$('#formAddConc').on('submit', function(e){
			e.preventDefault();
			if (!this.checkValidity()) { 
				this.reportValidity(); 
				return; 
			}
			
			Swal.fire({ 
				title: 'Crear conciliación?', 
				text: 'Se creará una nueva conciliación con los datos ingresados', 
				icon: 'question', 
				showCancelButton: true, 
				confirmButtonText: 'Crear' 
			}).then(function(result){
				if (!result.isConfirmed) return;
				
				var data = $('#formAddConc').serialize();
				$.post('addconc.php', data).done(function(resp){
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

		// Delete conciliación
		$(document).on('click', '.btn-delete-conc', function(){
			var id = $(this).data('id');
			Swal.fire({ 
				title:'Eliminar?', 
				text:'¿Desea eliminar esta conciliación?', 
				icon:'warning', 
				showCancelButton:true, 
				confirmButtonText:'Eliminar' 
			}).then(function(res){
				if (!res.isConfirmed) return;
				
				$.post('deleteconc.php', { id_conc: id }).done(function(resp){
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

	<!-- Edit Conciliación Modal (contenido cargado vía AJAX) -->
	<div class="modal fade" id="modalEditConc" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content" id="modalEditConcContent">
				<!-- Contenido cargado dinámicamente -->
			</div>
		</div>
	</div>

	<script>
	$(function(){
		// Abrir modal de edición y cargar el fragmento
		$(document).on('click', '.open-edit-conc', function(){
			var id = $(this).data('id');
			$('#modalEditConcContent').html('<div class="p-4 text-center">Cargando...</div>');
			$('#modalEditConc').modal('show');
			$.get('editconc_modal.php', { id_conc: id }).done(function(html){
				$('#modalEditConcContent').html(html);
				// init select2 if available
				if (jQuery().select2) { $('#modalEditConcContent').find('#selectJur').select2({ width: '100%' }); }
			}).fail(function(xhr){
				$('#modalEditConcContent').html('<div class="p-3 text-danger">Error cargando formulario: HTTP '+xhr.status+'</div>');
			});
		});

		// Delegate submit from modal form
		$(document).on('submit', '#modalEditConcContent form#formEditConc', function(e){
			e.preventDefault();
			var form = this;
			if (!form.checkValidity()) { form.reportValidity(); return; }
			var data = $(form).serialize();
			$.post('editconc1.php', data).done(function(resp){
				try { var j = (typeof resp === 'string') ? JSON.parse(resp) : resp; } catch(e){ j = {success:false, message:'Respuesta no valida'}; }
				if (j.success) { Swal.fire('Actualizado', j.message, 'success').then(function(){ location.reload(); }); }
				else { Swal.fire('Error', j.message || 'No se pudo actualizar', 'error'); }
			}).fail(function(xhr){ Swal.fire('Error', 'HTTP '+xhr.status+': '+xhr.responseText, 'error'); });
		});
	});
	</script>

</body>
</html>