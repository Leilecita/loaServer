<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suscripción completa - LOA</title>

    <!-- Bootstrap 4 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(120deg, #0f4c75, #3282b8);
            color: #333;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .front {
            background-color: #ffffff;
            color: #333;
            border-radius: 1rem;
            padding: 30px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 0 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        .logoloa {
            display: block;
            margin: 0 auto 20px;
            width: 100px;
        }

        h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #0f4c75;
        }

        .loa-text {
            font-size: 1rem;
            margin: 10px 0;
            color: #333;
        }

        .btn-primary {
            background-color: #0f4c75;
            border-color: #0f4c75;
            font-weight: 600;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background-color: #3282b8;
            border-color: #3282b8;
        }

        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
            border-radius: 0 0 1rem 1rem;
            margin-top: 25px;
            padding: 10px;
        }

        .fa-instagram {
            color: #E1306C;
            margin-right: 5px;
        }

        .fa-whatsapp {
            color: #25D366;
            margin-left: 5px;
        }
    </style>
</head>

<body>
<div class="front">
    <img src="img/loa_logo_new.png" class="logoloa" alt="LOA Logo" />

    <h3>¡Suscripción completada!</h3>

    <?php if(isset($form['info']) ){ ?>
        <p class="loa-text">Gracias por completar la inscripción para el/la alumno/a:</p>
        <h4 class="loa-text"><strong><?php echo ucfirst($form['name']) ?> <?php echo ucfirst($form['apellido']) ?></strong></h4>
    <?php } ?>
    <br>
    <a href="index.php" class="btn btn-primary btn-block">Volver al inicio</a>

    <br>
    <p class="loa-text">
        Sumate a nuestro grupo de
        <a href="https://chat.whatsapp.com/TU_CODIGO_DE_INVITACION" target="_blank" title="Grupo de WhatsApp">
            <i class="fab fa-whatsapp"></i> WhatsApp
        </a>
        para recibir la info de las clases de surf.
    </p>

    <div class="card-footer">
        <p class="loa-text">Seguinos en <i class="fab fa-instagram"></i> loa.surflife</p>
    </div>
</div>
</body>
</html>
