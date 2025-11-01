<?php
session_start();

if (!isset($_SESSION['id'])) {
  header("Location: index.php");
}

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$id_usuario = $_SESSION['id'];

// Conectar a la base de datos
include("conexion.php");

// Obtener documento del usuario si es abogado
$doc_usuario_actual = null;
if ($tipo_usuario == 1 || $tipo_usuario == '1') {
    $sql_doc = "SELECT documento FROM usuarios WHERE id = ? LIMIT 1";
    $stmt_doc = $mysqli->prepare($sql_doc);
    $stmt_doc->bind_param("i", $id_usuario);
    $stmt_doc->execute();
    $res_doc = $stmt_doc->get_result();
    if ($res_doc && $res_doc->num_rows > 0) {
        $row_doc = $res_doc->fetch_assoc();
        $doc_usuario_actual = $row_doc['documento'];
    }
}

// Construir filtro WHERE según tipo de usuario
$where_admin = "";
$where_abogado = "";
if ($tipo_usuario == 1 && !empty($doc_usuario_actual)) {
    $where_abogado = " WHERE doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'";
}

// Estadísticas generales
$stats = [];

// DEMANDAS
$sql_demandas_total = "SELECT COUNT(*) as total FROM demandas" . $where_abogado;
$sql_demandas_activas = "SELECT COUNT(*) as total FROM demandas WHERE (realizada = 0 OR realizada IS NULL)" . ($tipo_usuario == 1 ? " AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "");
$sql_demandas_realizadas = "SELECT COUNT(*) as total FROM demandas WHERE realizada = 1" . ($tipo_usuario == 1 ? " AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "");

$stats['demandas_total'] = $mysqli->query($sql_demandas_total)->fetch_assoc()['total'];
$stats['demandas_activas'] = $mysqli->query($sql_demandas_activas)->fetch_assoc()['total'];
$stats['demandas_realizadas'] = $mysqli->query($sql_demandas_realizadas)->fetch_assoc()['total'];

// RECLAMACIONES
$sql_reclamaciones_total = "SELECT COUNT(*) as total FROM reclamaciones" . $where_abogado;
$sql_reclamaciones_activas = "SELECT COUNT(*) as total FROM reclamaciones WHERE (realizada = 0 OR realizada IS NULL)" . ($tipo_usuario == 1 ? " AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "");
$sql_reclamaciones_realizadas = "SELECT COUNT(*) as total FROM reclamaciones WHERE realizada = 1" . ($tipo_usuario == 1 ? " AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "");

$stats['reclamaciones_total'] = $mysqli->query($sql_reclamaciones_total)->fetch_assoc()['total'];
$stats['reclamaciones_activas'] = $mysqli->query($sql_reclamaciones_activas)->fetch_assoc()['total'];
$stats['reclamaciones_realizadas'] = $mysqli->query($sql_reclamaciones_realizadas)->fetch_assoc()['total'];

// TUTELAS
$sql_tutelas_total = "SELECT COUNT(*) as total FROM tutelas" . $where_abogado;
$sql_tutelas_activas = "SELECT COUNT(*) as total FROM tutelas WHERE (realizada = 0 OR realizada IS NULL)" . ($tipo_usuario == 1 ? " AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "");
$sql_tutelas_realizadas = "SELECT COUNT(*) as total FROM tutelas WHERE realizada = 1" . ($tipo_usuario == 1 ? " AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "");

$stats['tutelas_total'] = $mysqli->query($sql_tutelas_total)->fetch_assoc()['total'];
$stats['tutelas_activas'] = $mysqli->query($sql_tutelas_activas)->fetch_assoc()['total'];
$stats['tutelas_realizadas'] = $mysqli->query($sql_tutelas_realizadas)->fetch_assoc()['total'];

// CONCILIACIONES
$sql_conciliaciones_total = "SELECT COUNT(*) as total FROM conciliaciones" . $where_abogado;
$sql_conciliaciones_activas = "SELECT COUNT(*) as total FROM conciliaciones WHERE (realizada = 0 OR realizada IS NULL)" . ($tipo_usuario == 1 ? " AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "");
$sql_conciliaciones_realizadas = "SELECT COUNT(*) as total FROM conciliaciones WHERE realizada = 1" . ($tipo_usuario == 1 ? " AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "");

$stats['conciliaciones_total'] = $mysqli->query($sql_conciliaciones_total)->fetch_assoc()['total'];
$stats['conciliaciones_activas'] = $mysqli->query($sql_conciliaciones_activas)->fetch_assoc()['total'];
$stats['conciliaciones_realizadas'] = $mysqli->query($sql_conciliaciones_realizadas)->fetch_assoc()['total'];

// Total general
$stats['total_general'] = $stats['demandas_total'] + $stats['reclamaciones_total'] + $stats['tutelas_total'] + $stats['conciliaciones_total'];
$stats['total_activas'] = $stats['demandas_activas'] + $stats['reclamaciones_activas'] + $stats['tutelas_activas'] + $stats['conciliaciones_activas'];
$stats['total_realizadas'] = $stats['demandas_realizadas'] + $stats['reclamaciones_realizadas'] + $stats['tutelas_realizadas'] + $stats['conciliaciones_realizadas'];

// Casos críticos (más de 20 días)
$today = date('Y-m-d');
$sql_criticos = "
    SELECT 'Demanda' as tipo, accionante_dem as nombre, rad_dem as radicado, auto_admisorio, 
           DATEDIFF('$today', auto_admisorio) as dias
    FROM demandas 
    WHERE auto_admisorio IS NOT NULL 
      AND auto_admisorio != '0000-00-00' 
      AND (realizada = 0 OR realizada IS NULL)
      AND DATEDIFF('$today', auto_admisorio) >= 20
      " . ($tipo_usuario == 1 ? "AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "") . "
    UNION ALL
    SELECT 'Reclamación' as tipo, nom_rec as nombre, rad_rec as radicado, auto_admisorio,
           DATEDIFF('$today', auto_admisorio) as dias
    FROM reclamaciones 
    WHERE auto_admisorio IS NOT NULL 
      AND auto_admisorio != '0000-00-00' 
      AND (realizada = 0 OR realizada IS NULL)
      AND DATEDIFF('$today', auto_admisorio) >= 20
      " . ($tipo_usuario == 1 ? "AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "") . "
    UNION ALL
    SELECT 'Tutela' as tipo, nom_tut as nombre, '' as radicado, auto_admisorio,
           DATEDIFF('$today', auto_admisorio) as dias
    FROM tutelas 
    WHERE auto_admisorio IS NOT NULL 
      AND auto_admisorio != '0000-00-00' 
      AND (realizada = 0 OR realizada IS NULL)
      AND DATEDIFF('$today', auto_admisorio) >= 20
      " . ($tipo_usuario == 1 ? "AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "") . "
    UNION ALL
    SELECT 'Conciliación' as tipo, accionante_conc as nombre, rad_conc as radicado, auto_admisorio,
           DATEDIFF('$today', auto_admisorio) as dias
    FROM conciliaciones 
    WHERE auto_admisorio IS NOT NULL 
      AND auto_admisorio != '0000-00-00' 
      AND (realizada = 0 OR realizada IS NULL)
      AND DATEDIFF('$today', auto_admisorio) >= 20
      " . ($tipo_usuario == 1 ? "AND doc_jur = '" . $mysqli->real_escape_string($doc_usuario_actual) . "'" : "") . "
    ORDER BY dias DESC
    LIMIT 5
";

$result_criticos = $mysqli->query($sql_criticos);
$casos_criticos = [];
while ($row = $result_criticos->fetch_assoc()) {
    $casos_criticos[] = $row;
}

// Si es admin, obtener top abogados
$top_abogados = [];
if ($tipo_usuario == 2) {
    $sql_abogados = "
        SELECT u.nombre, u.documento,
               (SELECT COUNT(*) FROM demandas WHERE doc_jur = u.documento) +
               (SELECT COUNT(*) FROM reclamaciones WHERE doc_jur = u.documento) +
               (SELECT COUNT(*) FROM tutelas WHERE doc_jur = u.documento) +
               (SELECT COUNT(*) FROM conciliaciones WHERE doc_jur = u.documento) as total_casos,
               (SELECT COUNT(*) FROM demandas WHERE doc_jur = u.documento AND (realizada = 0 OR realizada IS NULL)) +
               (SELECT COUNT(*) FROM reclamaciones WHERE doc_jur = u.documento AND (realizada = 0 OR realizada IS NULL)) +
               (SELECT COUNT(*) FROM tutelas WHERE doc_jur = u.documento AND (realizada = 0 OR realizada IS NULL)) +
               (SELECT COUNT(*) FROM conciliaciones WHERE doc_jur = u.documento AND (realizada = 0 OR realizada IS NULL)) as casos_activos
        FROM usuarios u
        WHERE u.tipo_usuario = 1
        ORDER BY total_casos DESC
        LIMIT 5
    ";
    $result_abogados = $mysqli->query($sql_abogados);
    while ($row = $result_abogados->fetch_assoc()) {
        $top_abogados[] = $row;
    }
}
?>

<!DOCTYPE html>
<!-- Coding by CodingNepal || www.codingnepalweb.com -->
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Boxicons CSS -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
  <title>JURIDICA</title>
  <link rel="stylesheet" href="menu/style.css" />
</head>

<body>
  <!-- navbar -->
  <nav class="navbar">
    <div class="logo_item">
      <i class="bx bx-menu" id="sidebarOpen"></i>
      <img src="img/gobersecre.png" alt=""></i>PROCESOS ASIGNADOS
    </div>

    <div class="search_bar">
      <input type="text" placeholder="Buscar..." />
    </div>

    <div class="navbar_content">
      <i class="bi bi-grid"></i>
      <i class="fa-solid fa-sun" id="darkLight"></i><!--<i class='bx bx-sun' id="darkLight"></i>-->
      <a href="logout.php"> <i class="fa-solid fa-door-open"></i></a>
      <img src="img/gobersecre.png" alt="" class="profile" />
    </div>
  </nav>

  <!-- menu admin y abogados-->
  <?php if ($tipo_usuario == 2 || $tipo_usuario == 1) { ?>
    <!-- sidebar -->
    <nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dahsboard"></div>
          <!-- duplicate or remove this li tag if you want to add or remove navlink with submenu -->
          <!-- start -->
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-square-poll-horizontal"></i>
                <!--<i class="bx bx-home-alt"></i>-->
              </span>

              <span class="navlink">INGRESOS</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/process/addusers.php" class="nav_link sublink">Crear Usuarios</a>
              <a href="code/process/showusers.php" class="nav_link sublink">Consultar Usuarios</a>
              <!--<a href="code/process/addDemanda1.php" class="nav_link sublink">Demandas</a>
              <a href="code/process/addTutela1.php" class="nav_link sublink">Tutelas</a>
              <a href="code/process/addConcilia1.php" class="nav_link sublink">Conciliaciones</a>-->

              <!--<a href="#" class="nav_link sublink">Barrios</a>
              <a href="#" class="nav_link sublink">Operadores</a>-->
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-magnifying-glass"></i>
              </span>

              <span class="navlink">CONSULTAS</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/process/showclaims.php" class="nav_link sublink">Reclamaciones</a>
              <a href="code/process/showdemands.php" class="nav_link sublink">Demandas</a>
              <a href="code/process/showtut.php" class="nav_link sublink">Tutelas</a>
              <a href="code/process/showconciliation.php" class="nav_link sublink">Conciliaciones</a>
            </ul>
          </li>

          <!-- start -->
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-users"></i>
              </span>
              <span class="navlink">USUARIO</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="#" class="nav_link sublink">Contraseña</a>

            </ul>
          </li>
          <!-- end -->
        </ul>


        <!-- Sidebar Open / Close -->
        <div class="bottom_content">
          <div class="bottom expand_sidebar">
            <span> Expand</span>
            <i class='bx bx-log-in'></i>
          </div>
          <div class="bottom collapse_sidebar">
            <span> Collapse</span>
            <i class='bx bx-log-out'></i>
          </div>
        </div>
      </div>
    </nav>
  <?php } ?>

  <!-- Dashboard Content -->
  <div class="dashboard-container">
    <div class="dashboard-header">
      <h1><i class="fa-solid fa-chart-line"></i> Dashboard - <?php echo $tipo_usuario == 2 ? 'Administrador' : 'Mis Casos'; ?></h1>
      <p class="welcome-text">Bienvenido, <strong><?php echo htmlspecialchars($nombre); ?></strong></p>
    </div>

    <!-- Resumen General -->
    <div class="stats-grid">
      <div class="stat-card total">
        <div class="stat-icon">
          <i class="fa-solid fa-briefcase"></i>
        </div>
        <div class="stat-content">
          <h3><?php echo $stats['total_general']; ?></h3>
          <p>Total Casos</p>
        </div>
      </div>

      <div class="stat-card active">
        <div class="stat-icon">
          <i class="fa-solid fa-clock"></i>
        </div>
        <div class="stat-content">
          <h3><?php echo $stats['total_activas']; ?></h3>
          <p>Casos Activos</p>
        </div>
      </div>

      <div class="stat-card completed">
        <div class="stat-icon">
          <i class="fa-solid fa-check-circle"></i>
        </div>
        <div class="stat-content">
          <h3><?php echo $stats['total_realizadas']; ?></h3>
          <p>Casos Realizados</p>
        </div>
      </div>

      <div class="stat-card progress">
        <div class="stat-icon">
          <i class="fa-solid fa-percentage"></i>
        </div>
        <div class="stat-content">
          <h3><?php echo $stats['total_general'] > 0 ? round(($stats['total_realizadas'] / $stats['total_general']) * 100) : 0; ?>%</h3>
          <p>Tasa de Completitud</p>
        </div>
      </div>
    </div>

    <!-- Gráficas por tipo de caso -->
    <div class="charts-grid">
      <div class="chart-card">
        <h3><i class="fa-solid fa-gavel"></i> Demandas</h3>
        <div class="chart-content">
          <div class="chart-number"><?php echo $stats['demandas_total']; ?></div>
          <div class="chart-bars">
            <div class="bar-item">
              <span class="bar-label">Activas</span>
              <div class="bar-container">
                <div class="bar bar-active" style="width: <?php echo $stats['demandas_total'] > 0 ? ($stats['demandas_activas'] / $stats['demandas_total']) * 100 : 0; ?>%"></div>
              </div>
              <span class="bar-value"><?php echo $stats['demandas_activas']; ?></span>
            </div>
            <div class="bar-item">
              <span class="bar-label">Realizadas</span>
              <div class="bar-container">
                <div class="bar bar-completed" style="width: <?php echo $stats['demandas_total'] > 0 ? ($stats['demandas_realizadas'] / $stats['demandas_total']) * 100 : 0; ?>%"></div>
              </div>
              <span class="bar-value"><?php echo $stats['demandas_realizadas']; ?></span>
            </div>
          </div>
        </div>
      </div>

      <div class="chart-card">
        <h3><i class="fa-solid fa-file-lines"></i> Reclamaciones</h3>
        <div class="chart-content">
          <div class="chart-number"><?php echo $stats['reclamaciones_total']; ?></div>
          <div class="chart-bars">
            <div class="bar-item">
              <span class="bar-label">Activas</span>
              <div class="bar-container">
                <div class="bar bar-active" style="width: <?php echo $stats['reclamaciones_total'] > 0 ? ($stats['reclamaciones_activas'] / $stats['reclamaciones_total']) * 100 : 0; ?>%"></div>
              </div>
              <span class="bar-value"><?php echo $stats['reclamaciones_activas']; ?></span>
            </div>
            <div class="bar-item">
              <span class="bar-label">Realizadas</span>
              <div class="bar-container">
                <div class="bar bar-completed" style="width: <?php echo $stats['reclamaciones_total'] > 0 ? ($stats['reclamaciones_realizadas'] / $stats['reclamaciones_total']) * 100 : 0; ?>%"></div>
              </div>
              <span class="bar-value"><?php echo $stats['reclamaciones_realizadas']; ?></span>
            </div>
          </div>
        </div>
      </div>

      <div class="chart-card">
        <h3><i class="fa-solid fa-shield-halved"></i> Tutelas</h3>
        <div class="chart-content">
          <div class="chart-number"><?php echo $stats['tutelas_total']; ?></div>
          <div class="chart-bars">
            <div class="bar-item">
              <span class="bar-label">Activas</span>
              <div class="bar-container">
                <div class="bar bar-active" style="width: <?php echo $stats['tutelas_total'] > 0 ? ($stats['tutelas_activas'] / $stats['tutelas_total']) * 100 : 0; ?>%"></div>
              </div>
              <span class="bar-value"><?php echo $stats['tutelas_activas']; ?></span>
            </div>
            <div class="bar-item">
              <span class="bar-label">Realizadas</span>
              <div class="bar-container">
                <div class="bar bar-completed" style="width: <?php echo $stats['tutelas_total'] > 0 ? ($stats['tutelas_realizadas'] / $stats['tutelas_total']) * 100 : 0; ?>%"></div>
              </div>
              <span class="bar-value"><?php echo $stats['tutelas_realizadas']; ?></span>
            </div>
          </div>
        </div>
      </div>

      <div class="chart-card">
        <h3><i class="fa-solid fa-handshake"></i> Conciliaciones</h3>
        <div class="chart-content">
          <div class="chart-number"><?php echo $stats['conciliaciones_total']; ?></div>
          <div class="chart-bars">
            <div class="bar-item">
              <span class="bar-label">Activas</span>
              <div class="bar-container">
                <div class="bar bar-active" style="width: <?php echo $stats['conciliaciones_total'] > 0 ? ($stats['conciliaciones_activas'] / $stats['conciliaciones_total']) * 100 : 0; ?>%"></div>
              </div>
              <span class="bar-value"><?php echo $stats['conciliaciones_activas']; ?></span>
            </div>
            <div class="bar-item">
              <span class="bar-label">Realizadas</span>
              <div class="bar-container">
                <div class="bar bar-completed" style="width: <?php echo $stats['conciliaciones_total'] > 0 ? ($stats['conciliaciones_realizadas'] / $stats['conciliaciones_total']) * 100 : 0; ?>%"></div>
              </div>
              <span class="bar-value"><?php echo $stats['conciliaciones_realizadas']; ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Casos Críticos y Top Abogados -->
    <div class="info-grid">
      <div class="info-card critical">
        <h3><i class="fa-solid fa-exclamation-triangle"></i> Casos Críticos (≥20 días)</h3>
        <?php if (count($casos_criticos) > 0): ?>
          <div class="critical-list">
            <?php foreach ($casos_criticos as $caso): ?>
              <div class="critical-item">
                <div class="critical-type">
                  <span class="badge badge-<?php echo strtolower($caso['tipo']); ?>"><?php echo $caso['tipo']; ?></span>
                </div>
                <div class="critical-info">
                  <strong><?php echo htmlspecialchars($caso['nombre']); ?></strong>
                  <?php if (!empty($caso['radicado'])): ?>
                    <small>Radicado: <?php echo htmlspecialchars($caso['radicado']); ?></small>
                  <?php else: ?>
                    <small>Sin radicado</small>
                  <?php endif; ?>
                </div>
                <div class="critical-days">
                  <span class="days-badge"><?php echo $caso['dias']; ?> días</span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="no-data"><i class="fa-solid fa-check"></i> No hay casos críticos en este momento</p>
        <?php endif; ?>
      </div>

      <?php if ($tipo_usuario == 2 && count($top_abogados) > 0): ?>
        <div class="info-card lawyers">
          <h3><i class="fa-solid fa-users"></i> Top Abogados</h3>
          <div class="lawyers-list">
            <?php foreach ($top_abogados as $index => $abogado): ?>
              <div class="lawyer-item">
                <div class="lawyer-rank">#<?php echo $index + 1; ?></div>
                <div class="lawyer-info">
                  <strong><?php echo htmlspecialchars($abogado['nombre']); ?></strong>
                  <small><?php echo $abogado['total_casos']; ?> casos totales | <?php echo $abogado['casos_activos']; ?> activos</small>
                </div>
                <div class="lawyer-progress">
                  <div class="progress-circle">
                    <?php 
                      $percentage = $abogado['total_casos'] > 0 ? round((($abogado['total_casos'] - $abogado['casos_activos']) / $abogado['total_casos']) * 100) : 0;
                      echo $percentage . '%';
                    ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <style>
    .dashboard-container {
      margin-left: 260px;
      padding: 30px;
      background: #f5f6fa;
      min-height: calc(100vh - 60px);
      margin-top: 60px;
    }

    .sidebar.close ~ .dashboard-container {
      margin-left: 78px;
    }

    .dashboard-header {
      margin-bottom: 30px;
    }

    .dashboard-header h1 {
      color: #2c3e50;
      font-size: 2rem;
      margin-bottom: 5px;
    }

    .welcome-text {
      color: #7f8c8d;
      font-size: 1.1rem;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      border-radius: 12px;
      padding: 25px;
      display: flex;
      align-items: center;
      gap: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
    }

    .stat-card.total .stat-icon {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .stat-card.active .stat-icon {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      color: white;
    }

    .stat-card.completed .stat-icon {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      color: white;
    }

    .stat-card.progress .stat-icon {
      background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
      color: white;
    }

    .stat-content h3 {
      font-size: 2rem;
      color: #2c3e50;
      margin: 0;
    }

    .stat-content p {
      color: #7f8c8d;
      margin: 5px 0 0 0;
      font-size: 0.95rem;
    }

    .charts-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .chart-card {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .chart-card h3 {
      color: #2c3e50;
      margin-bottom: 20px;
      font-size: 1.1rem;
    }

    .chart-number {
      font-size: 2.5rem;
      font-weight: bold;
      color: #667eea;
      margin-bottom: 20px;
    }

    .chart-bars {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .bar-item {
      display: grid;
      grid-template-columns: 80px 1fr 50px;
      align-items: center;
      gap: 10px;
    }

    .bar-label {
      font-size: 0.9rem;
      color: #7f8c8d;
    }

    .bar-container {
      background: #ecf0f1;
      height: 8px;
      border-radius: 4px;
      overflow: hidden;
    }

    .bar {
      height: 100%;
      border-radius: 4px;
      transition: width 0.5s ease;
    }

    .bar-active {
      background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
    }

    .bar-completed {
      background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
    }

    .bar-value {
      font-weight: bold;
      color: #2c3e50;
      text-align: right;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
      gap: 20px;
    }

    .info-card {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .info-card h3 {
      color: #2c3e50;
      margin-bottom: 20px;
      font-size: 1.1rem;
    }

    .critical-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .critical-item {
      display: grid;
      grid-template-columns: auto 1fr auto;
      gap: 15px;
      padding: 15px;
      background: #fff5f5;
      border-left: 4px solid #e74c3c;
      border-radius: 8px;
      align-items: center;
    }

    .badge {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      white-space: nowrap;
    }

    .badge-demanda {
      background: #3498db;
      color: white;
    }

    .badge-reclamación {
      background: #9b59b6;
      color: white;
    }

    .badge-tutela {
      background: #e67e22;
      color: white;
    }

    .badge-conciliación {
      background: #1abc9c;
      color: white;
    }

    .critical-info strong {
      display: block;
      color: #2c3e50;
      margin-bottom: 3px;
    }

    .critical-info small {
      color: #7f8c8d;
      font-size: 0.85rem;
    }

    .days-badge {
      background: #e74c3c;
      color: white;
      padding: 6px 12px;
      border-radius: 20px;
      font-weight: bold;
      font-size: 0.9rem;
    }

    .no-data {
      text-align: center;
      color: #27ae60;
      padding: 30px;
      font-size: 1.1rem;
    }

    .lawyers-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .lawyer-item {
      display: grid;
      grid-template-columns: 40px 1fr auto;
      gap: 15px;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 8px;
      align-items: center;
    }

    .lawyer-rank {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 1.1rem;
    }

    .lawyer-info strong {
      display: block;
      color: #2c3e50;
      margin-bottom: 3px;
    }

    .lawyer-info small {
      color: #7f8c8d;
      font-size: 0.85rem;
    }

    .progress-circle {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .dashboard-container {
        margin-left: 78px;
        padding: 15px;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }

      .charts-grid {
        grid-template-columns: 1fr;
      }

      .info-grid {
        grid-template-columns: 1fr;
      }

      .bar-item {
        grid-template-columns: 60px 1fr 40px;
      }
    }
  </style>

  <!-- JavaScript -->
  <script src="menu/script.js"></script>
</body>

</html>