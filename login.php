<?php
session_start();
include("conexion.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $conexion->real_escape_string($_POST['correo']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows == 1) {
        $row = $resultado->fetch_assoc();
        
        // Verificación de contraseña (para este prototipo TRL 5 validamos texto plano o hash básico)
        if ($password === $row['password']) {
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['rol'] = $row['rol'];

            if ($row['rol'] == 'administrador') {
                header("Location: admin.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            $error = "Contraseña incorrecta. Intente nuevamente.";
        }
    } else {
        $error = "El correo electrónico no está registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SATRAC - Iniciar Sesión (TRL 5)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #0f766e;
            --primary-dark: #115e59;
            --bg-color: #f8fafc;
            --text-main: #1e293b;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-main); display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { background: var(--card-bg); width: 100%; max-width: 400px; padding: 40px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color); }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h1 { font-size: 22px; color: var(--primary); display: flex; align-items: center; justify-content: center; gap: 10px; }
        .login-header p { font-size: 13px; color: #64748b; margin-top: 5px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px; color: #334155; }
        input[type="email"], input[type="password"] { width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 15px; }
        .btn-login { background: var(--primary); color: white; border: none; padding: 12px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; width: 100%; transition: background 0.2s; }
        .btn-login:hover { background: var(--primary-dark); }
        .error-msg { background: #ffeeec; color: #dc2626; padding: 10px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; text-align: center; font-weight: 600; }
        .footer-link { text-align: center; margin-top: 20px; font-size: 13px; }
        .footer-link a { color: var(--primary); text-decoration: none; font-weight: 600; }
        .footer-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <h1><i class="fa-solid fa-leaf"></i> SATRAC</h1>
        <p>Sistema de Alerta Temprana - Santa Marta</p>
    </div>

    <?php if(!empty($error)): ?>
        <div class="error-msg"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label>Correo Electrónico:</label>
            <input type="email" name="correo" placeholder="ejemplo@correo.com" required>
        </div>
        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-login"><i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión</button>
    </form>

    <div class="footer-link">
        <p>¿Eres ciudadano? <a href="index.php">Entrar al Módulo Ciudadano</a></p>
    </div>
</div>

</body>
</html>