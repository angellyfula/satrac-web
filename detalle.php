<?php
include("conexion.php");

// Obtener el ID del reporte desde la URL
$id_reporte = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar los datos específicos del reporte
$sql = "SELECT * FROM reportes WHERE id = $id_reporte";
$resultado = $conexion->query($sql);

if ($resultado->num_rows == 0) {
    die("Reporte no encontrado.");
}

$reporte = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SATRAC - Detalle del Reporte #SR-<?php echo $reporte['id']; ?></title>
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
        .container { max-width: 1000px; margin: 0 auto; background: var(--card-bg); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 30px; border: 1px solid var(--border-color); }
        header { border-bottom: 2px solid var(--border-color); padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 20px; color: var(--primary); display: flex; align-items: center; gap: 10px; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-pendiente { background: #fef3c7; color: #d97706; }
        .badge-proceso { background: #e0f2fe; color: #0284c7; }
        .badge-resuelto { background: #dcfce7; color: #16a34a; }
        
        .grid-detalle { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .seccion-foto { background: #f8fafc; border: 1px solid var(--border-color); border-radius: 12px; padding: 15px; }
        .seccion-foto img { width: 100%; height: 320px; object-fit: cover; border-radius: 8px; background: #e2e8f0; }
        .info-box { background: #f8fafc; border: 1px solid var(--border-color); padding: 15px; border-radius: 8px; margin-bottom: 15px; }
        .info-box h4 { font-size: 13px; color: #64748b; margin-bottom: 5px; text-transform: uppercase; }
        .info-box p { font-size: 15px; font-weight: 600; color: #334155; }
        
        .btn-volver { display: inline-flex; align-items: center; gap: 8px; text-decoration: none; background: #e2e8f0; color: #334155; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; }
        .btn-volver:hover { background: #cbd5e1; }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>
            <i class="fa-solid fa-file-lines"></i> Detalle del reporte 
            <span class="badge <?php 
                if($reporte['estado'] == 'Pendiente') echo 'badge-pendiente';
                elseif($reporte['estado'] == 'En Proceso') echo 'badge-proceso';
                else echo 'badge-resuelto';
            ?>"><?php echo $reporte['estado']; ?></span>
        </h1>
        <a href="admin.php" class="btn-volver"><i class="fa-solid fa-arrow-left"></i> Volver al Panel</a>
    </header>

    <div class="grid-detalle">
        <!-- Columna Izquierda: Evidencia Fotográfica y Ubicación -->
        <div>
            <div class="seccion-foto">
                <h4 style="font-size: 13px; color: #64748b; margin-bottom: 10px; text-transform: uppercase;"><i class="fa-solid fa-camera"></i> Evidencia Fotográfica</h4>
                
                <!-- Mostramos la imagen real guardada en la base de datos -->
                <?php if(!empty($reporte['foto_evidencia']) && file_exists($reporte['foto_evidencia'])): ?>
                    <img src="<?php echo $reporte['foto_evidencia']; ?>" alt="Evidencia de Residuos" style="width: 100%; height: 320px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
                <?php else: ?>
                    <div style="width: 100%; height: 300px; background: #e2e8f0; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #475569;">
                        <i class="fa-solid fa-image" style="font-size: 40px; color: #0f766e; margin-bottom: 8px;"></i>
                        <span>Sin evidencia fotográfica disponible</span>
                    </div>
                <?php endif; ?>

                <p style="font-size: 12px; color: #64748b; margin-top: 8px;"><i class="fa-solid fa-circle-info"></i> Evidencia enviada por el ciudadano desde terreno.</p>
            </div>

            <div class="info-box" style="margin-top: 20px;">
                <h4><i class="fa-solid fa-location-dot" style="color: var(--primary);"></i> Ubicación Reportada (GPS)</h4>
                <p>Lat: <?php echo $reporte['latitud']; ?>, Lng: <?php echo $reporte['longitud']; ?></p>
            </div>
        </div>

        <!-- Columna Derecha: Descripción y Trazabilidad -->
        <div>
            <div class="info-box">
                <h4>Descripción del Incidente</h4>
                <p style="font-weight: normal; font-size: 15px;"><?php echo htmlspecialchars($reporte['descripcion']); ?></p>
            </div>

            <div class="info-box">
                <h4>Fecha y Hora del Registro</h4>
                <p><i class="fa-regular fa-calendar"></i> <?php echo $reporte['fecha_reporte']; ?></p>
            </div>

            <div class="info-box">
                <h4>Historial de Trazabilidad Institucional</h4>
                <ul style="list-style: none; margin-top: 10px; font-size: 14px;">
                    <li style="margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-circle-check" style="color: var(--primary);"></i> 
                        <span>Reporte recibido y registrado en sistema (<?php echo $reporte['fecha_reporte']; ?>)</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 8px; color: #64748b;">
                        <i class="fa-solid fa-clock"></i> 
                        <span>Estado actual: <strong><?php echo $reporte['estado']; ?></strong></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>