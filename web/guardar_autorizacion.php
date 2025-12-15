<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(204); // No Content
    exit;
}
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/apache2/error.log');
error_reporting(E_ALL);
$required = [
    'nombreAlumno',
    'dni',
    'aclaracion',
    'dniAcl',
    'firma_base64',
    'pdf_base64'
];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === '') {
        http_response_code(400);
        exit("Falta campo: $field");
    }
}

include __DIR__ . '/../config/config.php';
global $DBCONFIG_WEB_ALUMNOS;
global $DBCONFIG;
$db = mysqli_connect(
    $DBCONFIG_WEB_ALUMNOS['HOST'],
    $DBCONFIG_WEB_ALUMNOS['USERNAME'],
    $DBCONFIG_WEB_ALUMNOS['PASSWORD'],
    $DBCONFIG_WEB_ALUMNOS['DATABASE']
);

// datos
$nombre = $_POST['nombreAlumno'];
$dni = preg_replace('/\D/', '', $_POST['dni']);
$aclaracion = $_POST['aclaracion'];
$dniAcl = $_POST['dniAcl'];

$firmaBase64 = $_POST['firma_base64'];
$pdfBase64   = $_POST['pdf_base64'];

// ---------- FIRMA ----------
$firmaBase64 = str_replace('data:image/png;base64,', '', $firmaBase64);
$firmaBin = base64_decode($firmaBase64);

$firmaName = 'firma_'.$dni.'_'.time().'.png';

file_put_contents(__DIR__."/autorizaciones/firmas/$firmaName", $firmaBin);

// ---------- PDF ----------
$pdfBin = base64_decode($pdfBase64);

$pdfName = 'autorizacion_'.$dni.'_'.time().'.pdf';
file_put_contents(__DIR__."/autorizaciones/pdfs/$pdfName", $pdfBin);

error_log("DB USADA: " . $DBCONFIG_WEB_ALUMNOS['DATABASE']);

// ---------- BD ----------
// ---------- BUSCAR STUDENT_ID POR DNI ----------
$stmt = $db->prepare("SELECT id FROM students WHERE dni = ?");
$stmt->bind_param("s", $dni);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Alumno no encontrado";
    exit;
}

$row = $result->fetch_assoc();
$studentId = $row['id'];

// ---------- INSERT ----------
$stmt = $db->prepare("
    INSERT INTO autorizaciones
    (student_id, alumno_nombre, alumno_dni, firmante_dni, firmante_aclaracion, firma_path, pdf_path, fecha_firma)
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param(
    "issssss",
    $studentId,
    $nombre,
    $dni,
    $dniAcl,
    $aclaracion,
    $firmaName,
    $pdfName
);

$stmt->execute();
if ($stmt->affected_rows === 0) {
    error_log("No se insertó autorización");
}
echo "OK";
