<?php include 'proteger_pagina.php'; ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autorizaciones LOA</title>

    <!-- Bootstrap 4 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(120deg, #0f4c75, #3282b8);
            font-family: 'Montserrat', sans-serif;
            color: #093b5e;
        }
        .front {
            background-color: #fff;
            color: #333;
            border-radius: 1rem;
            padding: 25px;
            margin-top: 40px;
            margin-bottom: 40px;
            box-shadow: 0 0 25px rgba(0,0,0,0.1);
        }
        h1, h2, h3, h4 { color: #0f4c75; }
        h1 { text-align: center; margin-bottom: 30px; }
        p { text-align: justify; color: #093b5e; }
        canvas {
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            width: 100%;
            max-width: 600px;
            height: 150px;
            touch-action: none;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.075);
        }
        .btn-block { width: 100%; }
        label { color: #0f4c75; font-family: 'Montserrat', sans-serif; }
        .input-group-text i { color: #0f4c75; font-size: 1rem; }
        .form-control { padding-left: 12px; }
        textarea.form-control { resize: none; }
        br { line-height: 0.5; }
    </style>
</head>
<body>
<div class="container">
    <div class="front col-md-12">
        <form id="formulario">
            <img src="img/loa_logo_new.png" class="d-block mx-auto mb-3" width="100">

            <div class="mb-3">
                <label for="nombreAlumno">Alumno/a</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    </div>
                    <input type="text" id="nombreAlumno" name="nombreAlumno" class="form-control" required placeholder="Nombre del alumno">
                </div>
                <label for="dni">DNI</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-regular fa-id-card"></i></span>
                    </div>
                    <input type="text" id="dni" name="dni" class="form-control"  inputmode="numeric"
                           pattern="[0-9]*"
                           maxlength="8" required placeholder="Número de documento">
                </div>
                <br>
            </div>

            <h4>Autorización de participación en actividades deportivas</h4>
            <p>Por la presente dejo constancia que me hago responsable y autorizo a mi hijo/a a participar de la Colonia de Playa LOA, dejando sentado que está en buenas condiciones físicas y psíquicas para la práctica del deporte. Entiendo y acepto que mi hijo/a practique todas las actividades deportivas y recreativas del cronograma, siendo que el surf y algunas actividades conllevan riesgos en su práctica.</p>
            <br>

            <h4>Autorización de uso de imagen</h4>
            <p>Mi hijo/a podrá ser fotografiado/a o grabado/a durante su participación en la Escuela/Colonia LOA. Autorizo que estas imágenes se utilicen con fines educativos, informativos y de difusión de las actividades, siempre cuidando su integridad y respeto.</p>
            <br>

            <!-- <h4>2. Autorización asistencia para el cambiado en playa</h4>
             <p>Entiendo que durante las actividades en la playa, mi hijo/a necesitará asistencia para cambiarse de ropa. Autorizo y confío en el personal de la Escuela/Colonia LOA para ayudarlo/a de manera respetuosa y segura, cuidando su privacidad en todo momento.</p>
             <br> -->

            <p style="margin-bottom:2rem;">Nosotros por nuestra parte contamos con seguros de responsibilidad civil y cada alumno cuenta con su seguro de AP deportivo (accidente personal deportivo) ya diseñado especialmente para nuestro formato.</p>
            <hr><br>

            <h4>Firma del padre/madre/tutor</h4>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="acepto" required>
                <label class="form-check-label" for="acepto">Acepto todos los puntos detallados anteriormente</label>
            </div>
            <p style="font-size: 0.9rem;">(*) Si no está de acuerdo con alguno de estos puntos, comuníquese con nosotros.</p>
            <br>

            <p>Por favor, firme aquí:</p>
            <canvas id="firmaCanvas" width="400" height="150"></canvas>
            <br>
            <button type="button" id="borrarFirma" class="btn btn-secondary btn-sm mt-2">Borrar firma</button>
            <br><br>

            <label for="aclaracion">Aclaración </label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa-solid fa-pen"></i></span>
                </div>
                <textarea id="aclaracion" name="aclaracion" class="form-control" rows="1" placeholder="Ingrese aclaración aquí..." required></textarea>
            </div>

            <label for="dniAcl">DNI de quien firma</label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa-regular fa-id-card"></i></span>
                </div>
                <input type="text" id="dniAcl" name="dniAcl" class="form-control" placeholder="Ingrese DNI aquí..." required  inputmode="numeric"
                       pattern="[0-9]*"
                       maxlength="8">
            </div>
            <br><br>

            <button type="submit" class="btn btn-primary btn-block mt-3">Generar PDF a enviar</button>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    let enviando = false;

    const canvas = document.getElementById("firmaCanvas");
    const ctx = canvas.getContext("2d");
    let dibujando = false;

    canvas.addEventListener("mousedown", () => dibujando = true);
    canvas.addEventListener("mouseup", () => { dibujando = false; ctx.beginPath(); });
    canvas.addEventListener("mouseout", () => { dibujando = false; ctx.beginPath(); });
    canvas.addEventListener("mousemove", dibujar);
    canvas.addEventListener("touchstart", (e) => { dibujando = true; e.preventDefault(); });
    canvas.addEventListener("touchend", (e) => { dibujando = false; ctx.beginPath(); e.preventDefault(); });
    canvas.addEventListener("touchmove", (e) => {
        const rect = canvas.getBoundingClientRect();
        const touch = e.touches[0];
        dibujar({offsetX: touch.clientX - rect.left, offsetY: touch.clientY - rect.top});
        e.preventDefault();
    });

    function dibujar(e) {
        if(!dibujando) return;
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        let x, y;
        if(e.touches) {
            x = (e.touches[0].clientX - rect.left) * scaleX;
            y = (e.touches[0].clientY - rect.top) * scaleY;
        } else {
            x = (e.offsetX !== undefined ? e.offsetX : e.layerX) * scaleX;
            y = (e.offsetY !== undefined ? e.offsetY : e.layerY) * scaleY;
        }
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.strokeStyle = "#000";
        ctx.lineTo(x,y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(x,y);
    }

    document.getElementById("borrarFirma").addEventListener("click", () => {
        ctx.clearRect(0,0,canvas.width,canvas.height);
        ctx.beginPath();
    });

    document.getElementById("formulario").addEventListener("submit", async (e) => {
        e.preventDefault();

        if (!dniValido) {
            alert("❌ Verificá el DNI del alumno antes de continuar");
            enviando = false;
            return;
        }

        if (enviando) return;
        enviando = true;

        if(!document.getElementById("acepto").checked){
            alert("Debes aceptar todos los puntos para generar el PDF.");
            enviando = false;
            return;
        }


        // nuevo

        //

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();
        pdf.setFillColor(255,255,255);
        pdf.roundedRect(10,10,190,277,5,5,'F');

        const imgLogo = new Image();
        imgLogo.src = 'img/loa_logo_new.png';
        imgLogo.onload = () => {
            const logoWidth = 25;
            const logoHeight = (imgLogo.height / imgLogo.width) * logoWidth;
            pdf.addImage(imgLogo, 'PNG', 210 - logoWidth - 15, 15, logoWidth, logoHeight);
            generarPDF();
        };

        function generarPDF(){
            const nombre = document.querySelector('input[name="nombreAlumno"]').value || "";
            const dniAlumno = document.querySelector('input[name="dni"]').value || "";
            const aclaracion = document.getElementById("aclaracion").value || "";
            const dniAcl = document.getElementById("dniAcl").value || "";
            const fechaStr = new Date().toLocaleDateString();
            const imgData = canvas.toDataURL("image/png");
            // const imgData = canvas.toDataURL("image/jpeg", 0.6);

            pdf.setFont('helvetica','bold');
            pdf.setFontSize(16);
            pdf.setTextColor(15,76,117);
            pdf.text("Autorizaciones Colonia LOA",105,30,{align:"center"});

            pdf.setFont('helvetica','bold');
            pdf.setFontSize(12);
            pdf.text("Alumno/a:", 20, 55);
            pdf.setFont('helvetica','normal');
            pdf.text(nombre, 50, 55);

            pdf.setFont('helvetica','bold');
            pdf.text("DNI:", 20, 65);
            pdf.setFont('helvetica','normal');
            pdf.text(dniAlumno, 50, 65);

            pdf.setFont('helvetica','bold');
            pdf.text("Fecha:", 20, 75);
            pdf.setFont('helvetica','normal');
            pdf.text(fechaStr, 50, 75);

            let y = 96;
            const textos = [
                {
                    titulo: "Autorización de participación en actividades deportivas",
                    texto: "Por la presente dejo constancia que me hago responsable y autorizo a mi hijo/a a participar de la Colonia de Playa LOA, dejando sentado que está en buenas condiciones físicas y psíquicas para la práctica del deporte. Entiendo y acepto que mi hijo/a practique todas las actividades deportivas y recreativas del cronograma, siendo que el surf y algunas actividades conllevan riesgos en su práctica."
                },
                {
                    titulo: "Autorización de uso de imagen",
                    texto: "Mi hijo/a podrá ser fotografiado/a o grabado/a durante su participación en la Escuela/Colonia LOA. Autorizo que estas imágenes se utilicen con fines educativos, informativos y de difusión de las actividades, siempre cuidando su integridad y respeto."
                },
                {
                    titulo: "Información sobre seguros",
                    texto: "Nosotros por nuestra parte contamos con seguros de responsabilidad civil y cada alumno cuenta con su seguro de AP deportivo (accidente personal deportivo) ya diseñado especialmente para nuestro formato."
                }
            ];

            textos.forEach(item => {
                pdf.setFont('helvetica','bold');
                pdf.setFontSize(14);
                pdf.setTextColor(15,76,117);
                pdf.text(item.titulo,20,y);
                y += 8;
                pdf.setFont('helvetica','normal');
                pdf.setFontSize(12);
                const splitText = pdf.splitTextToSize(item.texto,170);
                pdf.text(splitText,20,y);
                y += splitText.length*6 + 10;
            });

            pdf.setFont('helvetica','bold');
            pdf.text("Firma del padre/madre/tutor:",20,y);
            // pdf.addImage(imgData,'PNG',20,y+5,100,50);
            pdf.addImage(imgData,'JPEG',20,y+5,100,50);

            pdf.setFont('helvetica','bold');
            pdf.text("Aclaración:",130,y);
            pdf.setFont('helvetica','normal');
            pdf.text(aclaracion,130,y+10,{maxWidth:70});

            pdf.setFont('helvetica','bold');
            pdf.text("DNI:",130,y+25);
            pdf.setFont('helvetica','normal');
            pdf.text(dniAcl,130,y+35,{maxWidth:70});

            const pdfBlob = pdf.output('blob');
            const url = URL.createObjectURL(pdfBlob);

            // para guardar

            const firmaBase64 = canvas.toDataURL("image/png");
            // const firmaBase64 = canvas.toDataURL("image/jpeg", 0.6);

            const pdfBase64 = pdf.output("datauristring").split(',')[1];


            const formData = new FormData();
            formData.append("nombreAlumno", nombre);
            formData.append("dni", dniAlumno);
            formData.append("aclaracion", aclaracion);
            formData.append("dniAcl", dniAcl);
            formData.append("firma_base64", firmaBase64);
            formData.append("pdf_base64", pdfBase64);

            fetch("guardar_autorizacion.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'ok') {
                        alert("Autorización guardada correctamente");
                        resetFormulario();
                    }
                    else if (data.status === 'dni_not_found') {
                        enviando = false;
                        alert("❌ El DNI del alumno no existe en el sistema");
                    }
                    else {
                        enviando = false;
                        alert(data.message || "Error al guardar la autorización");
                    }
                })


            ////

            // Simula bloqueo de ventana para pruebas
            const simulateTest = false;
            const win = simulateTest ? null : window.open(url, '_blank');

            if (!win) {
                const file = new File([pdfBlob], 'AutorizacionLOA.pdf', { type: 'application/pdf' });
                if (navigator.canShare && navigator.canShare({ files: [file] })) {
                    navigator.share({ title: 'Autorización LOA', files: [file] })
                        .catch(() => {
                            alert('No se pudo compartir automáticamente. Descarga manual iniciada.');
                            descargarPDF(url);
                        });
                } else {
                    alert("Tu navegador bloqueó la apertura automática. Descarga iniciada.");
                    descargarPDF(url);
                }
            }

            function descargarPDF(url) {
                const a = document.createElement('a');
                a.href = url;
                a.download = 'AutorizacionLOA.pdf';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
        }
    });

    function resetFormulario() {
        // Reset inputs del form
        document.getElementById("formulario").reset();

        // Limpiar firma
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.beginPath();

        // Volver a permitir envíos
        enviando = false;
    }

    let dniValido = false;
    const dniInput = document.getElementById("dni");

    dniInput.addEventListener("change", validarDNI);
    dniInput.addEventListener("keyup", () => {
        if (dniInput.value.length >= 7) {
            validarDNI();
        }
    });

    function validarDNI() {
        const dni = dniInput.value.trim();

        if (dni.length < 7) {
            dniValido = false;
            return;
        }

        fetch("validar_dni.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "dni=" + encodeURIComponent(dni)
        })
            .then(res => res.text())
            .then(text => {
                console.log("RESPUESTA DNI:", text);

                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    alert("⚠️ Error del servidor al validar DNI");
                    dniValido = false;
                    return;
                }

                if (data.status === "dni_not_found") {
                    dniValido = false;
                    // alert("❌ El DNI del alumno no existe en el sistema");
                    dniInput.focus();
                } else if (data.status === "ok") {
                    dniValido = true;
                }
            })
            .catch(() => {
                dniValido = false;
                alert("❌ Error de conexión al validar DNI");
            });
    }



</script>
</body>
</html>
