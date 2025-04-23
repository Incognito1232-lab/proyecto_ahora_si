<?php
include('../config/conexion.php');

function getFileIcon($filename) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
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
        'gif' => 'image-icon.png'
    ];
    return $iconMap[$extension] ?? 'default-icon.png';
}

if (isset($_GET['curso_id'])) {
    $curso_id = $_GET['curso_id'];
    
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
        <!-- PDF.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
        <script>
            // Configurar PDF.js worker
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';
            
            function renderPDF(pdfUrl, canvasId) {
                var canvas = document.getElementById(canvasId);
                var ctx = canvas.getContext('2d');
                
                // Mostrar mensaje de carga
                canvas.parentElement.innerHTML = '<div class="loading">Cargando vista previa...</div>';
                
                // Cargar PDF
                pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                    return pdf.getPage(1);
                }).then(function(page) {
                    var viewport = page.getViewport({scale: 0.8});
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    
                    // Restaurar el canvas
                    canvas.parentElement.innerHTML = '<canvas id="' + canvasId + '"></canvas>';
                    canvas = document.getElementById(canvasId);
                    ctx = canvas.getContext('2d');
                    
                    // Renderizar página
                    page.render({
                        canvasContext: ctx,
                        viewport: viewport
                    });
                }).catch(function(error) {
                    console.error('Error al renderizar PDF:', error);
                    canvas.parentElement.innerHTML = '<img src="../static/icons/pdf-error.png" alt="Error al cargar PDF" class="file-icon">';
                });
            }
            
            // Función para Office Viewer
            function renderOffice(url, containerId) {
                var container = document.getElementById(containerId);
                container.innerHTML = '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' + 
                                     encodeURIComponent(url) + '" width="100%" height="100%" frameborder="0"></iframe>';
            }
        </script>
    </head>
    <body>
        <div class="container">
            <h2>Archivos del Curso</h2>
            
            <?php if (!empty($archivos)): ?>
                <div class="grid">
                <?php foreach ($archivos as $archivo): 
                    $filename = htmlspecialchars($archivo['archivo_nom']);
                    $filepath = htmlspecialchars($archivo['archivo_ruta']);
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $safeId = preg_replace('/[^a-zA-Z0-9]/', '-', $filename);
                ?>

                        <div class="card">
                            <h3><?= $filename ?></h3>
                            <div class="preview-container" id="preview-<?= $safeId ?>">
                                <?php if ($extension === 'pdf'): ?>
                                    <canvas id="canvas-<?= $safeId ?>" class="pdf-preview"></canvas>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            renderPDF('<?= $filepath ?>', 'canvas-<?= $safeId ?>');
                                        });
                                    </script>
                                <?php elseif (in_array($extension, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'])): ?>
                                    <img src="../static/icons/<?= getFileIcon($filename) ?>" alt="<?= $extension ?> file" class="file-icon">
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            // Solo activar Office Viewer si el archivo es accesible públicamente
                                            // renderOffice('<?= $filepath ?>', 'preview-<?= $safeId ?>');
                                        });
                                    </script>
                                <?php elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                    <img src="<?= $filepath ?>" alt="Preview" style="max-width: 100%; max-height: 100%;">
                                <?php else: ?>
                                    <img src="../static/icons/<?= getFileIcon($filename) ?>" alt="<?= $extension ?> file" class="file-icon">
                                <?php endif; ?>
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