<?php
session_start();

if (!isset($_SESSION['id'])) {
  header("Location: index.php");
}

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
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




  <!-- JavaScript -->
  <script src="menu/script.js"></script>
</body>

</html>