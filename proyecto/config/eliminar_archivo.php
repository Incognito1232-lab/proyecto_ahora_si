<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Ruta de la carpeta uploads
$uploads_dir = '../uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario
    $archivo_id = isset($_POST['archivo_id']) ? $_POST['archivo_id'] : null;
    $archivo_nom = isset($_POST['archivo_nom_buscar']) ? $_POST['archivo_nom_buscar'] : null;

    // Si se proporciona un ID, buscar por ID
    if ($archivo_id) {
        // Preparar la consulta para buscar el archivo por ID
        $sql = "SELECT archivo_nom FROM archivo WHERE archivo_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $archivo_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Obtener el nombre del archivo
            $row = $result->fetch_assoc();
            $file_name = $row['archivo_nom'];

            // Ruta completa del archivo en el servidor
            $file_path = $uploads_dir . $file_name;

            // Proceder con la eliminación del archivo
            if (unlink($file_path)) {
                // El archivo físico se eliminó correctamente, ahora eliminar de la base de datos
                $sql_delete = "DELETE FROM archivo WHERE archivo_id = ?";
                $stmt_delete = $conexion->prepare($sql_delete);
                $stmt_delete->bind_param("i", $archivo_id);
                if ($stmt_delete->execute()) {
                    echo "Archivo eliminado correctamente.";
                } else {
                    echo "Error al eliminar el archivo en la base de datos.";
                }
            } else {
                echo "Error al eliminar el archivo del servidor.";
            }
        } else {
            echo "No se encontró el archivo con el ID proporcionado.";
        }
    }
    // Si se proporciona un nombre, buscar por nombre
    elseif ($archivo_nom) {
        // Preparar la consulta para buscar el archivo por nombre
        $sql = "SELECT archivo_nom FROM archivo WHERE archivo_nom = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $archivo_nom);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Obtener el nombre del archivo
            $row = $result->fetch_assoc();
            $file_name = $row['archivo_nom'];

            // Ruta completa del archivo en el servidor
            $file_path = $uploads_dir . $file_name;

            // Proceder con la eliminación del archivo
            if (unlink($file_path)) {
                // El archivo físico se eliminó correctamente, ahora eliminar de la base de datos
                $sql_delete = "DELETE FROM archivo WHERE archivo_nom = ?";
                $stmt_delete = $conexion->prepare($sql_delete);
                $stmt_delete->bind_param("s", $archivo_nom);
                if ($stmt_delete->execute()) {
                    echo "Archivo eliminado correctamente.";
                } else {
                    echo "Error al eliminar el archivo en la base de datos.";
                }
            } else {
                echo "Error al eliminar el archivo del servidor.";
            }
        } else {
            echo "No se encontró el archivo con el nombre proporcionado.";
        }
    } else {
        echo "Por favor, ingrese un ID o un Nombre para eliminar el archivo.";
    }
}
?>
