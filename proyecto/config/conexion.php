<?php
// Datos de conexión
$host = "localhost";   // Dirección del servidor MySQL
$user = "root";        // Usuario de la base de datos
$password = "";        // Contraseña de la base de datos
$database = "sistema_estadistica"; // Nombre de la base de datos

// Crear la conexión
$conexion = new mysqli($host, $user, $password, $database);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
