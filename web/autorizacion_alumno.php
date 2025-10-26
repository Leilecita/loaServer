<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autorizaciones LOA</title>

    <!-- Bootstrap 4 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(120deg, #0f4c75, #3282b8);
            font-family: 'Montserrat', sans-serif;
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
        h1, h2, h3, h4 {
            color: #0f4c75;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            margin-top: 25px;
            margin-bottom: 10px;
        }
        p {
            text-align: justify;
            color: #093b5e;
        }
        canvas {
            border:1px solid #000;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 600px;
            height: 150px;
            touch-action: none;
        }
        .btn-block {
            width: 100%;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="front col-md-12 ">
        <form id="formulario">
            <img src="img/loa_logo_new.png" class="d-block mx-auto mb-3" width="100">

            <div class="mb-3">
                <p><strong>Alumno/a:</strong> <input type="text" name="nombreAlumno" class="form-control" placeholder="Nombre del alumno"></p>
                <p><strong>Fecha:</strong> <input type="text" name="fechaHoy" class="form-control" placeholder="DD/MM/AAAA"></p>
            </div>
            <br>
            <h4>1. Autorización de uso de imagen</h4>
            <p>
                Mi hijo/a podrá ser fotografiado/a o grabado/a durante su participación en la Escuela/Colonia LOA.
                Autorizo que estas imágenes se utilicen con fines educativos, informativos y de difusión de las actividades, siempre cuidando su integridad y respeto.
            </p>
            <br>
            <h4>2. Autorización asistencia para el cambiado en playa</h4>
            <p>
                Entiendo que durante las actividades en la playa, mi hijo/a necesitará asistencia para cambiarse de ropa.
                Autorizo y confío en el personal de la Escuela/Colonia LOA para ayudarlo/a de manera respetuosa y segura, cuidando su privacidad en todo momento.
            </p>
            <br>
            <h4>3. Autorización participación en actividades deportivas</h4>
            <p>
                Por la presente dejo constancia que me hago responsable y autorizo a mi hijo/a a participar de la Colonia de Playa LOA, dejando sentado que está en buenas condiciones físicas y psíquicas para la práctica del deporte.
                Entiendo y acepto que mi hijo/a practique todas las actividades deportivas y recreativas del cronograma, siendo que el surf y algunas actividades conllevan riesgos en su práctica.
            </p>

            <hr>
            <h4>Firma del padre/madre/tutor</h4>
            <p>Por favor, firme aquí:</p>
            <canvas id="firmaCanvas" width="400" height="150"></canvas>
            <br>
            <button type="button" id="borrarFirma" class="btn btn-secondary btn-sm mt-2">Borrar firma</button>
            <br><br>

            <label for="aclaracion">Aclaración </label>
            <textarea id="aclaracion" name="aclaracion" class="form-control" rows="1" placeholder="Ingrese aclaración aquí..."></textarea>
            <br><br>

            <button type="submit" class="btn btn-primary btn-block mt-3">Generar PDF</button>
        </form>
    </div>
</div>

<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
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

        // Obtener tamaño real y tamaño mostrado
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;

        let x, y;
        if (e.touches) {
            x = (e.touches[0].clientX - rect.left) * scaleX;
            y = (e.touches[0].clientY - rect.top) * scaleY;
        } else {
            x = (e.offsetX !== undefined ? e.offsetX : e.layerX) * scaleX;
            y = (e.offsetY !== undefined ? e.offsetY : e.layerY) * scaleY;
        }

        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.strokeStyle = "#000";
        ctx.lineTo(x, y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(x, y);
    }


    document.getElementById("borrarFirma").addEventListener("click", () => {
        ctx.clearRect(0,0,canvas.width,canvas.height);
        ctx.beginPath();
    });

    document.getElementById("formulario").addEventListener("submit", (e) => {
        e.preventDefault();
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

// Fondo "tarjeta"
        pdf.setFillColor(255,255,255);
        pdf.roundedRect(10,10,190,277,5,5,'F');

// Logo
        const imgLogo = new Image();
        imgLogo.src = 'img/loa_logo_new.png';
        imgLogo.onload = () => {
            const logoWidth = 25; // ancho deseado en mm
            const logoHeight = (imgLogo.height / imgLogo.width) * logoWidth; // altura proporcional
            const xPos = 210 - logoWidth - 15; // margen derecho 15mm en A4 (210mm ancho)
            const yPos = 15; // desde arriba
            pdf.addImage(imgLogo, 'PNG', xPos, yPos, logoWidth, logoHeight);

            generarPDF();
        };
        function generarPDF(){
            const nombre = document.querySelector('input[name="nombreAlumno"]').value || "";
            const fecha = document.querySelector('input[name="fechaHoy"]').value || "";
            const aclaracion = document.getElementById("aclaracion").value || "";
            const imgData = canvas.toDataURL("image/png");

            pdf.setFont('helvetica','bold');
            pdf.setFontSize(16);
            pdf.setTextColor(15,76,117);
            pdf.text("Autorizaciones Escuela / Colonia LOA",105,35,{align:"center"});

            pdf.setFont('helvetica','normal');
            pdf.setFontSize(12);
            pdf.setTextColor(0,0,0);
            pdf.text(`Alumno/a: ${nombre}`,20,65);
            pdf.text(`Fecha: ${fecha}`,20,75);

            let y = 90;

            const textos = [
                { titulo:"1. Autorización de uso de imagen", texto:"Mi hijo/a podrá ser fotografiado/a o grabado/a durante su participación en la Escuela/Colonia LOA. Autorizo que estas imágenes se utilicen con fines educativos, informativos y de difusión de las actividades, siempre cuidando su integridad y respeto." },
                { titulo:"2. Autorización asistencia para el cambiado en playa", texto:"Entiendo que durante las actividades en la playa, mi hijo/a necesitará asistencia para cambiarse de ropa. Autorizo y confío en el personal de la Escuela/Colonia LOA para ayudarlo/a de manera respetuosa y segura, cuidando su privacidad en todo momento." },
                { titulo:"3. Autorización participación en actividades deportivas", texto:"Por la presente dejo constancia que me hago responsable y autorizo a mi hijo/a a participar de la Colonia de Playa LOA, dejando sentado que está en buenas condiciones físicas y psíquicas para la práctica del deporte. Entiendo y acepto que mi hijo/a practique todas las actividades deportivas y recreativas del cronograma, siendo que el surf y algunas actividades conllevan riesgos en su práctica." }
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

            // Firma y aclaración
            pdf.setFontSize(12);
            pdf.setFont('helvetica','bold');
            pdf.text("Firma del padre/madre/tutor:",20,y);

            // Insertar firma a la izquierda
            pdf.addImage(imgData,'PNG',20,y+5,100,50);

            // Aclaración a la derecha de la firma
            if(aclaracion){
                pdf.setFont('helvetica','bold');
                pdf.text("Aclaración: " ,130,y,{maxWidth:70});
                pdf.setFont('helvetica','normal');
                pdf.text(   aclaracion,132,y+15,{maxWidth:70});
            }

            pdf.save("autorizaciones_LOA.pdf");
        }

    });
</script>
</body>
</html>
