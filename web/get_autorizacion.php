<?php

include __DIR__ . '/../config/config.php';
global $DBCONFIG_WEB_ALUMNOS;
global $DBCONFIG;
$db = mysqli_connect(
    $DBCONFIG_WEB_ALUMNOS['HOST'],
    $DBCONFIG_WEB_ALUMNOS['USERNAME'],
    $DBCONFIG_WEB_ALUMNOS['PASSWORD'],
    $DBCONFIG_WEB_ALUMNOS['DATABASE']
);


$dni = preg_replace('/\D/', '', $_GET['dni'] ?? '');
if (!$dni) {
    http_response_code(400);
    exit('DNI requerido');
}


$stmt = $db->prepare("
    SELECT pdf_path
    FROM autorizaciones
    WHERE alumno_dni = ?
    ORDER BY fecha_firma DESC
    LIMIT 1
");
$stmt->bind_param("s", $dni);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    http_response_code(404);
    exit('No existe autorización');
}

$row = $res->fetch_assoc();
$file = __DIR__ . '/../web/autorizaciones/pdfs/' . $row['pdf_path'];

if (!file_exists($file)) {
    http_response_code(404);
    exit('Archivo no encontrado');
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="autorizacion_' . $dni . '.pdf"');
readfile($file);
exit;
