<?php

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulario de Registro LOA</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>



<body>

<div class="group">
    <form  action="registro.php" method="POST" >

        <div class="alumno">
            <p><em>Datos alumno</em></p>
        </div>

        <input type="text" name="nombre" class="form-input" required placeholder="Nombre"/>

        <input type="text" name="apellido" class="form-input" required placeholder="Apellido"/>

        <input type="text" name="nacimiento" class="form-input" text placeholder="Fecha de nacimiento"/>

        <input type="text" name="edad" class="form-input" text placeholder="Edad"/>

        <input type="text" name="dni" class="form-input" required placeholder="DNI"/>

        <input type="text" name="direccion" class="form-input" required placeholder="Dirección"/>

        <div class="alumno">
            <p><em>Datos padres</em></p>
        </div>

        <input type="text" name="nombremama" class="form-input" required  placeholder="Nombre mamá"/>

        <input type="tel" name="telefonomama" class="form-input" text placeholder="Teléfono"/>

        <input type="text" name="nombrepapa" class="form-input" required placeholder="Nombre papá"/>

        <input type="tel" name="telefonopapa" class="form-input" text placeholder="Teléfono"/>

        <input type="email" name="email" class="form-input" placeholder="Email" />


        <input type="text" name="redsocial" class="form-input" placeholder="Instagram" />

        <center> <input class="form-btn" name="submit" type="submit" value="Suscribirse" /></center>
        </p>

    </form>
</div>



</body>
</html>

<!--

<div class="title">
    <h2><em>LOA ESCUELA DE SURF</em></h2>
</div>

<div class = "obs">
    <p><em>La dirección de correo electrónico
            que usted ha proporcionado ha sido previamente registrada en nuestra base de datos.</em></p>
</div>

-->