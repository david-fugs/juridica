<?php
  
  require "conexion.php";
  
  session_start();
  
  if($_POST){
    
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    
    $sql = "SELECT id, password, nombre, tipo_usuario FROM usuarios WHERE usuario='$usuario'";
    //echo $sql;
    $resultado = $mysqli->query($sql);
    $num = $resultado->num_rows;
    
    if($num>0)
    {
      $row = $resultado->fetch_assoc();
      $password_bd = $row['password'];
      
      $pass_c = sha1($password);
      
      if($password_bd == $pass_c){
        
        $_SESSION['id'] = $row['id'];
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['tipo_usuario'] = $row['tipo_usuario'];
        
        header("Location: access.php");
        
      } else {
      
      echo "La contraseña no coincide";
      
      }
      
      } else {
      echo "NO existe usuario";
    }

  }
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Sistema Jurídico - Iniciar Sesión</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e22ce 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    /* Patrón de fondo decorativo */
    body::before {
      content: '';
      position: absolute;
      width: 200%;
      height: 200%;
      background-image: 
        repeating-linear-gradient(45deg, transparent, transparent 50px, rgba(255,255,255,0.03) 50px, rgba(255,255,255,0.03) 100px);
      animation: drift 60s linear infinite;
      z-index: 0;
    }

    @keyframes drift {
      0% { transform: translate(0, 0) rotate(0deg); }
      100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    .login-container {
      position: relative;
      z-index: 1;
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(20px);
      border-radius: 24px;
      box-shadow: 0 30px 90px rgba(0, 0, 0, 0.3), 0 0 1px rgba(255, 255, 255, 0.5) inset;
      overflow: hidden;
      max-width: 1100px;
      width: 90%;
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 600px;
    }

    /* Panel izquierdo - Branding */
    .branding-panel {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .branding-panel::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: pulse 8s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1) translate(0, 0); opacity: 0.5; }
      50% { transform: scale(1.1) translate(-5%, -5%); opacity: 0.8; }
    }

    .branding-content {
      position: relative;
      z-index: 1;
    }

    .branding-icon {
      width: 120px;
      height: 120px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 30px;
      border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .branding-icon i {
      font-size: 60px;
      color: rgba(255, 255, 255, 0.9);
    }

    .branding-title {
      font-family: 'Playfair Display', serif;
      font-size: 38px;
      font-weight: 700;
      margin-bottom: 20px;
      line-height: 1.2;
      text-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
    }

    .branding-subtitle {
      font-size: 16px;
      font-weight: 300;
      opacity: 0.9;
      line-height: 1.6;
      max-width: 350px;
      margin: 0 auto;
    }

    .branding-features {
      margin-top: 40px;
      text-align: left;
      width: 100%;
      max-width: 320px;
    }

    .feature-item {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      font-size: 14px;
      opacity: 0.85;
    }

    .feature-item i {
      width: 32px;
      height: 32px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      flex-shrink: 0;
    }

    /* Panel derecho - Formulario */
    .form-panel {
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .form-header {
      margin-bottom: 40px;
    }

    .form-title {
      font-size: 32px;
      font-weight: 600;
      color: #1e3c72;
      margin-bottom: 10px;
      font-family: 'Playfair Display', serif;
    }

    .form-subtitle {
      color: #64748b;
      font-size: 15px;
      font-weight: 400;
    }

    .form-group {
      margin-bottom: 28px;
      position: relative;
    }

    .form-label {
      display: block;
      margin-bottom: 10px;
      color: #334155;
      font-size: 14px;
      font-weight: 500;
      letter-spacing: 0.3px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 18px;
      z-index: 1;
    }

    .form-control {
      width: 100%;
      padding: 15px 20px 15px 52px;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 15px;
      transition: all 0.3s ease;
      background: #f8fafc;
      color: #1e293b;
      font-weight: 400;
    }

    .form-control:focus {
      outline: none;
      border-color: #2a5298;
      background: white;
      box-shadow: 0 0 0 4px rgba(42, 82, 152, 0.1);
    }

    .form-control::placeholder {
      color: #cbd5e1;
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      font-size: 14px;
    }

    .remember-me {
      display: flex;
      align-items: center;
      color: #64748b;
      cursor: pointer;
    }

    .remember-me input[type="checkbox"] {
      margin-right: 8px;
      cursor: pointer;
      width: 18px;
      height: 18px;
    }

    .forgot-password {
      color: #2a5298;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .forgot-password:hover {
      color: #1e3c72;
      text-decoration: underline;
    }

    .btn-login {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 16px rgba(42, 82, 152, 0.3);
      position: relative;
      overflow: hidden;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s ease;
    }

    .btn-login:hover::before {
      left: 100%;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 24px rgba(42, 82, 152, 0.4);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .btn-login i {
      margin-left: 8px;
    }

    .register-link {
      text-align: center;
      margin-top: 30px;
      color: #64748b;
      font-size: 14px;
    }

    .register-link a {
      color: #2a5298;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .register-link a:hover {
      color: #1e3c72;
      text-decoration: underline;
    }

    .alert-message {
      padding: 14px 18px;
      border-radius: 10px;
      margin-bottom: 24px;
      font-size: 14px;
      display: flex;
      align-items: center;
      animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .alert-error {
      background: #fef2f2;
      color: #991b1b;
      border: 1px solid #fecaca;
    }

    .alert-error i {
      margin-right: 10px;
      font-size: 16px;
    }

    /* Responsive */
    @media (max-width: 968px) {
      .login-container {
        grid-template-columns: 1fr;
        max-width: 500px;
      }

      .branding-panel {
        display: none;
      }

      .form-panel {
        padding: 40px 30px;
      }
    }

    @media (max-width: 480px) {
      .form-panel {
        padding: 30px 20px;
      }

      .form-title {
        font-size: 26px;
      }

      .branding-title {
        font-size: 30px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <!-- Panel Izquierdo - Branding -->
    <div class="branding-panel">
      <div class="branding-content">
        <div class="branding-icon">
          <i class="fa-solid fa-scale-balanced"></i>
        </div>
        <h1 class="branding-title">Sistema Jurídico</h1>
        <p class="branding-subtitle">
          Plataforma integral para la gestión profesional de procesos legales y administrativos
        </p>
        
        <div class="branding-features">
          <div class="feature-item">
            <i class="fa-solid fa-check"></i>
            <span>Gestión de demandas y procesos</span>
          </div>
          <div class="feature-item">
            <i class="fa-solid fa-check"></i>
            <span>Control de tutelas y conciliaciones</span>
          </div>
          <div class="feature-item">
            <i class="fa-solid fa-check"></i>
            <span>Seguimiento en tiempo real</span>
          </div>
          <div class="feature-item">
            <i class="fa-solid fa-check"></i>
            <span>Acceso seguro y confidencial</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Panel Derecho - Formulario -->
    <div class="form-panel">
      <div class="form-header">
        <h2 class="form-title">Iniciar Sesión</h2>
        <p class="form-subtitle">Ingrese sus credenciales para acceder al sistema</p>
      </div>

      <?php if($_POST && $num == 0): ?>
        <div class="alert-message alert-error">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>El usuario ingresado no existe en el sistema</span>
        </div>
      <?php endif; ?>

      <?php if($_POST && $num > 0 && isset($pass_c) && $password_bd != $pass_c): ?>
        <div class="alert-message alert-error">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>La contraseña ingresada es incorrecta</span>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
          <label class="form-label">Usuario</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-user input-icon"></i>
            <input 
              type="text" 
              name="usuario" 
              class="form-control" 
              placeholder="Ingrese su usuario"
              required
              autocomplete="username"
            />
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Contraseña</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-lock input-icon"></i>
            <input 
              type="password" 
              name="password" 
              class="form-control" 
              placeholder="Ingrese su contraseña"
              required
              autocomplete="current-password"
            />
          </div>
        </div>

        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" />
            <span>Recordarme</span>
          </label>
          <a href="reset-password.php" class="forgot-password">¿Olvidó su contraseña?</a>
        </div>

        <button type="submit" class="btn-login">
          <span>Acceder al Sistema</span>
          <i class="fa-solid fa-arrow-right"></i>
        </button>

        <div class="register-link">
          ¿No tiene una cuenta? <a href="register.php">Crear cuenta nueva</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
