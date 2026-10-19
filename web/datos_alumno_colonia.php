<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de Registro LOA</title>

    <!-- Bootstrap 4 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>

        .front {
            background-color: #ffffff;
            color: #333;
            border-radius: 1rem;
            padding: 25px;
            margin-top: 40px;
            margin-bottom: 40px; /* <- agregado */
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
        }

        .form-row .form-control {
            border: 1px solid #ced4da; /* borde completo, mismo color que Bootstrap */
            border-radius: .25rem;
        }
        textarea .form-control {
            resize: none;
            border-radius: .25rem;
            padding-left: 12px; /* <- agrega esto */
        }
        body {
            background: linear-gradient(120deg, #0f4c75, #3282b8);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
        }

        .logoloa {
            display: block;
            margin: 0 auto 20px;
            width: 100px;
        }

        h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #0f4c75;
            text-align: center;
        }

        .input-group-text {
            background-color: #fff;
            border-right: 0;
        }

        .form-control {
            border-left: 0;
            border-radius: 0 0.5rem 0.5rem 0;
            padding-left: 12px !important;
        }

        textarea.form-control {
            resize: none;
            border-radius: 0.5rem;
            border-left: 1px solid #ced4da; /* agrega el borde izquierdo */
            padding-left: 12px;
        }

        .input-group-text i {
            color: #0f4c75;
            font-size: 1rem;
        }

        textarea.form-control {
            resize: none;
            border-radius: 0.5rem;
        }

        hr {
            border-top: 1px solid #ddd;
        }

        @media (max-width: 767px) {
            .front {
                padding: 20px 15px;
            }
        }
        /* Eliminar la línea vertical entre ícono y campo Día */
        .input-group-prepend .input-group-text {
            border-right: none !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        /* Quitar borde izquierdo y radio del primer campo (día) */
        .form-row .col-4:first-child .form-control {
            border-left: none !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

    </style>
</head>

<body>
<div class="container">
    <div class="front col-md-8 offset-md-2">
        <form action="registro_colonia.php" method="POST">
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
                    <input type="number" name="dni" class="form-control" placeholder="Número de documento" />
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


                <input type="hidden" name="edad" id="edad">

            <!-- Fecha de nacimiento
            <label>Fecha de nacimiento</label>
            <div class="form-row">
                <div class="col-4 mb-2">
                    <input type="number" min="1" max="31" name="dia" id="dia" class="form-control" required placeholder="Día" />
                </div>
                <div class="col-4 mb-2">
                    <input type="number" name="mes" id="mes" min="1" max="12" class="form-control" required placeholder="Mes" />
                </div>
                <div class="col-4 mb-2">
                    <input type="number" name="anio" id="anio" max="2050" class="form-control" required placeholder="Año" />
                </div>
            </div>
            <input type="hidden" name="edad" id="edad" />-->

            <input type="hidden" name="tel_adulto" id="tel_adulto" />
            <input type="hidden" name="instagram_adulto" id="instagram_adulto" />
            <input type="hidden" name="observation" id="observation" />
            <hr>

            <!-- Mamá -->
            <h3>Datos mamá</h3>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    </div>
                    <input type="text" name="nombre_mama" class="form-control" placeholder="Nombre completo" />
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                    </div>
                    <input type="number" name="tel_mama" class="form-control" placeholder="Teléfono" />
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-brands fa-instagram"></i></span>
                    </div>
                    <input type="text" name="instagram_mama" class="form-control" placeholder="instagram" />
                </div>
            </div>

            <!-- Papá -->
            <h3>Datos papá</h3>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    </div>
                    <input type="text" name="nombre_papa" class="form-control" placeholder="Nombre completo" />
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                    </div>
                    <input type="number" name="tel_papa" class="form-control" placeholder="Teléfono" />
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-brands fa-instagram"></i></span>
                    </div>
                    <input type="text" name="instagram_papa" class="form-control" placeholder="instagram" />
                </div>
            </div>

            <hr>

            <!-- Autorizados -->
            <h3>Autorizados a retirar</h3>

            <!-- Autorizado 1 -->
            <div class="form-row mb-2">
                <div class="col-md-4 mb-2"><input type="text" name="autorizado1_nombre" class="form-control" placeholder="Nombre completo" /></div>
                <div class="col-md-4 mb-2"><input type="number" name="autorizado1_dni" class="form-control" placeholder="DNI" /></div>
                <div class="col-md-4 mb-2"><input type="text" name="autorizado1_parentesco" class="form-control" placeholder="Parentesco" /></div>
            </div>

            <!-- Autorizado 2 -->
            <div class="form-row mb-2">
                <div class="col-md-4 mb-2"><input type="text" name="autorizado2_nombre" class="form-control" placeholder="Nombre completo" /></div>
                <div class="col-md-4 mb-2"><input type="number" name="autorizado2_dni" class="form-control" placeholder="DNI" /></div>
                <div class="col-md-4 mb-2"><input type="text" name="autorizado2_parentesco" class="form-control" placeholder="Parentesco" /></div>
            </div>

            <!-- Autorizado 3 -->
            <div class="form-row mb-2">
                <div class="col-md-4 mb-2"><input type="text" name="autorizado3_nombre" class="form-control" placeholder="Nombre completo" /></div>
                <div class="col-md-4 mb-2"><input type="number" name="autorizado3_dni" class="form-control" placeholder="DNI" /></div>
                <div class="col-md-4 mb-2"><input type="text" name="autorizado3_parentesco" class="form-control" placeholder="Parentesco" /></div>
            </div>

            <hr>


            <!-- SALUD -->
            <h3 class="titulo-seccion">
                <i class="fa-solid fa-notes-medical"></i>
                Salud
            </h3>
            <div class="form-group">
    <textarea name="salud" class="form-control" rows="3"
              placeholder="Detalle aquí cualquier Alergia / Medicamento / Enfermedad / Lesión que sea importante detallar"></textarea>
            </div>
            <!-- ACTIVIDADES Y GUSTOS -->
            <h3 class="titulo-seccion">
                <i class="fa-solid fa-person-running"></i>
                Actividades y gustos
            </h3>
            <div class="form-group">
    <textarea name="deportes" class="form-control" rows="2"
              placeholder="Detalle brevemente gustos y/o actividades deportivas y artísticas"></textarea>
            </div>

            <!-- SABE NADAR -->
            <div class="form-group">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fa-solid fa-person-swimming"></i>
            </span>
                    </div>
                    <input type="text" name="sabe_nadar" class="form-control" placeholder="¿Sabe nadar?" />
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary btn-block mt-3">Suscribirse</button>
            <br>
        </form>
    </div>
</div>



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
