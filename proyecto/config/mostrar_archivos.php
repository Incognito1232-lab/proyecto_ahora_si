<?php
// Incluir la conexión a la base de datos
include('../config/conexion.php'); // Archivo que contiene la configuración y conexión a la base de datos.

// Verificar que se ha recibido el ID del curso
if (isset($_GET['curso_id'])) {
    $curso_id = $_GET['curso_id'];

    // Consultar los archivos asociados al curso
    $query = "
        SELECT archivo_nom, archivo_ruta
        FROM archivo
        WHERE cur_id = ?
    ";

    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $curso_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Preparar los datos para el HTML
    $archivos = [];
    while ($archivo = $resultado->fetch_assoc()) {
        $archivos[] = $archivo;
    }

    $stmt->close();
    $conexion->close();

    // Incluir la plantilla HTML
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Archivos del Curso</title>
        <link rel='stylesheet' href='../static/mostrararchivos.css'>
    </head>
    <body>
        <div class='container'>
            <h2>Archivos para el curso $curso_id</h2>";
    
    if (!empty($archivos)) {
        echo "<div class='grid'>";
        foreach ($archivos as $archivo) {
            echo "<div class='card'>
                    <h3>" . htmlspecialchars($archivo['archivo_nom']) . "</h3>
                    <a href='" . htmlspecialchars($archivo['archivo_ruta']) . "' target='_blank'>Ver Archivo</a>
                  </div>";
        }
        echo "</div>";
    } else {
        echo "<p>No se encontraron archivos para este curso.</p>";
    }

    echo "    </div>
    </body>
    </html>";
} else {
    // Manejar el error de curso_id no proporcionado
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error</title>
        <link rel='stylesheet' href='../static/mostrararchivos.css'>
    </head>
    <body>
        <div class='error'>
            <h2>Error</h2>
            <p>No se recibió un ID de curso válido.</p>
        </div>
    </body>
    </html>";
}
?>
