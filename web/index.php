<?php

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulario de Registro LOA</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">

</head>
<div class="title">
    <h2><em>LOA ESCUELA DE SURF</em></h2>

</div>

<body>
<div class="group">

    <form  action="registro.php" method="POST" >

        <label for="nombre">Nombre  </label>
        <input type="text" name="nombre" class="form-input" required/>

        <label for="apellido">Apellido </label>
        <input type="text" name="apellido" class="form-input" required/>

        <label for="edad">Edad </label>
        <input type="text" name="edad" class="form-input" text/>

        <label for="dni">DNI </label>
        <input type="text" name="dni" class="form-input" required/>

        <label for="telefono">Telefono </label>
        <input type="tel" name="telefono" class="form-input" text/>

        <label for="email">Email </label>
        <input type="email" name="email" class="form-input" />

        <label for="redsocial">Facebook / Instagram </label>
        <input type="text" name="redsocial" class="form-input" />

        <center> <input class="form-btn" name="submit" type="submit" value="Suscribirse" /></center>
        </p>


    </form>
</div>
<div class = "obs">
    <p><em>La dirección de correo electrónico
            que usted ha proporcionado ha sido previamente registrada en nuestra base de datos.</em></p>
</div>



</body>
</html>