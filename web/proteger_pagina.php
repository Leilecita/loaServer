<?php
// acceso_restringido.php
date_default_timezone_set('America/Argentina/Buenos_Aires');

// 📅 Fecha a partir de la cual se pedirá contraseña
$fecha_limite = strtotime('2026-1-1'); // Cambiá la fecha que quieras
$hoy = time();

$password_correcta = 'surf2025';

// ⚙️ Si la fecha ya pasó, se activa la protección
if ($hoy >= $fecha_limite) {
    session_start();

    // Si el usuario aún no inició sesión autorizada
    if (!isset($_SESSION['acceso_autorizado']) || $_SESSION['acceso_autorizado'] !== true) {

        // Si envió el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
            if ($_POST['password'] === $password_correcta) {
                $_SESSION['acceso_autorizado'] = true;
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            } else {
                $error = "Contraseña incorrecta";
            }
        }

        // Mostrar formulario
        ?>
        <!doctype html>
        <html lang="es">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Acceso restringido</title>
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    background: linear-gradient(120deg, #0f4c75, #3282b8);
                    color: #fff;
                    font-family: "Montserrat", sans-serif;
                    text-align: center;
                    padding-top: 100px;
                }
                .front {
                    background-color: #ffffff;
                    color: #333;
                    border-radius: 1rem;
                    padding: 25px;
                    margin: 0 auto;
                    width: 90%;
                    max-width: 400px;
                    box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
                }
                .btn-primary {
                    background-color: #0f4c75;
                    border: none;
                }
                img {
                    width: 80px;
                    margin-bottom: 15px;
                }
            </style>
        </head>
        <body>
        <div class="front">
            <img src="img/loa_logo_new.png" alt="LOA Logo">
            <h4>Acceso restringido</h4>
            <p>Ingresá la contraseña para continuar</p>
            <?php if (!empty($error)) echo '<p style="color:red;">' . $error . '</p>'; ?>
            <form method="POST">
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </form>
        </div>
        </body>
        </html>
        <?php
        exit;
    }
}
?>
