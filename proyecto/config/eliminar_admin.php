<?php
// Inicializar mensajes
session_start(); // Iniciar la sesión para manejar mensajes
$mensaje = "";
$error = "";

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "sistema_estadistica";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST["admin_id"];

    // Consulta SQL para eliminar el administrador
    $sql = "DELETE FROM administradores WHERE admin_id = ?";

    // Preparar la consulta
    if ($stmt = $conn->prepare($sql)) {
        // Vincular el parámetro
        $stmt->bind_param("i", $admin_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->affected_rows > 0) {
                $_SESSION['mensaje'] = "Administrador eliminado correctamente.";
            } else {
                $_SESSION['error'] = "No se encontró un administrador con ese ID.";
            }
        } else {
            $_SESSION['error'] = "Error al eliminar el administrador: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error en la preparación de la consulta: " . $conn->error;
    }

    // Redirigir a la página de administración
    header("Location: ../home/admin.html");
    exit(); // Asegúrate de salir después de redirigir
}

// Cerrar la conexión
$conn->close();
?>