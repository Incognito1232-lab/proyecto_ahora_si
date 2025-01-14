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

// Consulta SQL para obtener los cursos con el año de la malla curricular
$sql = "
    SELECT 
        c.cur_id, 
        c.cur_nom, 
        c.ciclo_num, 
        m.malla_anio 
    FROM 
        curso c
    INNER JOIN 
        mallacurricular m
    ON 
        c.malla_id = m.malla_id
";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    $cursos = [];
    while ($row = $result->fetch_assoc()) {
        $cursos[] = [
            "cur_id" => $row["cur_id"],
            "cur_nom" => $row["cur_nom"],
            "ciclo_num" => $row["ciclo_num"],
            "malla_anio" => $row["malla_anio"]
        ];
    }
    echo json_encode([
        "error" => false,
        "data" => $cursos
    ]);
} else {
    echo json_encode([
        "error" => false,
        "data" => [],
        "message" => "No se encontraron cursos."
    ]);
}

$conn->close();
?>
