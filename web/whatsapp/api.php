<?php
declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

function reply(int $code, array $data): void {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

if (empty($_SESSION['loa_whatsapp_authenticated'])) reply(401, ['error' => 'Acceso no autorizado']);

require_once __DIR__ . '/../../config/config.php';
global $DBCONFIG_WEB_ALUMNOS;

$db = new mysqli(
    (string) $DBCONFIG_WEB_ALUMNOS['HOST'],
    (string) ($DBCONFIG_WEB_ALUMNOS['USERNAME'] ?? 'root'),
    (string) $DBCONFIG_WEB_ALUMNOS['PASSWORD'],
    (string) $DBCONFIG_WEB_ALUMNOS['DATABASE'],
    (int) ($DBCONFIG_WEB_ALUMNOS['PORT'] ?? 3306)
);
if ($db->connect_errno) reply(500, ['error' => 'No se pudo conectar con MySQL']);
$db->set_charset('utf8mb4');

function normalizePhone(string $raw): ?string {
    $raw = trim($raw);
    $digits = preg_replace('/\D+/', '', $raw);
    if ($digits === '' || $digits === '0') return null;
    if (substr($digits, 0, 4) === '0054') $digits = substr($digits, 2);
    if (substr($digits, 0, 2) === '54') {
        if (strlen($digits) === 12) $digits = '549' . substr($digits, 2);
        return preg_match('/^549\d{10}$/D', $digits) ? $digits : null;
    }
    if ($digits[0] === '0') $digits = substr($digits, 1);
    if (preg_match('/^\d{10}$/D', $digits)) return '549' . $digits;
    if (preg_match('/^9\d{10}$/D', $digits)) return '54' . $digits;
    return preg_match('/^\+[1-9]\d{7,14}$/D', $raw) ? $digits : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // La fuente de verdad de alumnos sigue siendo students.
    $students = $db->query('SELECT nombre, apellido, tel_adulto, tel_mama, tel_papa FROM students ORDER BY apellido, nombre');
    if (!$students) reply(500, ['error' => 'No se pudieron consultar los alumnos']);
    $contacts = [];
    while ($student = $students->fetch_assoc()) {
        $name = trim(ucwords(strtolower((string) $student['nombre']))) . ' ' . trim(ucwords(strtolower((string) $student['apellido'])));
        foreach (['tel_adulto' => 'Adulto', 'tel_mama' => 'Mamá', 'tel_papa' => 'Papá'] as $column => $role) {
            $phone = normalizePhone((string) ($student[$column] ?? ''));
            if (!$phone) continue;
            if (!isset($contacts[$phone])) $contacts[$phone] = ['source_phone' => $phone, 'phone' => $phone, 'students' => [], 'roles' => [], 'sent' => false, 'sent_at' => null];
            if (!in_array($name, $contacts[$phone]['students'], true)) $contacts[$phone]['students'][] = $name;
            if (!in_array($role, $contacts[$phone]['roles'], true)) $contacts[$phone]['roles'][] = $role;
        }
    }
    $saved = $db->query('SELECT source_phone, phone, sent, sent_at FROM whatsapp_contacts');
    if (!$saved) reply(500, ['error' => 'No se pudo consultar whatsapp_contacts']);
    while ($row = $saved->fetch_assoc()) {
        $source = $row['source_phone'];
        if (!isset($contacts[$source])) continue;
        $contacts[$source]['phone'] = $row['phone'];
        $contacts[$source]['sent'] = (bool) $row['sent'];
        $contacts[$source]['sent_at'] = $row['sent_at'];
    }
    reply(200, ['contacts' => array_values($contacts)]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') reply(405, ['error' => 'Método no permitido']);
if (!hash_equals((string) ($_SESSION['loa_whatsapp_csrf'] ?? ''), (string) ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''))) reply(403, ['error' => 'Token inválido']);
$payload = json_decode((string) file_get_contents('php://input'), true);
if (!is_array($payload)) reply(400, ['error' => 'JSON inválido']);
$source = (string) ($payload['source_phone'] ?? '');
$phone = (string) ($payload['phone'] ?? '');
$sent = $payload['sent'] ?? null;
if (!preg_match('/^[0-9]{8,15}$/D', $source) || normalizePhone($phone) !== $phone || !is_bool($sent)) reply(422, ['error' => 'Teléfono o estado inválido']);

$stmt = $db->prepare('INSERT INTO whatsapp_contacts (source_phone, phone, sent, sent_at)
 VALUES (?, ?, ?, IF(? = 1, NOW(), NULL))
 ON DUPLICATE KEY UPDATE phone = VALUES(phone),
 sent_at = IF(VALUES(sent) = 1, IF(sent = 1, sent_at, NOW()), NULL),
 sent = VALUES(sent)');
if (!$stmt) reply(500, ['error' => 'Error al preparar escritura']);
$sentInt = (int) $sent;
$stmt->bind_param('ssii', $source, $phone, $sentInt, $sentInt);
if (!$stmt->execute()) reply(500, ['error' => 'No se pudo guardar']);
reply(200, ['ok' => true]);
