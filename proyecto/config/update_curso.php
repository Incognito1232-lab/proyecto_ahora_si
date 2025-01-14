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

// Obtener los datos del formulario
$cur_id = $_POST['cur_id'];
$cur_nom = $_POST['cur_nom'];
$cur_cred = $_POST['cur_cred'];
$malla_id = $_POST['malla_id'];
$ciclo_num = $_POST['ciclo_num'];
$carrera_id = $_POST['carrera_id']; // Asegúrate de que el nombre del campo sea correcto

// Log para depuración
error_log("Datos recibidos: cur_id=$cur_id, cur_nom=$cur_nom, cur_cred=$cur_cred, malla_id=$malla_id, ciclo_num=$ciclo_num, carrera_id=$carrera_id");

// Consulta SQL para actualizar el curso
$sql = "
    UPDATE curso
    SET
        cur_nom = ?,
        cur_cred = ?,
        malla_id = ?,
        ciclo_num = ?,
        carrer_id = ?
    WHERE cur_id = ?
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

$stmt->bind_param('ssssss', $cur_nom, $cur_cred, $malla_id, $ciclo_num, $carrera_id, $cur_id);
$stmt->execute();

// Verificar si la consulta fue exitosa
if ($stmt->affected_rows > 0) {
    echo json_encode([
        "error" => false,
        "message" => "Curso actualizado correctamente."
    ]);
} else {
    echo json_encode([
        "error" => true,
        "message" => "No se pudo actualizar el curso. Verifica los datos proporcionados."
    ]);
}

$conn->close();
?>
