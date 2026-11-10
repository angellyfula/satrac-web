# SATRAC - Sistema de Alerta Temprana y Reportes Ambientales Comunitarios

Plataforma web colaborativa desarrollada como opción de grado para el programa de Ingeniería de Sistemas de la Universidad Nacional Abierta y a Distancia (UNAD). Su propósito principal es optimizar la gestión, el reporte ciudadano y la respuesta institucional frente a la acumulación de residuos sólidos en el Distrito de Santa Marta.

## Características del Sistema
* **Módulo Ciudadano:** Permite a los usuarios registrar incidentes de acumulación de residuos adjuntando descripciones y capturando automáticamente coordenadas geográficas (latitud y longitud) mediante geolocalización.
* **Panel Administrativo (Dashboard):** Módulo centralizado para la visualización en tiempo real de los reportes y gestión de estados operativos (*Pendiente*, *En Proceso*, *Resuelto*).
* **Diseño Responsivo (UX/UI):** Adaptado para una óptima visualización y experiencia de usuario tanto en dispositivos móviles como en equipos de escritorio.

## Tecnologías Utilizadas
* **Frontend:** HTML5, CSS3, JavaScript (Diseño responsivo).
* **Backend:** PHP (Modular y estructurado para enrutamiento y lógica de negocio).
* **Base de Datos:** MySQL (Gestión relacional de usuarios, reportes e historial de estados).
* **Entorno de Ejecución Local:** Servidor Apache mediante XAMPP.
* **Herramientas de Diseño:** Figma (Prototipado visual e interfaces UX/UI).

## Instalación y Configuración (Entorno Local)
1. Clonar o descargar este repositorio en la carpeta `htdocs` de tu entorno XAMPP.
2. Configurar la base de datos importando el esquema relacional en MySQL.
3. Verificar los parámetros de conexión en el archivo `conexion.php`.
4. Ejecutar el servidor Apache e ingresar a través del navegador mediante:
   `http://localhost/satrac-web/index.php`
