<?php
include('../config/conexion.php');

function getFileIcon($filename) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    // Mapeo de extensiones a iconos
    $iconMap = [
        'pdf' => 'pdf-icon.png',
        'doc' => 'word-icon.png',
        'docx' => 'word-icon.png',
        'ppt' => 'ppt-icon.png',
        'pptx' => 'ppt-icon.png',
        'xls' => 'excel-icon.png',
        'xlsx' => 'excel-icon.png',
        'txt' => 'txt-icon.png',
        'jpg' => 'image-icon.png',
        'jpeg' => 'image-icon.png',
        'png' => 'image-icon.png',
        'zip' => 'zip-icon.png',
        'rar' => 'zip-icon.png',
    ];
    
    return $iconMap[$extension] ?? 'default-icon.png';
}

if (isset($_GET['curso_id'])) {
    $curso_id = $_GET['curso_id'];

    // Consulta para obtener el nombre del curso
    $queryCurso = "SELECT cur_nom FROM curso WHERE cur_id = ?";
    $stmtCurso = $conexion->prepare($queryCurso);
    $stmtCurso->bind_param("s", $curso_id);
    $stmtCurso->execute();
    $resultadoCurso = $stmtCurso->get_result();
    $curso = $resultadoCurso->fetch_assoc();
    $cursoNombre = $curso['cur_nom'] ?? 'Curso desconocido';
    $stmtCurso->close();
    
    $query = "SELECT archivo_nom, archivo_ruta FROM archivo WHERE cur_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $curso_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $archivos = [];
    while ($archivo = $resultado->fetch_assoc()) {
        $archivos[] = $archivo;
    }
    
    $stmt->close();
    $conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivos del Curso</title>
    <link rel="stylesheet" href="../static/mostrararchivos.css">
    <style>
        /* Estilos adicionales para los iconos */
        .file-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }
        .file-type {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Archivos del Curso: <?= htmlspecialchars($cursoNombre) ?></h2>
        
        <?php if (!empty($archivos)): ?>
            <div class="grid">
            <?php foreach ($archivos as $archivo): 
                $filename = htmlspecialchars($archivo['archivo_nom']); // Nombre del archivo
                $filepath = htmlspecialchars($archivo['archivo_ruta']); // Ruta completa del archivo
                $basename = basename($filepath); // Obtiene solo el nombre del archivo desde la ruta
                $extension = strtolower(pathinfo($basename, PATHINFO_EXTENSION)); // Extrae la extensión desde la ruta
                $icon = getFileIcon($basename);
               
                    // Texto descriptivo del tipo de archivo
                    $fileTypes = [
                        'pdf' => 'Documento PDF',
                        'doc' => 'Documento Word',
                        'docx' => 'Documento Word',
                        'ppt' => 'Presentación PowerPoint',
                        'pptx' => 'Presentación PowerPoint',
                        'xls' => 'Hoja de cálculo Excel',
                        'xlsx' => 'Hoja de cálculo Excel',
                        'txt' => 'Archivo de texto',
                        'jpg' => 'Imagen JPEG',
                        'jpeg' => 'Imagen JPEG',
                        'png' => 'Imagen PNG',
                        'gif' => 'Imagen GIF',
                        'zip' => 'Archivo comprimido',
                        'rar' => 'Archivo comprimido',
                        'mp4' => 'Video MP4',
                        'avi' => 'Video AVI',
                        'mp3' => 'Audio MP3',
                        'wav' => 'Audio WAV'
                    ];
                    
                    $fileType = $fileTypes[$extension] ?? 'Archivo ' . strtoupper($extension);
                ?>
                    <div class="card">
                        <h3><?= $filename ?></h3>
                        <div class="preview-container">
                            <img src="../static/icons/<?= $icon ?>" alt="<?= $fileType ?>" class="file-icon">
                        </div>
                        <div class="actions">
                            <a href="<?= $filepath ?>" target="_blank">Ver</a>
                            <a href="<?= $filepath ?>" download class="download">Descargar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No se encontraron archivos para este curso.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
} else {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="stylesheet" href="../static/mostrararchivos.css">
</head>
<body>
    <div class="container error">
        <h2>Error</h2>
        <p>No se recibió un ID de curso válido.</p>
    </div>
</body>
</html>
<?php
}
?>