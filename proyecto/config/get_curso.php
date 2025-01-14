<?php
header('Content-Type: application/json'); // Encabezado JSON

// Mostrar errores PHP (para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Obtener el ID del curso desde la solicitud
if (!isset($_GET['cur_id'])) {
    echo json_encode([
        "error" => true,
        "message" => "ID del curso no proporcionado."
    ]);
    exit();
}

$cur_id = $_GET['cur_id'];

// Consulta SQL para obtener la información del curso, incluyendo malla_anio y carr_nom
$sql = "
    SELECT 
        c.cur_id, 
        c.cur_nom, 
        c.cur_cred,
        c.ciclo_num,
        m.malla_anio,
        cr.carr_nom,
        c.malla_id,
        c.carrera_id
    FROM 
        curso c
    INNER JOIN 
        mallacurricular m ON c.malla_id = m.malla_id
    INNER JOIN 
        carrera cr ON c.carrera_id = cr.carr_id
    WHERE 
        c.cur_id = ?
";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode([
        "error" => true,
        "message" => "Error en la preparación de la consulta: " . $conn->error
    ]);
    exit();
}

$stmt->bind_param('s', $cur_id);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el curso
if ($result->num_rows > 0) {
    $curso = $result->fetch_assoc();
    echo json_encode([
        "error" => false,
        "data" => $curso
    ]);
} else {
    echo json_encode([
        "error" => true,
        "message" => "No se encontró el curso."
    ]);
}

$conn->close();
?>
