<?php

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Formulario de Registro LOA</title>
    <link href="css/estilos.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</head>



<body>
<div class="background" ></div>
<div class="front container">
<div class="row">
    <div class="col-sm-8 offset-sm-2 col-xs-10 offset-xs-1">
        <form  action="registro.php" method="POST" >
            <fieldset class="withLogo">
                <img src="img/logoloa.png" class="logoloa" />

                <legend><h4>Datos alumno</h4></legend>
                <div class="form-group">
                    <label for="nombre" class="col-form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required placeholder=""/>
                </div>
                <div class="form-group">
                   <label for="apellido">Apellido</label>
                    <input type="text" name="apellido" class="form-control"  placeholder=""/>
                </div>
                <div class="form-group">
                    <label for="dni">DNI</label>
                    <input type="text" name="dni" class="form-control" text required placeholder=""/>
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" text placeholder=""/>
                </div>
                <div class="form-group">
                    <label for="edad">Edad</label>
                    <input type="text" name="edad" class="form-control" text placeholder=""/>
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" name="direccion" class="form-control" text placeholder=""/>
                </div>
                <div class="form-group">
                    <label for="localidad">Localidad</label>
                    <input type="text" name="localidad" class="form-control" text placeholder=""/>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <legend>Datos Mamá</legend>
                        <div class="form-group">
                            <label for="nombre_mama" class="col-form-label">Nombre</label>
                            <input type="text" name="nombre_mama" class="form-control"  placeholder=""/>
                        </div>
                        <div class="form-group">
                            <label for="tel_mama">Telefono</label>
                            <input type="text" name="tel_mama" class="form-control"  placeholder=""/>
                        </div>

                        <div class="form-group">
                            <label for="email_mama" class="col-form-label">Email</label>
                            <input type="email" name="email_mama" class="form-control"  placeholder=""/>
                        </div>

                        <div class="form-group">
                            <label for="instagram_mama">Instagram</label>
                            <input type="text" name="instagram_mama" class="form-control"  placeholder=""/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <legend>Datos Papá</legend>
                        <div class="form-group">
                            <label for="nombre_papa" class="col-form-label">Nombre </label>
                            <input type="text" name="nombre_papa" class="form-control"  placeholder=""/>
                        </div>
                        <div class="form-group">
                            <label for="tel_papa">Telefono </label>
                            <input type="text" name="tel_papa" class="form-control"  placeholder=""/>
                        </div>

                        <div class="form-group">
                            <label for="email_papa" class="col-form-label">Email</label>
                            <input type="email" name="email_papa" class="form-control"  placeholder=""/>
                        </div>

                        <div class="form-group">
                            <label for="instagram_papa">Instagram</label>
                            <input type="text" name="instagram_papa" class="form-control"  placeholder=""/>
                        </div>
                    </div>
                </div>



                </fieldset>





            </p>
            <button type="submit" class="btn btn-primary">Suscribirse</button>
        </form>
    </div>
</div>
</div>
</div>


</body>
</html>

<!--

 <fieldset class="form-group">
                    <legend>Checkboxes</legend>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value="" checked="">
                            Option one is this and that—be sure to include why it's great
                        </label>
                    </div>
                    <div class="form-check disabled">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value="" disabled="">
                            Option two is disabled
                        </label>
                    </div>
                </fieldset>
            </fieldset>

-->