<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
$dni = $_POST['dni'];
$aclaracion = $_POST['aclaracion'];
$dniAcl = $_POST['dniAcl'];
var_dump($dni);

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


var_dump($dni, strlen($dni));
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
