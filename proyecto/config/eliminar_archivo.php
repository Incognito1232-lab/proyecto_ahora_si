<?php
// Configurar la respuesta en formato JSON
header('Content-Type: application/json');

// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Inicializar mensajes
$response = array("error" => false, "message" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores enviados por el formulario
    $archivo_nom = isset($_POST['archivo_nom_buscar']) ? $_POST['archivo_nom_buscar'] : null;

    if ($archivo_nom) {
        // Preparar la consulta para obtener el ID y la ruta del archivo
        $sql = "SELECT archivo_id, archivo_ruta FROM archivo WHERE archivo_nom = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $archivo_nom);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Obtener el ID y la ruta del archivo
            $row = $result->fetch_assoc();
            $archivo_id = $row['archivo_id'];
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
                        $response["message"] = "Archivo eliminado correctamente de la carpeta y la base de datos.";
                    } else {
                        $response["error"] = true;
                        $response["message"] = "Error al eliminar el archivo de la base de datos.";
                    }
                } else {
                    $response["error"] = true;
                    $response["message"] = "Error al eliminar el archivo del servidor.";
                }
            } else {
                $response["error"] = true;
                $response["message"] = "El archivo no existe en la ruta especificada.";
            }
        } else {
            $response["error"] = true;
            $response["message"] = "No se encontró el archivo con el nombre proporcionado.";
        }
    } else {
        $response["error"] = true;
        $response["message"] = "Por favor, proporciona un nombre de archivo válido.";
    }
}

echo json_encode($response);
?>
