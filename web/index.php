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



    <!-- Optional theme -->
   <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

</head>



<body>
<div class="background" ></div>
<div class="front container">
<div class="row">
    <div class="col-sm-8 offset-sm-2 col-xs-10 offset-xs-1">
        <form  action="registro.php" method="POST" >
            <fieldset class="withLogo">
                <img src="img/logoloa.png" class="logoloa" />


     <h3>Datos alumno</h3>

                <div class="form-group">
                    <label style="font-family:'Arial'" for="nombre" class="col-form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required placeholder="" />
                </div>
                <div class="form-group">
                   <label style="font-family:'Arial'" for="apellido">Apellido</label>
                    <input type="text" name="apellido" class="form-control"  placeholder="" />
                </div>
                <div class="form-group">
                    <label style="font-family:'Arial'" for="dni">DNI</label>
                    <input type="number" name="dni" class="form-control" text required placeholder=""/>
                </div>
               <!-- <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" text placeholder=""/>
                </div> -->

                <div class="row">
                    <div class='col-sm-6'>
                        <div class="form-group">
                            <label style="font-family:'Arial'" for="fecha_nacimiento">Fecha de nacimiento</label>

                            <div class='input-group date'>
                                <input type='text' name="fecha_nacimiento"  class="form-control" id='datetimepicker2' />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label style="font-family:'Arial'" for="edad">Edad</label>
                    <input type="number" name="edad" class="form-control" text placeholder=""/>
                </div>


               <!-- <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" name="direccion" class="form-control" text placeholder=""/>
                </div> -->
                <div class="form-group">
                    <label style="font-family:'Arial'" for="tel_adulto">Telefono</label>
                    <input type="number" name="tel_adulto" class="form-control"  placeholder=""/>
                </div>

               <!-- <div class="form-group">
                    <label for="localidad">Localidad</label>
                    <input type="text" name="localidad" class="form-control" text placeholder=""/>
                </div> -->

               <!-- <div class="form-group">
                    <label for="email_adulto" class="col-form-label">Email</label>
                    <input type="email" name="email_adulto" class="form-control"  placeholder=""/>
                </div> -->

                <div class="form-group">
                    <label style="font-family:'Arial'" for="instagram_adulto">Instagram</label>
                    <input type="text" name="instagram_adulto" class="form-control"  placeholder=""/>
                </div>

              <!--  <div class="form-group">
                    <label for="facebook_adulto">Facebook</label>
                    <input type="text" name="facebook_adulto" class="form-control"  placeholder=""/>
                </div> -->


                    <div class="custom-control custom-checkbox  " data-toggle="collapse" data-target="#demo">
                        <input type="checkbox" class="custom-control-input" id="defaultChecked2" >
                            <label class="custom-control-label" for="defaultChecked2">Soy menor de edad</label>

                    </div>
                <div id="demo" class="collapse">

                    <div class="row">
                        <div class="col-sm-6 col-xs-12">

                            <legend>Datos Mamá</legend>
                            <div class="form-group">
                                <label for="nombre_mama" class="col-form-label">Nombre</label>
                                <input type="text" name="nombre_mama" class="form-control"  placeholder=""/>
                            </div>
                            <div class="form-group">
                                <label style="font-family:'Arial'" for="tel_mama">Telefono</label>
                                <input type="number" name="tel_mama" class="form-control"  placeholder=""/>
                            </div>

                            <div class="form-group">
                                <label style="font-family:'Arial'" for="email_mama" class="col-form-label">Email</label>
                                <input type="email" name="email_mama" class="form-control"  placeholder=""/>
                            </div>

                            <div class="form-group">
                                <label style="font-family:'Arial'" for="instagram_mama">Instagram</label>
                                <input type="text" name="instagram_mama" class="form-control"  placeholder=""/>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <legend>Datos Papá</legend>
                            <div class="form-group">
                                <label style="font-family:'Arial'" for="nombre_papa" class="col-form-label">Nombre </label>
                                <input type="text" name="nombre_papa" class="form-control"  placeholder=""/>
                            </div>
                            <div class="form-group">
                                <label style="font-family:'Arial'" for="tel_papa">Telefono </label>
                                <input type="number" name="tel_papa" class="form-control"  placeholder=""/>
                            </div>

                            <div class="form-group">
                                <label style="font-family:'Arial'" for="email_papa" class="col-form-label">Email</label>
                                <input type="email" name="email_papa" class="form-control"  placeholder=""/>
                            </div>

                            <div class="form-group">
                                <label style="font-family:'Arial'" for="instagram_papa">Instagram</label>
                                <input type="text" name="instagram_papa" class="form-control"  placeholder=""/>
                            </div>
                        </div>


                    </div>

                </div>

                <br>


                <div class="custom-control custom-checkbox ">
                    <input type="checkbox" class="custom-control-input" id="myCheck" >
                    <label class="custom-control-label" for="myCheck">

                        <p>

                            Por la presente dejo constancia que mi persona / o a quien autorizo está en buenas condiciones tanto físicas como psíquicas
                            para la práctica del deporte, entendiendo que el surf es un deporte de riesgos en su práctica.


                        </p>
                    </label>

                </div>
            </fieldset>



            </p>


            <button type="submit" onclick="myFunction()" class="btn btn-primary">Suscribirse</button>
        </form>
    </div>
</div>
</div>
</div>


</body>
</html>

<script>
    function myFunction() {
        document.getElementById("myCheck").required = true;
    }

    $(function() {
        $('#datetimepicker2').datetimepicker({
            viewMode: 'months',
            format: 'YYYY-MM-DD'
        });
    });


</script>

<!--
 <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="defaultChecked2" required>
                    <label class="custom-control-label" for="defaultChecked2">Default checked</label>
                </div>
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