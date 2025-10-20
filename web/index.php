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
        <div class="col-sm-8 offset-sm-2 col-xs-12">
            <form  action="registro.php" method="POST" >
                <fieldset class="withLogo">
                    <img src="img/logoloa.png" class="logoloa" />

                   <!-- <a href="https://www.loasurf.com.ar/public/docs/miarchivo.pdf" target="_blank">Descargar PDF</a>
                    <a href="https://http://localhost/loaserver/docs/miarchivo.pdf" target="_blank">Descargar PDF</a>-->
                    <h3>Datos alumno</h3>

                    <div class="form-group">
                        <label  for="nombre" class="col-form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required placeholder="" />
                    </div>
                    <div class="form-group">
                       <label  for="apellido">Apellido</label>
                        <input type="text" name="apellido" class="form-control"  placeholder="" />
                    </div>
                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <input type="number" name="dni" class="form-control" text placeholder=""/>
                    </div>

                    <div class="form-group">
                        <label  for="fecha_nacimiento">Fecha de nacimiento</label>
                        <div class="row">
                        <div class="col">
                            <input type="number" min="1" max="31" name="dia"  id="dia" class="form-control" required placeholder="día"/>
                        </div>
                            <div class="col">
                            <input type="number" name="mes" id="mes"  min="1" max="12" class="form-control" required placeholder="mes"/>
                            </div>
                            <div class="col">
                                <input type="number" name="anio" id="anio" max="2050" class="form-control" required placeholder="año"/>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="edad">Edad</label>
                        <input type="number" name="edad" id="edad" class="form-control" text placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label  for="tel_adulto">Telefono</label>
                        <input type="number" name="tel_adulto" class="form-control"  placeholder=""/>
                    </div>

                    <div class="form-group">
                        <label  for="instagram_adulto">Instagram</label>
                        <input type="text" name="instagram_adulto" class="form-control"  placeholder=""/>
                    </div>

                    <div class="form-group">
                        <label  for="observation">Información importante a tener en cuenta </label>
                        <input type="text" name="observation" maxlength="100" class="form-control"  placeholder=""/>
                    </div>

                        <div class="custom-control custom-checkbox  " data-toggle="collapse" data-target="#demo">
                            <input type="checkbox" class="custom-control-input" id="defaultChecked2" >
                                <label class="custom-control-label" for="defaultChecked2">Soy menor de edad</label>

                        </div>
                    <div id="demo" class="collapse">

                        <div class="row">
                            <div class="col-sm-6 col-xs-12">

                                <h3>Datos Mamá</h3>
                                <div class="form-group">
                                    <label for="nombre_mama" class="col-form-label">Nombre</label>
                                    <input type="text" name="nombre_mama" class="form-control"  placeholder=""/>
                                </div>
                                <div class="form-group">
                                    <label  for="tel_mama">Telefono</label>
                                    <input type="number" name="tel_mama" class="form-control"  placeholder=""/>
                                </div>

                                <div class="form-group">
                                    <label for="instagram_mama">Instagram</label>
                                    <input type="text" name="instagram_mama" class="form-control"  placeholder=""/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <h3>Datos Papá</h3>
                                <div class="form-group">
                                    <label for="nombre_papa" class="col-form-label">Nombre </label>
                                    <input type="text" name="nombre_papa" class="form-control"  placeholder=""/>
                                </div>
                                <div class="form-group">
                                    <label  for="tel_papa">Telefono </label>
                                    <input type="number" name="tel_papa" class="form-control"  placeholder=""/>
                                </div>

                                <div class="form-group">
                                    <label for="instagram_papa">Instagram</label>
                                    <input type="text" name="instagram_papa" class="form-control"  placeholder=""/>
                                </div>
                            </div>


                        </div>

                    </div>

                    <br>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="myCheck">
                            <label class="custom-control-label" for="myCheck" style="display: block; width: 100%;">
                                Por la presente dejo constancia que mi persona / o a quien autorizo está en buenas condiciones tanto físicas como psíquicas
                                para la práctica del deporte, entendiendo que el surf es un deporte de riesgos en su práctica.
                            </label>
                        </div>
                    </div>

                </fieldset>

                <button type="submit" onclick="myFunction()" class="btn btn-primary">Suscribirse</button>
            </form>
        </div>
    </div>
</div>


</body>

</html>
<style>

    label[for="observation"] {
        white-space: nowrap;
    }



</style>
<script>
    function calcularEdad() {
        const dia = parseInt(document.getElementById("dia").value);
        const mes = parseInt(document.getElementById("mes").value) - 1; // en JS los meses empiezan en 0
        const anio = parseInt(document.getElementById("anio").value);

        if (!dia || !mes || !anio) return;

        const hoy = new Date();
        const nacimiento = new Date(anio, mes, dia);

        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const m = hoy.getMonth() - nacimiento.getMonth();

        if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }
        document.getElementById("edad").value = edad >= 0 ? edad : "";
    }

    document.getElementById("dia").addEventListener("input", calcularEdad);
    document.getElementById("mes").addEventListener("input", calcularEdad);
    document.getElementById("anio").addEventListener("input", calcularEdad);

    function myFunction() {
        document.getElementById("myCheck").required = true;
    }
</script>


