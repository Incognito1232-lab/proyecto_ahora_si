<?php
// Configurar la respuesta en formato JSON
header('Content-Type: application/json');

// Inicializar mensajes
$response = array("error" => false, "message" => "");

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "sistema_estadistica";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    $response["error"] = true;
    $response["message"] = "Error de conexión: " . $conn->connect_error;
    echo json_encode($response);
    exit;
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $archivo_nom = $_POST["archivo_nom"];
    $ciclo_num = $_POST["ciclo_num"];
    $malla_id = $_POST["malla_id"];
    $cur_id = $_POST["cur_id"];

    // Manejo del archivo subido
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["archivo_ruta"]["name"]);

    if (move_uploaded_file($_FILES["archivo_ruta"]["tmp_name"], $target_file)) {
        // Insertar la información en la base de datos
        $archivo_ruta = $target_file;
        $sql = "INSERT INTO archivo (archivo_nom, archivo_ruta, ciclo_num, malla_id, cur_id) 
                VALUES ('$archivo_nom', '$archivo_ruta', $ciclo_num, $malla_id, '$cur_id')";

        if ($conn->query($sql) !== TRUE) {
            $response["error"] = true;
            $response["message"] = "Error al registrar en la base de datos: " . $conn->error;
        }
    } else {
        $response["error"] = true;
        $response["message"] = "Error al subir el archivo.";
    }
}

$conn->close();
echo json_encode($response);
?>
