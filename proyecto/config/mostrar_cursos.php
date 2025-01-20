<?php
// Incluir la conexión a la base de datos
include('../config/conexion.php'); // Archivo que contiene la configuración y conexión a la base de datos.

// Verificar que se han recibido los datos necesarios desde el formulario
if (isset($_POST['ciclo']) && isset($_POST['malla']) && isset($_POST['carrera'])) {
    // Recoger los datos del formulario
    $ciclo = intval($_POST['ciclo']); // Convertir el valor del ciclo a un entero.
    $malla = $_POST['malla']; // Guardar la malla como cadena.
    $carrera = $_POST['carrera']; // Guardar la carrera como cadena.

    // Consultar el ID de la carrera desde la base de datos
    $query_carrera = "SELECT carr_id FROM carrera WHERE carr_nom = ?";
    $stmt_carrera = $conexion->prepare($query_carrera);
    $stmt_carrera->bind_param("s", $carrera); // Vincular el nombre de la carrera
    $stmt_carrera->execute();
    $resultado_carrera = $stmt_carrera->get_result();
    
    if ($resultado_carrera->num_rows > 0) {
        $carrera_id = $resultado_carrera->fetch_assoc()['carr_id']; // Obtener el ID de la carrera
    } else {
        echo "<p>La carrera seleccionada no existe.</p>";
        exit; // Terminar el script si no se encuentra la carrera
    }

    // Consultar el ID de la malla correspondiente a la carrera seleccionada
    $query_malla = "SELECT malla_id FROM mallacurricular WHERE malla_anio = ?";
    $stmt_malla = $conexion->prepare($query_malla);
    $stmt_malla->bind_param("s", $malla); // Vincular el año de la malla
    $stmt_malla->execute();
    $resultado_malla = $stmt_malla->get_result();
    
    if ($resultado_malla->num_rows > 0) {
        $malla_id = $resultado_malla->fetch_assoc()['malla_id']; // Obtener el ID de la malla
    } else {
        echo "<p>No se encontró la malla seleccionada.</p>";
        exit; // Terminar el script si no se encuentra la malla
    }

    // Consultar los cursos según los filtros seleccionados
    $query = "
        SELECT cur_id, cur_nom
        FROM curso
        WHERE curso.ciclo_num = ? 
        AND curso.malla_id = ? 
        AND curso.carrera_id = ?
    ";
    // Crear una declaración preparada para evitar inyección SQL
    $stmt = $conexion->prepare($query);
    // Vincular los parámetros de la consulta con los valores correspondientes
    $stmt->bind_param("iii", $ciclo, $malla_id, $carrera_id);
    // Ejecutar la consulta
    $stmt->execute();
    // Obtener los resultados de la consulta
    $resultado = $stmt->get_result();

    // Incluir los estilos CSS en la página
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Cursos</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f3f4f7;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            .container {
                width: 90%;
                max-width: 800px;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
            .container h2 {
                text-align: center;
                color: #333;
                margin-bottom: 20px;
                font-size: 1.5rem;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th, td {
                text-align: left;
                padding: 10px;
                border: 1px solid #ddd;
            }
            th {
                background-color: #4CAF50;
                color: white;
                text-align: center;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            tr:hover {
                background-color: #f1f1f1;
            }
            td a {
                color: #4CAF50;
                text-decoration: none;
                font-weight: bold;
            }
            td a:hover {
                text-decoration: underline;
            }
            .no-courses {
                text-align: center;
                color: #555;
                font-size: 1.2em;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class='container'>";

    // Mostrar los resultados obtenidos
    echo "<h2>Cursos del Ciclo $ciclo en la Malla $malla para la carrera $carrera</h2>";
    if ($resultado->num_rows > 0) { // Comprobar si se encontraron resultados.
        echo "<table><tr><th>Nombre del Curso</th><th>Acción</th></tr>";
        // Recorrer cada curso y mostrarlo en una tabla HTML
        while ($curso = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td>" . $curso['cur_nom'] . "</td>
                    <td><a href='mostrar_archivos.php?curso_id=" . $curso['cur_id'] . "'>Ver Archivos</a></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        // Mostrar un mensaje si no se encontraron cursos
        echo "<p class='no-courses'>No se encontraron cursos para esta selección.</p>";
    }

    // Liberar recursos
    $stmt->close(); // Cerrar la declaración preparada.
    $stmt_carrera->close(); // Cerrar la declaración de la consulta de carrera.
    $stmt_malla->close(); // Cerrar la declaración de la consulta de malla.
    $conexion->close(); // Cerrar la conexión a la base de datos.

    echo "    </div>
    </body>
    </html>";
} else {
    // Mensaje de error si no se recibieron los datos necesarios
    echo "<p>Error: No se recibieron datos válidos.</p>";
}
?>
