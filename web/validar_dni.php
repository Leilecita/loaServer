<?php
header('Content-Type: application/json');

if (!isset($_POST['dni'])) {
    echo json_encode(['status' => 'error', 'message' => 'DNI faltante']);
    exit;
}

include __DIR__ . '/../config/config.php';
global $DBCONFIG_WEB_ALUMNOS;

$db = mysqli_connect(
    $DBCONFIG_WEB_ALUMNOS['HOST'],
    $DBCONFIG_WEB_ALUMNOS['USERNAME'],
    $DBCONFIG_WEB_ALUMNOS['PASSWORD'],
    $DBCONFIG_WEB_ALUMNOS['DATABASE']
);

$dni = preg_replace('/\D/', '', $_POST['dni']);

$stmt = $db->prepare("SELECT id FROM students WHERE dni = ?");
$stmt->bind_param("s", $dni);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(['status' => 'dni_not_found']);
} else {
    echo json_encode(['status' => 'ok']);
}
