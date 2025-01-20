<?php
header('Content-Type: application/json'); // Encabezado JSON

// Mostrar errores PHP (para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = ""; // Cambia por la contraseña de tu base de datos si es necesario
$dbname = "sistema_estadistica";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    echo json_encode([
        "error" => true,
        "message" => "Conexión fallida: " . $conn->connect_error
    ]);
    exit();
}

// Comprobar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores del formulario
    $cur_id = $_POST['cur_id'];
    $cur_nom = $_POST['cur_nom'];
    $cur_cred = $_POST['cur_cred'];
    $carr_id = $_POST['carr_nom']; // Usar carr_id en lugar de carr_nom
    $malla_id = $_POST['malla_anio']; // Usar malla_id en lugar de malla_anio
    $ciclo_num = $_POST['ciclo_num'];

    // Consulta SQL para insertar el nuevo curso
    $sql = "INSERT INTO curso (cur_id, cur_nom, cur_cred, malla_id, ciclo_num, carrera_id)
            VALUES (?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    if ($stmt = $conn->prepare($sql)) {
        // Vincular los parámetros
        $stmt->bind_param("ssiiii", $cur_id, $cur_nom, $cur_cred, $malla_id, $ciclo_num, $carr_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode([
                "error" => false,
                "message" => "Curso agregado correctamente."
            ]);
        } else {
            echo json_encode([
                "error" => true,
                "message" => "Error al agregar curso: " . $stmt->error
            ]);
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo json_encode([
            "error" => true,
            "message" => "Error en la preparación de la consulta: " . $conn->error
        ]);
    }
}

// Cerrar la conexión
$conn->close();
?>
