<?php

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

// Procesar el cambio de estado si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reporte_id']) && isset($_POST['nuevo_estado'])) {
    $id_reporte = intval($_POST['reporte_id']);
    $nuevo_estado = $conexion->real_escape_string($_POST['nuevo_estado']);

    // Actualizar el estado en la base de datos
    $sql_update = "UPDATE reportes SET estado = '$nuevo_estado' WHERE id = $id_reporte";
    $conexion->query($sql_update);
}

// Consultar todos los reportes registrados
$resultado = $conexion->query("SELECT * FROM reportes ORDER BY fecha_reporte DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SATRAC - Panel Administrativo (TRL 5)</title>
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
        body { background-color: var(--bg-color); color: var(--text-main); line-height: 1.5; padding: 20px; }
        .container { max-width: 1100px; margin: 0 auto; background: var(--card-bg); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 30px; }
        header { border-bottom: 2px solid var(--border-color); padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 22px; color: var(--primary); display: flex; align-items: center; gap: 10px; }
        .nav-links a { text-decoration: none; background: #e2e8f0; color: #334155; padding: 8px 14px; border-radius: 6px; font-size: 14px; font-weight: 600; }
        .nav-links a:hover { background: #cbd5e1; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid var(--border-color); font-size: 14px; }
        th { background: #f1f5f9; color: #475569; font-weight: 600; }
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; }
        .badge-pendiente { background: #fef3c7; color: #d97706; }
        .badge-proceso { background: #e0f2fe; color: #0284c7; }
        .badge-resuelto { background: #dcfce7; color: #16a34a; }
        select { padding: 6px 10px; border-radius: 6px; border: 1px solid var(--border-color); font-size: 13px; }
        .btn-update { background: var(--primary); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; }
        .btn-update:hover { background: var(--primary-dark); }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1><i class="fa-solid fa-chart-line"></i> SATRAC - Panel de Control Administrativo</h1>
        <div class="nav-links" style="display: flex; gap: 10px;">
            <a href="index.php"><i class="fa-solid fa-house"></i> Módulo Ciudadano</a>
            <a href="logout.php" style="background: #fee2e2; color: #dc2626;"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
        </div>
    </header>

    <h2>Gestión de Reportes Ciudadanos</h2>
    <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">Listado de incidentes georreferenciados en Santa Marta para seguimiento y trazabilidad institucional.</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Coordenadas GPS</th>
                <th>Fecha</th>
                <th>Estado Actual</th>
                <th>Acción / Cambiar Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while($row = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><a href="detalle.php?id=<?php echo $row['id']; ?>" style="color: #0f766e; text-decoration: none; font-weight: bold;">#SR-<?php echo $row['id']; ?></a></td>
                        <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                        <td><small><i class="fa-solid fa-location-dot" style="color: #0f766e;"></i> <?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?></small></td>
                        <td><?php echo $row['fecha_reporte']; ?></td>
                        <td>
                            <?php 
                                $clase_badge = 'badge-pendiente';
                                if($row['estado'] == 'En Proceso') $clase_badge = 'badge-proceso';
                                if($row['estado'] == 'Resuelto') $clase_badge = 'badge-resuelto';
                            ?>
                            <span class="badge <?php echo $clase_badge; ?>"><?php echo $row['estado']; ?></span>
                        </td>
                        <td>
                            <form action="admin.php" method="POST" style="display: flex; gap: 8px; align-items: center;">
                                <input type="hidden" name="reporte_id" value="<?php echo $row['id']; ?>">
                                <select name="nuevo_estado">
                                    <option value="Pendiente" <?php if($row['estado']=='Pendiente') echo 'selected'; ?>>Pendiente</option>
                                    <option value="En Proceso" <?php if($row['estado']=='En Proceso') echo 'selected'; ?>>En Proceso</option>
                                    <option value="Resuelto" <?php if($row['estado']=='Resuelto') echo 'selected'; ?>>Resuelto</option>
                                </select>
                                <button type="submit" class="btn-update"><i class="fa-solid fa-floppy-disk"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 30px;">No hay reportes registrados en el sistema todavía.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>