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

    // Mostrar los resultados
    echo "<h2>Archivos para el curso $curso_id</h2>";
    if ($resultado->num_rows > 0) {
        echo "<ul>";
        while ($archivo = $resultado->fetch_assoc()) {
            echo "<li><a href='" . $archivo['archivo_ruta'] . "'>" . $archivo['archivo_nom'] . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron archivos para este curso.</p>";
    }

    // Liberar recursos
    $stmt->close();
    $conexion->close();
} else {
    echo "<p>Error: No se recibió un ID de curso válido.</p>";
}
?>
