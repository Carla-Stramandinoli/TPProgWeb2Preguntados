google.charts.load('current', {'packages':['corechart', 'bar', 'geochart']});
google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {
    drawGeneroChart();
    drawEdadChart();
    drawJugadoresChart();
    drawDataChart();
    drawGeoChart();
}

function drawGeneroChart() {
    const numHombres = document.getElementById('numHombres').value;
    const numMujeres = document.getElementById('numMujeres').value;
    const numOtros = document.getElementById('numOtros').value;

    const data = google.visualization.arrayToDataTable([
        ['Género', 'Cantidad'],
        [`Hombres = ${numHombres}`, Number(numHombres)],
        [`Mujeres = ${numMujeres}`, Number(numMujeres)],
        [`Otros = ${numOtros}`, Number(numOtros)],
    ]);

    const options = {
        height: 300,
        is3D: true,
        pieSliceText: 'value',
        pieSliceTextStyle: { fontSize: 18 },
        titleTextStyle: { fontSize: 20 },
        legend: { position: 'right', textStyle: { fontName: 'Arial',
                bold: true,
                fontSize: 15} }
    };

    new google.visualization.PieChart(document.getElementById('chart_genero')).draw(data, options);
}


function drawEdadChart() {
    const numMenores = document.getElementById('numMenores').value;
    const numMayores = document.getElementById('numMayores').value;
    const numJubilados = document.getElementById('numJubilados').value;

    const data = google.visualization.arrayToDataTable([
        ['Edad', 'Cantidad'],
        [`Menores = ${numMenores}`, Number(numMenores)],
        [`Mayores = ${numMayores}`, Number(numMayores)],
        [`Jubilados = ${numJubilados}`, Number(numJubilados)]
    ]);

    const options = {
        height: 300,
        is3D: true,
        pieSliceText: 'value',
        pieSliceTextStyle: { fontSize: 18 },
        titleTextStyle: { fontSize: 20 },
        legend: { position: 'right', textStyle: { fontName: 'Arial',
                bold: true,
                fontSize: 15} }
    };

    new google.visualization.PieChart(document.getElementById('chart_edad')).draw(data, options);
}


function drawJugadoresChart() {
    const jugadoresTotales = document.getElementById('numJugadoresTotales').value;
    const jugadoresNuevos = document.getElementById('jugadoresNuevos').value;

    const data = google.visualization.arrayToDataTable([
        ['Jugadores', 'Cantidad', { role: 'style' }],
        [`Jugadores Totales ${jugadoresTotales}`, Number(jugadoresTotales), '#1b9e77'],
        [`Jugadores Nuevos ${jugadoresNuevos}`, Number(jugadoresNuevos), '#d95f02']
    ]);

    const options = {
        height: 300,
        legend: {
            position: 'none',
            textStyle: { fontName: 'Arial', bold: true, fontSize: 14 }
        },
        titleTextStyle: {
            fontName: 'Arial',
            bold: true,
            fontSize: 20
        },
        bar: { groupWidth: '60%' },
        hAxis: {
            title: 'Cantidad',
            titleTextStyle: { fontName: 'Arial', bold: true, fontSize: 14 },
            textStyle: { fontName: 'Arial', bold: true, fontSize: 12 }
        },
        vAxis: {
            textStyle: { fontName: 'Arial', bold: true, fontSize: 15 }
        }
    };



    const chart = new google.visualization.BarChart(document.getElementById('chart_jugadores'));
    chart.draw(data, options);
}


function drawDataChart() {
    const preguntasTotales = document.getElementById('numPreguntasTotales').value;
    const preguntasCreadas = document.getElementById('numPreguntasCreadas').value;
    const porcentajeAciertos = document.getElementById('numPorcentajeAciertos').value;

    const data = google.visualization.arrayToDataTable([
        ['Preguntas', 'Cantidad', { role: 'style' }],
        [`Preguntas Totales ${preguntasTotales}`, Number(preguntasTotales), '#1b9e77'],
        [`Preguntas Creadas ${preguntasCreadas}`, Number(preguntasCreadas), '#d95f02'],
        [`% Aciertos ${porcentajeAciertos}`, Number(porcentajeAciertos), '#7570b3']
    ]);

    const options = {
        height: 300,
        legend: {
            position: 'none',
            textStyle: { fontName: 'Arial', bold: true, fontSize: 14 }
        },
        titleTextStyle: {
            fontName: 'Arial',
            bold: true,
            fontSize: 20
        },
        bar: { groupWidth: '60%' },
        hAxis: {
            title: 'Cantidad',
            titleTextStyle: { fontName: 'Arial', bold: true, fontSize: 14 },
            textStyle: { fontName: 'Arial', bold: true, fontSize: 12 }
        },
        vAxis: {
            textStyle: { fontName: 'Arial', bold: true, fontSize: 15}
        }
    };

    const chart = new google.visualization.BarChart(document.getElementById('chart_data'));
    chart.draw(data, options);
}

        function mostrarCampoFecha() {
            const filtro = document.getElementById('filtro').value;
            document.querySelectorAll('.filtro-fecha').forEach(div => div.style.display = 'none');

            const leyenda = document.getElementById('leyenda-fecha');
            const leyendaInput = document.getElementById('leyenda_fecha_oculta');
            leyenda.textContent = "";
            leyendaInput.value = "";

            const hoy = new Date();
            if (filtro === 'historico'){
                leyendaInput.value = `Datos Historicos`;
            }
            if (filtro === 'dia') {
                const input = document.getElementById('fecha-dia');
                document.getElementById('filtro-dia').style.display = 'block';

                const partes = input.value.split("-");
                const fecha = new Date(Number(partes[0]), Number(partes[1]) - 1, Number(partes[2]));
                const texto = `Datos del ${fecha.toLocaleDateString('es-AR')}`;
                leyenda.textContent = texto;
                leyendaInput.value = texto;

            } else if (filtro === 'mes') {
                const input = document.getElementById('fecha-mes');
                document.getElementById('filtro-mes').style.display = 'block';

                const valor = input.value || hoy.toISOString().slice(0, 7);
                input.value = valor;

                const [anio, mes] = input.value.split("-");
                const fecha = new Date(Number(anio), Number(mes) - 1, 1);
                const nombreMes = fecha.toLocaleString('es-AR', { month: 'long' });

                const texto = `Datos de ${nombreMes} de ${anio}`;
                leyenda.textContent = texto;
                leyendaInput.value = texto;

            } else if (filtro === 'anio') {
                const input = document.getElementById('fecha-anio');
                document.getElementById('filtro-anio').style.display = 'block';

                const anio = input.value || hoy.getFullYear();
                input.value = anio;

                const texto = `Datos del año ${anio}`;
                leyenda.textContent = texto;
                leyendaInput.value = texto;
            }
        }

        // Se ejecuta al cargar
        document.addEventListener("DOMContentLoaded", mostrarCampoFecha);

        // También actualiza la leyenda si cambiás la fecha manualmente
        document.getElementById('fecha-dia')?.addEventListener('input', mostrarCampoFecha);
        document.getElementById('fecha-mes')?.addEventListener('input', mostrarCampoFecha);
        document.getElementById('fecha-anio')?.addEventListener('input', mostrarCampoFecha);
        document.getElementById('filtro')?.addEventListener('change', mostrarCampoFecha);



        const { jsPDF } = window.jspdf;

document.getElementById('btn-imprimir').addEventListener('click', async function (e) {
    e.preventDefault();
    const contenido = document.getElementById('contenido-imprimible');
    const btn = document.getElementById('btn-imprimir');
    const loader = document.getElementById('pdf-loading');

    btn.style.display = 'none';
    loader.style.display = 'block'; // Mostrar spinner

    await new Promise(resolve => setTimeout(resolve, 50)); // pequeño delay para que el DOM pinte el mensaje

    const canvas = await html2canvas(contenido, { scale: 1 });
    const imgData = canvas.toDataURL('image/jpeg', 0.7);

    btn.style.display = 'inline-block';
    loader.style.display = 'none'; // Ocultar spinner

    const pdf = new jsPDF('p', 'mm', 'a4');
    const imgProps = pdf.getImageProperties(imgData);
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

    pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);

    const pdfBlob = pdf.output('blob');
    const pdfUrl = URL.createObjectURL(pdfBlob);

    window.open(pdfUrl, '_blank');
});
