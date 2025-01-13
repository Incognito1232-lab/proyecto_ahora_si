<?php
session_start(); // Iniciar sesión para almacenar información del usuario

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = ""; // Cambia si tu base de datos tiene contraseña
$dbname = "sistema_estadistica";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores del formulario
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta SQL para verificar el usuario y la contraseña
    $sql = "SELECT * FROM administradores WHERE admin_usuario = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario encontrado
        $row = $result->fetch_assoc();
        if ($contrasena === $row['admin_contrasena']) { // Comparación directa
            // Contraseña correcta, iniciar sesión
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['admin_usuario'] = $row['admin_usuario'];
            // Redirigir a la página admin.html en la carpeta home
            header("Location: ../home/admin.html");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
}

$conn->close();
?>
