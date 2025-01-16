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
    $cur_id = $_POST['cur_id'];
    $cur_nom = $_POST['cur_nom'];
    $cur_cred = $_POST['cur_cred'];
    $carr_nom = $_POST['carr_nom'];
    $malla_anio = $_POST['malla_anio'];
    $ciclo_num = $_POST['ciclo_num'];

    // Obtener malla_id basado en malla_anio
    $sql_malla = "SELECT malla_id FROM mallacurricular WHERE malla_anio = ?";
    if ($stmt_malla = $conn->prepare($sql_malla)) {
        $stmt_malla->bind_param("i", $malla_anio);
        $stmt_malla->execute();
        $stmt_malla->bind_result($malla_id);
        $stmt_malla->fetch();
        $stmt_malla->close();
    } else {
        echo "Error en la preparación de la consulta de malla: " . $conn->error;
        exit();
    }

    // Obtener carrera_id basado en carr_nom
    $sql_carrera = "SELECT carr_id FROM carrera WHERE carr_nom = ?";
    if ($stmt_carrera = $conn->prepare($sql_carrera)) {
        $stmt_carrera->bind_param("s", $carr_nom);
        $stmt_carrera->execute();
        $stmt_carrera->bind_result($carrera_id);
        $stmt_carrera->fetch();
        $stmt_carrera->close();
    } else {
        echo "Error en la preparación de la consulta de carrera: " . $conn->error;
        exit();
    }

    // Consulta SQL para insertar el nuevo curso
    $sql = "INSERT INTO curso (cur_id, cur_nom, cur_cred, malla_id, ciclo_num, carrera_id)
            VALUES (?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    if ($stmt = $conn->prepare($sql)) {
        // Vincular los parámetros
        $stmt->bind_param("ssiiii", $cur_id, $cur_nom, $cur_cred, $malla_id, $ciclo_num, $carrera_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a la página de administración después de agregar el curso
            header("Location: ../home/admin.html?success=1"); // Puedes agregar un parámetro de éxito si lo deseas
            exit(); // Asegúrate de salir después de redirigir
        } else {
            // Manejar el error de inserción
            $_SESSION['error'] = "Error al agregar curso: " . $stmt->error;
            header("Location: ../home/admin.html?error=1"); // Redirigir con un parámetro de error
            exit();
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