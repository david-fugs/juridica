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
	<title>JURIDICA - Consultar Demandas</title>
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
				max-width: 1200px;
				margin-left: auto;
				margin-right: auto;
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
							<b><i class="fa-solid fa-gavel"></i> CONSULTAR DEMANDAS</b>
						</h1>
					</div>

					<div class="card mt-4">
						<div class="card-header bg-primary text-white">
							<h5><i class="fa-solid fa-search"></i> Búsqueda de Demandas</h5>
						</div>
						<div class="card-body">
							<form action="showdemands.php" method="get" class="form-inline justify-content-center">
								<div class="form-group mx-2">
									<input name="accionante_dem" type="text" class="form-control" placeholder="Accionante | Demandante" size=30
										value="<?php echo isset($_GET['accionante_dem']) ? $_GET['accionante_dem'] : ''; ?>">
								</div>
								<div class="form-group mx-2">
									<input name="rad_dem" type="text" class="form-control" placeholder="Radicado No." size=20
										value="<?php echo isset($_GET['rad_dem']) ? $_GET['rad_dem'] : ''; ?>">
								</div>
								<div class="form-group mx-2">
									<input name="nom_jur" type="text" class="form-control" placeholder="Asignado a:" size=30
										value="<?php echo isset($_GET['nom_jur']) ? $_GET['nom_jur'] : ''; ?>">
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

					@$accionante_dem = ($_GET['accionante_dem']);
					@$rad_dem = ($_GET['rad_dem']);
					@$nom_jur = ($_GET['nom_jur']);

					// Paginación
					$resul_x_pagina = 25;
					$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
					$offset = ($page - 1) * $resul_x_pagina;

					// Construir query base
					$where_conditions = [];
					$params = [];

					if (!empty($accionante_dem)) {
						$where_conditions[] = "accionante_dem LIKE ?";
						$params[] = "%$accionante_dem%";
					}
					if (!empty($rad_dem)) {
						$where_conditions[] = "rad_dem LIKE ?";
						$params[] = "%$rad_dem%";
					}
					if (!empty($nom_jur)) {
						$where_conditions[] = "nom_jur LIKE ?";
						$params[] = "%$nom_jur%";
					}

					$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

					// Contar total de registros
					// Usamos la tabla usuarios (documento) para enlazar el abogado asignado
					$count_query = "SELECT COUNT(*) as total FROM demandas LEFT JOIN usuarios ON demandas.doc_jur=usuarios.documento $where_clause";
					if (!empty($params)) {
						$stmt = $mysqli->prepare($count_query);
						if (!empty($params)) $stmt->bind_param(str_repeat('s', count($params)), ...$params);
						$stmt->execute();
						$count_result = $stmt->get_result();
					} else {
						$count_result = $mysqli->query($count_query);
					}
					$total_records = $count_result->fetch_assoc()['total'];
					$total_pages = ceil($total_records / $resul_x_pagina);

					// Query principal con LIMIT
					// Seleccionamos también el nombre del abogado desde usuarios (si existe)
					$main_query = "SELECT demandas.*, usuarios.nombre as nom_jur FROM demandas LEFT JOIN usuarios ON demandas.doc_jur=usuarios.documento $where_clause ORDER BY fecha_dem DESC LIMIT $resul_x_pagina OFFSET $offset";
					if (!empty($params)) {
						$stmt = $mysqli->prepare($main_query);
						if (!empty($params)) $stmt->bind_param(str_repeat('s', count($params)), ...$params);
						$stmt->execute();
						$result = $stmt->get_result();
					} else {
						$result = $mysqli->query($main_query);
					}

					if ($total_records > 0) {
					?>
						<div class="table-container">
							<div class="d-flex justify-content-between align-items-center mb-3">
								<h5 class="text-primary">
									<i class="fa-solid fa-list"></i> Resultados encontrados: <?php echo $total_records; ?>
									(Página <?php echo $page; ?> de <?php echo $total_pages; ?>)
								</h5>
								<button class="btn btn-success" data-toggle="modal" data-target="#addDemandModal">
									<i class="fa-solid fa-plus"></i> Agregar Demanda
								</button>
							</div>

							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<thead class="thead-dark">
										<tr>
											<th>#</th>
											<th><i class="fa-solid fa-calendar"></i> Fecha</th>
											<th><i class="fa-solid fa-user"></i> Accionante</th>
											<th><i class="fa-solid fa-file-contract"></i> Radicado</th>
											<th><i class="fa-solid fa-building-columns"></i> Despacho</th>
											<th><i class="fa-solid fa-user-tie"></i> Abogado</th>
											<th><i class="fa-solid fa-info-circle"></i> Estado</th>
											<th><i class="fa-solid fa-gear"></i> Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$contador = $offset + 1;
										while ($row = $result->fetch_assoc()) {
											$estado_badge = ($row['estado_dem'] == 1) ?
												'<span class="badge-role badge-active">Activo</span>' :
												'<span class="badge-role badge-inactive">Inactivo</span>';
										?>
											<tr>
												<td><?php echo $contador; ?></td>
												<td><?php echo date('d/m/Y', strtotime($row['fecha_dem'])); ?></td>
												<td><strong><?php echo htmlspecialchars($row['accionante_dem']); ?></strong></td>
												<td><?php echo htmlspecialchars($row['rad_dem']); ?></td>
												<td><?php echo htmlspecialchars($row['desp_judi_dem']); ?></td>
												<td><?php echo htmlspecialchars($row['nom_jur']); ?></td>
												<td><?php echo htmlspecialchars(substr($row['est_act_proc_dem'], 0, 50)) . (strlen($row['est_act_proc_dem']) > 50 ? '...' : ''); ?></td>
												<td>
													<div class="action-group">
														<a href="editdemands.php?id_dem=<?php echo $row['id_dem']; ?>" class="btn-action btn-edit-custom" title="Editar">
															<i class="fa-solid fa-pen"></i>
														</a>
														<button class="btn-action btn-delete-custom" data-id="<?php echo $row['id_dem']; ?>" title="Eliminar">
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
								<nav aria-label="Paginación de demandas">
									<ul class="pagination">
										<?php if ($page > 1): ?>
											<li class="page-item">
												<a class="page-link" href="?page=<?php echo ($page - 1); ?>&accionante_dem=<?php echo urlencode($accionante_dem); ?>&rad_dem=<?php echo urlencode($rad_dem); ?>&nom_jur=<?php echo urlencode($nom_jur); ?>">
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
												<a class="page-link" href="?page=<?php echo $i; ?>&accionante_dem=<?php echo urlencode($accionante_dem); ?>&rad_dem=<?php echo urlencode($rad_dem); ?>&nom_jur=<?php echo urlencode($nom_jur); ?>">
													<?php echo $i; ?>
												</a>
											</li>
										<?php endfor; ?>

										<?php if ($page < $total_pages): ?>
											<li class="page-item">
												<a class="page-link" href="?page=<?php echo ($page + 1); ?>&accionante_dem=<?php echo urlencode($accionante_dem); ?>&rad_dem=<?php echo urlencode($rad_dem); ?>&nom_jur=<?php echo urlencode($nom_jur); ?>">
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
							<h4><i class="fa-solid fa-exclamation-triangle"></i> No se encontraron demandas</h4>
							<p>No hay demandas que coincidan con los criterios de búsqueda.</p>
							<button class="btn btn-success" data-toggle="modal" data-target="#addDemandModal">
								<i class="fa-solid fa-plus"></i> Crear Primera Demanda
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
					title: '¿Eliminar demanda? ',
					text: 'Esta acción eliminará la demanda permanentemente.',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Sí, eliminar',
					cancelButtonText: 'Cancelar'
				}).then(function(result){
					if(result.isConfirmed){
						$.ajax({
							url: 'deletedemand.php',
							method: 'POST',
							data: { id_dem: id },
							dataType: 'json'
						}).done(function(resp){
							if(resp && resp.success){
								Swal.fire('Eliminado', resp.message || 'Demanda eliminada', 'success').then(function(){
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

	<!-- Add Demand Modal -->
	<div class="modal fade" id="addDemandModal" tabindex="-1" role="dialog" aria-labelledby="addDemandModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addDemandModalLabel">
						<i class="fa-solid fa-plus"></i> Agregar Nueva Demanda
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="formAddDemand">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="add_fecha_dem">Fecha Oficina <span class="text-danger">*</span></label>
									<input type="date" class="form-control" id="add_fecha_dem" name="fecha_dem" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="add_accionante_dem">Accionante | Demandante <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="add_accionante_dem" name="accionante_dem"
										style="text-transform:uppercase;" required>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="add_doc_dem">Documento</label>
									<input type="text" class="form-control" id="add_doc_dem" name="doc_dem"
										style="text-transform:uppercase;">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<label for="add_rad_dem">Radicación <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="add_rad_dem" name="rad_dem" required>
								</div>
							</div>
							<div class="col-md-7">
								<div class="form-group">
									<label for="add_desp_judi_dem">Despacho Judicial</label>
									<input type="text" class="form-control" id="add_desp_judi_dem" name="desp_judi_dem">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="add_est_act_proc_dem">Estado Actual del Proceso</label>
							<textarea class="form-control" id="add_est_act_proc_dem" name="est_act_proc_dem"
								rows="3" style="text-transform:uppercase;"></textarea>
						</div>

						<div class="row">
							<div class="col-md-8">
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
							<div class="col-md-4">
								<div class="form-group">
									<label for="add_interno_dem">Interno</label>
									<input type="text" class="form-control" id="add_interno_dem" name="interno_dem"
										style="text-transform:uppercase;">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="add_obs_dem">Observaciones y/o Comentarios Adicionales</label>
							<textarea class="form-control" id="add_obs_dem" name="obs_dem"
								rows="2" style="text-transform:uppercase;"></textarea>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="button" id="btnSaveDemand" class="btn btn-success">
						<i class="fa-solid fa-save"></i> Guardar Demanda
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
			$('#add_fecha_dem').val(new Date().toISOString().split('T')[0]);

			// Save demand via AJAX
			$('#btnSaveDemand').on('click', function() {
				var form = $('#formAddDemand');
				var data = form.serialize();

				// Use HTML5 form validation first (lets browser show required messages)
				var nativeForm = document.getElementById('formAddDemand');
				if(!nativeForm.checkValidity()){
					// Show browser validation messages
					nativeForm.reportValidity();
					return;
				}

				// Gather values to show in confirmation (omit fecha since it is prefilled)
				var accionante = $.trim($('#add_accionante_dem').val() || '');
				var radicado = $.trim($('#add_rad_dem').val() || '');
				var abogadoText = $("#add_doc_jur option:selected").text() || '';

				Swal.fire({
					title: 'Confirme los datos',
					html:
						'<b>Accionante:</b> ' + $('<div>').text(accionante).html() + '<br/>' +
						'<b>Radicación:</b> ' + $('<div>').text(radicado).html() + '<br/>' +
						'<b>Abogado:</b> ' + $('<div>').text(abogadoText).html(),
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Confirmar y guardar',
					cancelButtonText: 'Cancelar'
				}).then(function(result){
					if(result.isConfirmed){
						$.post('adddemand.php', data, function(resp) {
							if (resp.success) {
								$('#addDemandModal').modal('hide');
								Swal.fire({
									icon: 'success',
									title: 'Demanda creada',
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
			$('#addDemandModal').on('hidden.bs.modal', function() {
				$('#formAddDemand')[0].reset();
				$('#add_fecha_dem').val(new Date().toISOString().split('T')[0]);
			});
		});
	</script>

</body>

</html>