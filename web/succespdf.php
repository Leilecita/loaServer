<?php
function render($name){
    ob_start();
    ?>
    <!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Suscripción Completa</title>
        <link href="css/estilos.css" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    </head>

    <body>


    <div class="group">

        <h2><em> </em></h2>

        <div class="col-sm-8 offset-sm-2 col-xs-10 offset-xs-1">
            <div class="card">
                <h5 class="card-header">Suscripción completa al 10-01-2021  </h5>
                <div class="card-body">
                    <h5 class="card-title">Datos alumno</h5>
                    <p class="card-text">Leila del Campo <?php echo $name ?></p>

                    <a href="#" class="btn btn-primary">Go </a>
                </div>
            </div>

        </div>
    </body>
    </html>

    <?php
    return ob_get_clean();
}
?>
