<?php
header('Content-Type: application/json'); // Encabezado JSON

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = ""; // Cambia si tu base de datos requiere contraseña
$dbname = "sistema_estadistica";

// Establecer conexión con la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    echo json_encode([
        "error" => true,
        "message" => "Conexión fallida: " . $conn->connect_error
    ]);
    exit();
}

// Consultas SQL para obtener opciones
$sqlMallas = "SELECT malla_id, malla_anio FROM mallacurricular";
$sqlCarreras = "SELECT carr_id, carr_nom FROM carrera";

$mallas = $conn->query($sqlMallas);
$carreras = $conn->query($sqlCarreras);

$data = [
    "mallas" => [],
    "carreras" => []
];

if ($mallas->num_rows > 0) {
    while ($row = $mallas->fetch_assoc()) {
        $data["mallas"][] = $row;
    }
}

if ($carreras->num_rows > 0) {
    while ($row = $carreras->fetch_assoc()) {
        $data["carreras"][] = $row;
    }
}

echo json_encode([
    "error" => false,
    "data" => $data
]);

$conn->close();
?>
