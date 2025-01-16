<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores enviados por el formulario
    $archivo_id = isset($_POST['archivo_id']) ? $_POST['archivo_id'] : null;

    if ($archivo_id) {
        // Preparar la consulta para obtener la ruta del archivo
        $sql = "SELECT archivo_ruta FROM archivo WHERE archivo_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $archivo_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Obtener la ruta del archivo
            $row = $result->fetch_assoc();
            $file_path = realpath($row['archivo_ruta']); // Obtener la ruta absoluta del archivo

            // Verificar si el archivo existe
            if ($file_path && file_exists($file_path)) {
                // Eliminar el archivo del servidor
                if (unlink($file_path)) {
                    // Archivo físico eliminado, ahora eliminar el registro de la base de datos
                    $sql_delete = "DELETE FROM archivo WHERE archivo_id = ?";
                    $stmt_delete = $conexion->prepare($sql_delete);
                    $stmt_delete->bind_param("i", $archivo_id);

                    if ($stmt_delete->execute()) {
                        echo "Archivo eliminado correctamente de la carpeta y la base de datos.";
                    } else {
                        echo "Error al eliminar el archivo de la base de datos.";
                    }
                } else {
                    echo "Error al eliminar el archivo del servidor.";
                }
            } else {
                echo "El archivo no existe en la ruta especificada.";
            }
        } else {
            echo "No se encontró el archivo con el ID proporcionado.";
        }
    } else {
        echo "Por favor, proporciona un ID válido.";
    }
}
?>
