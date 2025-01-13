<?php
// Iniciar sesión
session_start();

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = ""; // Cambia por la contraseña de tu base de datos si es necesario
$dbname = "sistema_estadistica";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Comprobar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores del formulario
    $admin_usuario = $_POST['admin_usuario'];
    $admin_contrasena = $_POST['admin_contrasena'];
    $admin_nombre = $_POST['admin_nombre'];
    $admin_correo = $_POST['admin_correo'];

    // Consulta SQL para insertar el nuevo administrador
    $sql = "INSERT INTO administradores (admin_usuario, admin_contrasena, admin_nombre, admin_correo)
            VALUES (?, ?, ?, ?)";

    // Preparar la consulta
    if ($stmt = $conn->prepare($sql)) {
        // Vincular los parámetros
        $stmt->bind_param("ssss", $admin_usuario, $admin_contrasena, $admin_nombre, $admin_correo);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a la página de administración después de agregar al administrador
            header("Location: ../home/admin.html");
            exit();
        } else {
            echo "Error al agregar administrador: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();
?>
