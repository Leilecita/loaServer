<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro LOA - Éxito</title>

    <!-- Bootstrap 4 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(120deg, #0f4c75, #3282b8);
            font-family: 'Montserrat', sans-serif;
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
        }

        .fa-instagram {
            color: #E1306C;
            margin-right: 5px;
        }
    </style>
</head>

<body>
<div class="front">
    <img src="img/loa_logo_new.png" class="logoloa" />
    <h3>¡Suscripción completa!</h3>
    <?php if(isset($form['info'])){ ?>
        <p class="loa-text">Gracias por completar la inscripción para el/la alumno/a:</p>
        <h4 class="loa-text"><strong><?php echo ucfirst($form['name']) ?> <?php echo ucfirst($form['apellido']) ?></strong></h4>
    <?php } ?>
    <br>
    <a href="datos_alumno_colonia.php" class="btn btn-primary btn-block">Volver al inicio</a>
    <div class="card-footer mt-3">
        <p class="loa-text">Seguinos en <i class="fab fa-instagram"></i> loa.surflife</p>
    </div>
</div>
</body>
</html>
<!--  <?php if(isset($form['info']) && $form['info'] == "actualizada"){ ?>
        <p class="loa-text">Ya existe un registro con el DNI <strong><?php echo $form['dni'] ?></strong></p>
        <p class="loa-text">Se actualizó la información para el/la alumno/a:</p>
        <p class="loa-text"><strong><?php echo ucfirst($form['name']) ?> <?php echo ucfirst($form['apellido']) ?></strong></p>
    <?php } else { ?>  <?php } ?>
-->