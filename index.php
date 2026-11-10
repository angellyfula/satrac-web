<?php
include("conexion.php");

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $lat = $conexion->real_escape_string($_POST['latitud']);
    $lng = $conexion->real_escape_string($_POST['longitud']);
    
    // Simulación temporal de la ruta de la foto para el prototipo TRL 5
    $foto_evidencia = "";
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $nombre_archivo = time() . "_" . basename($_FILES['foto']['name']);
    $carpeta_destino = "uploads/";
    
    // Crear la carpeta uploads si no existe
    if (!file_exists($carpeta_destino)) {
        mkdir($carpeta_destino, 0777, true);
    }
    
    $ruta_final = $carpeta_destino . $nombre_archivo;
    
    // Mover el archivo subido a la carpeta local
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_final)) {
        $foto_evidencia = $ruta_final;
    }
}

// Si no subió ninguna foto, dejamos una por defecto
if(empty($foto_evidencia)) {
    $foto_evidencia = "uploads/default.jpg";
}

    $sql = "INSERT INTO reportes (descripcion, latitud, longitud, foto_evidencia, estado) VALUES ('$descripcion', '$lat', '$lng', '$foto_evidencia', 'Pendiente')";
    
    if ($conexion->query($sql) === TRUE) {
        $mensaje = "¡Reporte ciudadano enviado con éxito al servidor!";
    } else {
        $mensaje = "Error al registrar el reporte: " . $conexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SATRAC - Módulo Ciudadano (TRL 5)</title>
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
        .container { max-width: 900px; margin: 0 auto; background: var(--card-bg); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 40px; }
        header { border-bottom: 2px solid var(--border-color); padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 22px; color: var(--primary); display: flex; align-items: center; gap: 10px; }
        .grid-form { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; color: #334155; }
        textarea, input[type="file"] { width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 15px; }
        .gps-box { background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px; border-radius: 8px; color: #166534; font-size: 14px; margin-top: 8px; display: flex; align-items: center; gap: 8px; }
        .btn-gps { background: #e2e8f0; border: none; padding: 12px; border-radius: 8px; font-weight: 600; color: #475569; cursor: pointer; width: 100%; text-align: center; }
        .btn-gps:hover { background: #cbd5e1; }
        .btn-submit { background: var(--primary); color: white; border: none; padding: 14px 24px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; width: 100%; transition: background 0.2s; }
        .btn-submit:hover { background: var(--primary-dark); }
        .alert { background: #dcfce7; color: #16a34a; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; text-align: center; }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1><i class="fa-solid fa-leaf"></i> SATRAC - Módulo Ciudadano</h1>
        <span style="font-size: 14px; color: #64748b;">Santa Marta, Colombia</span>
    </header>

    <?php if(!empty($mensaje)): ?>
        <div class="alert"><i class="fa-solid fa-circle-check"></i> <?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form action="index.php" method="POST" enctype="multipart/form-data">
        <div class="grid-form">
            <div>
                <div class="form-group">
                    <label>Descripción del Foco de Residuos:</label>
                    <textarea name="descripcion" rows="5" placeholder="Ej. Acumulación de residuos sólidos en vía principal..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Evidencia Fotográfica:</label>
                    <input type="file" name="foto" accept="image/*" required>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label>Georreferenciación Automática (GPS):</label>
                    <button type="button" class="btn-gps" onclick="obtenerGPS()"><i class="fa-solid fa-location-crosshairs"></i> Capturar Coordenadas Actuales</button>
                    <div id="gpsStatus" class="gps-box">
                        <i class="fa-solid fa-circle-exclamation"></i> Coordenadas pendientes de captura...
                    </div>
                    <input type="hidden" id="lat" name="latitud" required>
                    <input type="hidden" id="lng" name="longitud" required>
                </div>
                <div style="margin-top: 45px;">
                    <button type="submit" class="btn-submit"><i class="fa-solid fa-paper-plane"></i> Enviar Reporte al Servidor</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function obtenerGPS() {
        const box = document.getElementById('gpsStatus');
        if (navigator.geolocation) {
            box.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Obteniendo ubicación GPS en Santa Marta...`;
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
                box.innerHTML = `<i class="fa-solid fa-circle-check" style="color:#16a34a"></i> Ubicación capturada: Lat ${lat.toFixed(4)}, Lng ${lng.toFixed(4)}`;
            }, () => {
                // Coordenadas simuladas por defecto si el navegador bloquea
                const lat = 11.2404; const lng = -74.1990;
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
                box.innerHTML = `<i class="fa-solid fa-circle-check" style="color:#16a34a"></i> Ubicación predeterminada (Santa Marta): Lat ${lat}, Lng ${lng}`;
            });
        } else {
            alert("La geolocalización no es soportada por este navegador.");
        }
    }
</script>

</body>
</html>