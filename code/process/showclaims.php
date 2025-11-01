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
	<met		<script src="js/app.js"></script>
		<script src="https://www.jose-aguilar.com/scripts/fontawesome/js/all.min.js" data-auto-replace-svg="nest"></script>

	</body>
</html>
	<title>JURIDICA - Consultar Reclamaciones</title>
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
			max-width: 1200px;
			margin-left: auto;
			margin-right: auto;
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
						<h1 style="color: #412fd1; text-shadow: #FFFFFF 0.1em 0.1em 0.2em">
							<b><i class="fa-solid fa-building-shield"></i> CONSULTAR RECLAMACIONES</b>
						</h1>
					</div>

					<div class="card mt-4">
						<div class="card-header bg-primary text-white">
							<h5><i class="fa-solid fa-search"></i> Búsqueda de Reclamaciones</h5>
						</div>
						<div class="card-body">
							<form action="showclaims.php" method="get" class="form-inline justify-content-center">
								<div class="form-group mx-2">
									<input name="nom_rec" type="text" class="form-control" placeholder="Solicitante" size=30
										value="<?php echo isset($_GET['nom_rec']) ? $_GET['nom_rec'] : ''; ?>">
								</div>
								<div class="form-group mx-2">
									<input name="rad_rec" type="text" class="form-control" placeholder="Radicado No." size=20
										value="<?php echo isset($_GET['rad_rec']) ? $_GET['rad_rec'] : ''; ?>">
								</div>
								<div class="form-group mx-2">
									<input name="nom_jur" type="text" class="form-control" placeholder="Asignado a:" size=30
										value="<?php echo isset($_GET['nom_jur']) ? $_GET['nom_jur'] : ''; ?>">
								</div>
								<div class="form-group mx-2">
                    <select name="estado" class="form-control">
                        <option value="">Todos los estados</option>
                        <option value="activa" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'activa') ? 'selected' : ''; ?>>Activas</option>
                        <option value="realizada" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'realizada') ? 'selected' : ''; ?>>Realizadas</option>
                    </select>
                </div>
                <div class="form-group mx-2">
                    <button type="submit" class="btn btn-success">
										<i class="fa-solid fa-search"></i> Buscar
									</button>
								</div>
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
			}

			@$nom_rec = ($_GET['nom_rec']);
			@$rad_rec = ($_GET['rad_rec']);
			@$nom_jur = ($_GET['nom_jur']);
			@$estado_filter = isset($_GET['estado']) ? $_GET['estado'] : '';
					$resul_x_pagina = 25;
					$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
					$offset = ($page - 1) * $resul_x_pagina;

				// Construir query base - cambiar a tabla usuarios
				$where_conditions = [];
				$params = [];

		// Si es tipo_usuario = 1 (abogado), solo ver reclamaciones asignadas a él (filtrar por doc_jur)
		if ($tipo_usuario == 1 && !empty($doc_usuario_actual)) {
			$where_conditions[] = "reclamaciones.doc_jur = ?";
			$params[] = $doc_usuario_actual;
		}
		
		// Filtro por estado realizada
		if ($estado_filter === 'realizada') {
			$where_conditions[] = "reclamaciones.realizada = 1";
		} elseif ($estado_filter === 'activa') {
			$where_conditions[] = "(reclamaciones.realizada = 0 OR reclamaciones.realizada IS NULL)";
		}
		
		if (!empty($nom_rec)) {
					$where_conditions[] = "nom_rec LIKE ?";
					$params[] = "%$nom_rec%";
				}
				if (!empty($rad_rec)) {
					$where_conditions[] = "rad_rec LIKE ?";
					$params[] = "%$rad_rec%";
				}
				if (!empty($nom_jur)) {
					$where_conditions[] = "nom_jur LIKE ?";
					$params[] = "%$nom_jur%";
				}

				$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

			// Contar total de registros - usar tabla usuarios
			$count_query = "SELECT COUNT(*) as total FROM reclamaciones LEFT JOIN usuarios ON reclamaciones.doc_jur=usuarios.documento $where_clause";
			if (!empty($params)) {
				$stmt = $mysqli->prepare($count_query);
				// Todos los parámetros son strings (documento es VARCHAR)
				$bind_types = str_repeat('s', count($params));
				$stmt->bind_param($bind_types, ...$params);
					$stmt->execute();
					$count_result = $stmt->get_result();
				} else {
					$count_result = $mysqli->query($count_query);
				}
				$total_records = $count_result->fetch_assoc()['total'];
				$total_pages = ceil($total_records / $resul_x_pagina);

				// Query principal con LIMIT - cambiar a usuarios.nombre as nom_jur
				$main_query = "SELECT reclamaciones.*, usuarios.nombre as nom_jur FROM reclamaciones LEFT JOIN usuarios ON reclamaciones.doc_jur=usuarios.documento $where_clause ORDER BY fecha_rec DESC LIMIT $resul_x_pagina OFFSET $offset";
				if (!empty($params)) {
					$stmt = $mysqli->prepare($main_query);
					$stmt->bind_param($bind_types, ...$params);
					$stmt->execute();
					$result = $stmt->get_result();
				} else {
					$result = $mysqli->query($main_query);
				}					if ($total_records > 0) {
					?>
						<div class="table-container">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h5 class="text-primary">
							<i class="fa-solid fa-list"></i> Resultados encontrados: <?php echo $total_records; ?>
							(Página <?php echo $page; ?> de <?php echo $total_pages; ?>)
						</h5>
					<div>
						<a href="export_claims_excel.php?<?php echo http_build_query($_GET); ?>" class="btn btn-success">
							<i class="fa-solid fa-file-excel"></i> Exportar a Excel
						</a>
						<button class="btn btn-success ml-2" data-toggle="modal" data-target="#addClaimModal">
							<i class="fa-solid fa-plus"></i> Agregar Reclamación
						</button>
					</div>
					</div>							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<thead class="thead-dark">
										<tr>
											<th>#</th>
											<th><i class="fa-solid fa-calendar"></i> Fecha</th>
											<th><i class="fa-solid fa-user"></i> Solicitante</th>
											<th><i class="fa-solid fa-file-text"></i> Reclamación</th>
											<th><i class="fa-solid fa-file-contract"></i> Radicado</th>
											<th><i class="fa-solid fa-user-tie"></i> Abogado</th>
											<th><i class="fa-solid fa-info-circle"></i> Estado</th>
											<th><i class="fa-solid fa-gear"></i> Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$contador = $offset + 1;
										while ($row = $result->fetch_assoc()) {
										?>
											<tr>
												<td><?php echo $contador; ?></td>
												<td><?php echo date('d/m/Y', strtotime($row['fecha_rec'])); ?></td>
												<td><strong><?php echo htmlspecialchars($row['nom_rec']); ?></strong></td>
												<td><?php echo htmlspecialchars(substr($row['reclamacion_rec'], 0, 50)) . (strlen($row['reclamacion_rec']) > 50 ? '...' : ''); ?></td>
												<td><?php echo htmlspecialchars($row['rad_rec']); ?></td>
												<td><?php echo htmlspecialchars($row['nom_jur']); ?></td>
												<td><?php echo htmlspecialchars(substr($row['est_res_rec'], 0, 40)) . (strlen($row['est_res_rec']) > 40 ? '...' : ''); ?></td>
												<td>
													<div class="action-group">
														<a href="editclaims.php?id_rec=<?php echo $row['id_rec']; ?>" class="btn-action btn-edit-custom" title="Editar">
															<i class="fa-solid fa-pen"></i>
														</a>
														<button class="btn-action btn-delete-custom" data-id="<?php echo $row['id_rec']; ?>" title="Eliminar">
															<i class="fa-solid fa-trash"></i>
														</button>
													</div>
												</td>
											</tr>
										<?php
											$contador++;
										}
										?>
									</tbody>
								</table>
							</div>

							<!-- Paginación -->
							<?php if ($total_pages > 1): ?>
								<nav aria-label="Paginación de reclamaciones">
									<ul class="pagination">
										<?php if ($page > 1): ?>
											<li class="page-item">
												<a class="page-link" href="?page=<?php echo ($page - 1); ?>&nom_rec=<?php echo urlencode($nom_rec); ?>&rad_rec=<?php echo urlencode($rad_rec); ?>&nom_jur=<?php echo urlencode($nom_jur); ?>">
													<i class="fa-solid fa-chevron-left"></i> Anterior
												</a>
											</li>
										<?php endif; ?>

										<?php
										$start = max(1, $page - 2);
										$end = min($total_pages, $page + 2);
										for ($i = $start; $i <= $end; $i++):
										?>
											<li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
												<a class="page-link" href="?page=<?php echo $i; ?>&nom_rec=<?php echo urlencode($nom_rec); ?>&rad_rec=<?php echo urlencode($rad_rec); ?>&nom_jur=<?php echo urlencode($nom_jur); ?>">
													<?php echo $i; ?>
												</a>
											</li>
										<?php endfor; ?>

										<?php if ($page < $total_pages): ?>
											<li class="page-item">
												<a class="page-link" href="?page=<?php echo ($page + 1); ?>&nom_rec=<?php echo urlencode($nom_rec); ?>&rad_rec=<?php echo urlencode($rad_rec); ?>&nom_jur=<?php echo urlencode($nom_jur); ?>">
													Siguiente <i class="fa-solid fa-chevron-right"></i>
												</a>
											</li>
										<?php endif; ?>
									</ul>
								</nav>
							<?php endif; ?>
						</div>
					<?php
					} else {
					?>
						<div class="alert alert-warning text-center mt-4">
							<h4><i class="fa-solid fa-exclamation-triangle"></i> No se encontraron reclamaciones</h4>
							<p>No hay reclamaciones que coincidan con los criterios de búsqueda.</p>
							<div>
                    <a href="export_claims.php?<?php echo http_build_query($_GET); ?>" class="btn btn-success">
                        <i class="fa-solid fa-file-excel"></i> Exportar a Excel
                    </a>
                    <button class="btn btn-success ml-2" data-toggle="modal" data-target="#addClaimModal">
								<i class="fa-solid fa-plus"></i> Crear Primera Reclamación
							</button>
						</div>
					<?php
					}
					?>
					<div class="text-center mt-4 mb-4">
						<a href="../../access.php" class="btn btn-secondary btn-lg">
							<i class="fa-solid fa-arrow-left"></i> Volver al Menú Principal
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="../../js/jquery.min.js"></script>
	<script>
		// Ensure SweetAlert2 is available
		function ensureSwal(callback){
			if(typeof Swal !== 'undefined') return callback();
			var s = document.createElement('script');
			s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
			s.onload = function(){ callback(); };
			document.head.appendChild(s);
		}

		ensureSwal(function(){
			$(document).on('click', '.btn-delete-custom', function(e){
				e.preventDefault();
				var id = $(this).data('id');
				Swal.fire({
					title: '¿Eliminar reclamación?',
					text: 'Esta acción eliminará la reclamación permanentemente.',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Sí, eliminar',
					cancelButtonText: 'Cancelar'
				}).then(function(result){
					if(result.isConfirmed){
						$.ajax({
							url: 'deleteclaim.php',
							method: 'POST',
							data: { id_rec: id },
							dataType: 'json'
						}).done(function(resp){
							if(resp && resp.success){
								Swal.fire('Eliminado', resp.message || 'Reclamación eliminada', 'success').then(function(){
									// reload to refresh list
									location.reload();
								});
							} else {
								Swal.fire('Error', (resp && resp.message) ? resp.message : 'Error al eliminar', 'error');
							}
						}).fail(function(jqXHR, textStatus){
							Swal.fire('Error', 'No se pudo contactar con el servidor: ' + textStatus, 'error');
						});
					}
				});
			});
		});
	</script>

	<!-- Add Claim Modal -->
	<div class="modal fade" id="addClaimModal" tabindex="-1" role="dialog" aria-labelledby="addClaimModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addClaimModalLabel">
						<i class="fa-solid fa-plus"></i> Agregar Nueva Reclamación
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="formAddClaim">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="add_fecha_rec">Fecha Oficina <span class="text-danger">*</span></label>
									<input type="date" class="form-control" id="add_fecha_rec" name="fecha_rec" required>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label for="add_nom_rec">Nombres y Apellidos <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="add_nom_rec" name="nom_rec"
										style="text-transform:uppercase;" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="add_reclamacion_rec">Reclamación <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="add_reclamacion_rec" name="reclamacion_rec"
										style="text-transform:uppercase;" required>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="add_rad_rec">Radicación <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="add_rad_rec" name="rad_rec" required>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label for="add_doc_jur">Abogado Asignado <span class="text-danger">*</span></label>
									<select name="doc_jur" class="form-control" id="add_doc_jur" required>
										<option value="">Seleccione un abogado</option>
										<?php
										// Poblar select desde la tabla usuarios (documento => nombre)
										// Filtramos por tipo_usuario = 2 (abogados)
										$consulta_jur = "SELECT documento, nombre FROM usuarios WHERE tipo_usuario = '2' ORDER BY nombre ASC";
										$res_jur = $mysqli->query($consulta_jur);
										if ($res_jur) {
											while ($row_jur = $res_jur->fetch_assoc()) {
												// value será el documento, label el nombre
												echo '<option value="' . htmlspecialchars($row_jur['documento']) . '">' . htmlspecialchars($row_jur['nombre']) . '</option>';
											}
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="add_est_res_rec">Estado / Respuesta</label>
									<input type="text" class="form-control" id="add_est_res_rec" name="est_res_rec"
										style="text-transform:uppercase;">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="add_obs_rec">Observaciones y/o Comentarios Adicionales</label>
							<textarea class="form-control" id="add_obs_rec" name="obs_rec"
								rows="2" style="text-transform:uppercase;"></textarea>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="button" id="btnSaveClaim" class="btn btn-success">
						<i class="fa-solid fa-save"></i> Guardar Reclamación
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Scripts: jQuery (full), Popper, Bootstrap, SweetAlert2 -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		$(document).ready(function() {
			// Set today's date as default
			$('#add_fecha_rec').val(new Date().toISOString().split('T')[0]);

			// Save claim via AJAX
			$('#btnSaveClaim').on('click', function() {
				var form = $('#formAddClaim');
				var data = form.serialize();

				// Use HTML5 form validation first (lets browser show required messages)
				var nativeForm = document.getElementById('formAddClaim');
				if(!nativeForm.checkValidity()){
					// Show browser validation messages
					nativeForm.reportValidity();
					return;
				}

				// Gather values to show in confirmation
				var nombre = $.trim($('#add_nom_rec').val() || '');
				var reclamacion = $.trim($('#add_reclamacion_rec').val() || '');
				var radicado = $.trim($('#add_rad_rec').val() || '');
				var abogadoText = $("#add_doc_jur option:selected").text() || '';

				Swal.fire({
					title: 'Confirme los datos',
					html:
						'<b>Solicitante:</b> ' + $('<div>').text(nombre).html() + '<br/>' +
						'<b>Reclamación:</b> ' + $('<div>').text(reclamacion).html() + '<br/>' +
						'<b>Radicación:</b> ' + $('<div>').text(radicado).html() + '<br/>' +
						'<b>Abogado:</b> ' + $('<div>').text(abogadoText).html(),
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Confirmar y guardar',
					cancelButtonText: 'Cancelar'
				}).then(function(result){
					if(result.isConfirmed){
						$.post('addclaim.php', data, function(resp) {
							if (resp.success) {
								$('#addClaimModal').modal('hide');
								Swal.fire({
									icon: 'success',
									title: 'Reclamación creada',
									text: resp.message,
									timer: 1800,
									showConfirmButton: false
								}).then(function() {
									location.reload();
								});
							} else {
								Swal.fire({
									icon: 'error',
									title: 'Error',
									text: resp.message
								});
							}
						}, 'json').fail(function() {
							Swal.fire({
								icon: 'error',
								title: 'Error',
								text: 'Error en la petición'
							});
						});
					}
				});
			});

			// Clear form when modal closes
			$('#addClaimModal').on('hidden.bs.modal', function() {
				$('#formAddClaim')[0].reset();
				$('#add_fecha_rec').val(new Date().toISOString().split('T')[0]);
			});
		});
	</script>
		<script src="js/app.js"></script>
		<script src="https://www.jose-aguilar.com/scripts/fontawesome/js/all.min.js" data-auto-replace-svg="nest"></script>

	</body>
</html>