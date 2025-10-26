<?php
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de Registro LOA</title>

    <!-- Bootstrap 4 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(120deg, #0f4c75, #3282b8);
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }

        .front {
            background-color: #ffffff;
            border-radius: 1rem;
            padding: 25px;
            margin: 40px auto;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
        }

        .logoloa {
            display: block;
            margin: 0 auto 20px;
            width: 100px;
        }

        h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 25px 0 20px;
            color: #0f4c75;
            text-align: center;
        }

        .input-group-text {
            background-color: #fff;
            border-right: 0;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: .5rem;
            padding-left: 12px !important;
        }

        textarea.form-control {
            resize: none;
            border-radius: 0.5rem;
        }

        .input-group-prepend .input-group-text i {
            color: #0f4c75;
        }

        hr {
            border-top: 1px solid #ddd;
        }

        label.custom-control-label {
            font-size: 0.9rem;
            text-align: justify;
        }

        .btn-primary {
            background-color: #0f4c75;
            border: none;
            border-radius: 0.5rem;
            padding: 10px;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background-color: #3282b8;
        }

        @media (max-width: 767px) {
            .front {
                padding: 20px 15px;
            }
        }

        .input-group-text {
            border-right: none !important;
            background-color: #fff;
        }

        .input-group .form-control {
            border-left: none !important;
        }

    </style>
</head>

<body>
<div class="container">
    <div class="col-md-8 offset-md-2 front">
        <form action="registro.php" method="POST">
            <img src="img/loa_logo_new.png" class="logoloa" />
            <h3>Datos del alumno</h3>

            <!-- Nombre -->
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    </div>
                    <input type="text" name="nombre" class="form-control" required placeholder="Nombre del alumno" />
                </div>
            </div>

            <!-- Apellido -->
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    </div>
                    <input type="text" name="apellido" class="form-control" placeholder="Apellido del alumno" />
                </div>
            </div>

            <!-- DNI -->
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-regular fa-id-card"></i></span>
                    </div>
                    <input type="number" name="dni" class="form-control" placeholder="DNI" />
                </div>
            </div>

            <!-- Fecha de nacimiento -->
            <div class="form-group">
                <div class="form-row">
                    <div class="col-4 pr-1">
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="fa-solid fa-cake-candles"></i>
                              </span>
                            </div>
                            <input type="number" min="1" max="31" name="dia" id="dia" class="form-control" placeholder="Día" required />
                        </div>
                    </div>
                    <div class="col-4 px-1">
                        <input type="number" min="1" max="12" name="mes" id="mes" class="form-control" placeholder="Mes" required />
                    </div>
                    <div class="col-4 pl-1">
                        <input type="number" name="anio" id="anio" max="2050" class="form-control" placeholder="Año" required />
                    </div>
                </div>
            </div>

            <!-- Edad -->
            <input type="hidden" name="edad" id="edad"  />

            <!-- Teléfono -->
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                    </div>
                    <input type="number" name="tel_adulto" class="form-control" placeholder="Teléfono" />
                </div>
            </div>

            <!-- Instagram -->
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-brands fa-instagram"></i></span>
                    </div>
                    <input type="text" name="instagram_adulto" class="form-control" placeholder="Instagram" />
                </div>
            </div>


            <h3>Salud</h3>
            <!-- Observaciones -->
            <div class="form-group">
                <textarea name="observation" maxlength="100" class="form-control" rows="3"
                          placeholder="Detalle alergias, enfermedades, lesiones u observaciones importantes que debamos tener en cuenta"></textarea>
            </div>

            <hr>
            <!-- Menor de edad -->
            <div class="custom-control custom-checkbox mb-3" data-toggle="collapse" data-target="#datosPadres">
                <input type="checkbox" class="custom-control-input" id="esMenor">
                <label class="custom-control-label" for="esMenor">Soy menor de edad</label>
            </div>

            <!-- Datos padres -->
            <div id="datosPadres" class="collapse">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Datos Mamá</h3>
                        <div class="form-group">
                            <input type="text" name="nombre_mama" class="form-control" placeholder="Nombre completo" />
                        </div>
                        <div class="form-group">
                            <input type="number" name="tel_mama" class="form-control" placeholder="Teléfono" />
                        </div>
                        <div class="form-group">
                            <input type="text" name="instagram_mama" class="form-control" placeholder="Instagram" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3>Datos Papá</h3>
                        <div class="form-group">
                            <input type="text" name="nombre_papa" class="form-control" placeholder="Nombre completo" />
                        </div>
                        <div class="form-group">
                            <input type="number" name="tel_papa" class="form-control" placeholder="Teléfono" />
                        </div>
                        <div class="form-group">
                            <input type="text" name="instagram_papa" class="form-control" placeholder="Instagram" />
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Consentimiento -->
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="myCheck" required>
                    <label class="custom-control-label" for="myCheck">
                        Por la presente dejo constancia que mi persona / o a quien autorizo está en buenas condiciones
                        tanto físicas como psíquicas para la práctica del deporte, entendiendo que el surf es un deporte
                        de riesgos en su práctica.
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3">Suscribirse</button>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    function calcularEdad() {
        const dia = parseInt(document.getElementById("dia").value);
        const mes = parseInt(document.getElementById("mes").value) - 1;
        const anio = parseInt(document.getElementById("anio").value);

        if (!dia || !mes || !anio) return;

        const hoy = new Date();
        const nacimiento = new Date(anio, mes, dia);

        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const m = hoy.getMonth() - nacimiento.getMonth();

        if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) edad--;

        document.getElementById("edad").value = edad >= 0 ? edad : "";
    }

    ["dia", "mes", "anio"].forEach(id => {
        document.getElementById(id).addEventListener("input", calcularEdad);
    });
</script>
</body>
</html>
