<?php
// Incluir la conexión a la base de datos
include('../config/conexion.php');

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

    // Comienza la estructura HTML
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Archivos del Curso</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 20px;
            }
            .container {
                background-color: #ffffff;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 500px;
                padding: 20px;
                text-align: center;
            }
            h2 {
                font-size: 1.5em;
                margin-bottom: 20px;
                color: #333;
            }
            ul {
                list-style: none;
                padding: 0;
            }
            li {
                margin: 10px 0;
            }
            a {
                text-decoration: none;
                color: #007BFF;
                font-weight: bold;
            }
            a:hover {
                text-decoration: underline;
                color: #0056b3;
            }
            p {
                color: #666;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
    ";

    // Mostrar los resultados
    echo "<h2>Archivos para el curso $curso_id</h2>";
    if ($resultado->num_rows > 0) {
        echo "<ul>";
        while ($archivo = $resultado->fetch_assoc()) {
            echo "<li><a href='" . $archivo['archivo_ruta'] . "' target='_blank'>" . $archivo['archivo_nom'] . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron archivos para este curso.</p>";
    }

    // Termina la estructura HTML
    echo "
        </div>
    </body>
    </html>
    ";

    // Liberar recursos
    $stmt->close();
    $conexion->close();
} else {
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background-color: #f4f4f4;
            }
            .error {
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                text-align: center;
            }
            h2 {
                color: #ff4d4d;
                margin-bottom: 10px;
            }
            p {
                color: #666;
            }
        </style>
    </head>
    <body>
        <div class='error'>
            <h2>Error</h2>
            <p>No se recibió un ID de curso válido.</p>
        </div>
    </body>
    </html>
    ";
}
?>
