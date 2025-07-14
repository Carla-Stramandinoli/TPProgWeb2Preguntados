const reloj = document.getElementById("reloj");

// Cargamos el tiempo guardado, si existe
let tiempo = parseInt(localStorage.getItem("tiempoRestante")) || 10;
temporizadorDiezSegundos();

const intervaloTemporizador = setInterval(temporizadorDiezSegundos, 1000);

function temporizadorDiezSegundos() {
    reloj.innerText = `Tiempo: ${tiempo}`;
    if (tiempo <= 0) {
        reloj.innerText = `Perdiste :C`;

        clearInterval(intervaloTemporizador);

        setTimeout(() => {
            window.location.href = "/jugarPartida/timeOut";
        }, 400);
        return;
    }
    tiempo--;
    // Guardamos el nuevo valor en localStorage
    localStorage.setItem("tiempoRestante", tiempo);
}


// contador de tiempo para truco 50-50

document.addEventListener('DOMContentLoaded', () => {
    const segundosIniciales = parseInt(document.getElementById('segundos-truco')?.value);

    if (!isNaN(segundosIniciales) && segundosIniciales > 0) {
        let segundosRestantes = segundosIniciales;
        const contadorElemento = document.getElementById('contador-segundos-truco');
        const contenedorBoton = document.getElementById('btn-truco-desactivado');

        const intervalo = setInterval(() => {
            segundosRestantes--;

            // Mostrar contador
            const minutos = Math.floor(segundosRestantes / 60);
            const segundos = segundosRestantes % 60;
            contadorElemento.textContent = `Disponible en: ${minutos}:${segundos.toString().padStart(2, '0')}`;

            if (segundosRestantes < 1) {
                clearInterval(intervalo);

                // Reemplazar el div por un botón habilitado
                const nuevoBoton = document.createElement('button');
                nuevoBoton.id = 'btn-truco-50-50';
                nuevoBoton.className = 'btn btn-success p-3 rounded-3 text-center text-nowrap fw-semibold d-flex align-items-center justify-content-center fs-3';
                nuevoBoton.style.minHeight = '70px';
                nuevoBoton.innerHTML = 'Usar Truco<br>50-50';


                nuevoBoton.addEventListener('click', () => {

                    nuevoBoton.classList.replace('btn-success','btn-secondary');
                    nuevoBoton.disabled = true;

                    fetch("/jugarPartida/aplicarTruco5050")
                        .then(res => res.json())
                        .then(data => {
                            data.forEach((preguntaIncorrecta) => {
                                document.querySelector(`button[value="${preguntaIncorrecta.id_temporal}"]`).classList.add('d-none');
                            });
                        });
                })

                contenedorBoton.replaceWith(nuevoBoton);

                // Opcional: ocultar el contador
                contadorElemento.remove();
            }
        }, 1000);
    }
});


// Truco 50-50

const botonTruco5050 = document.getElementById("btn-truco-50-50");

botonTruco5050.addEventListener('click', () => {

    botonTruco5050.classList.replace('btn-success','btn-secondary');

    fetch("/jugarPartida/aplicarTruco5050")
        .then(res => res.json())
        .then(data => {
            data.forEach((preguntaIncorrecta) => {
                document.querySelector(`button[value="${preguntaIncorrecta.id_temporal}"]`).classList.add('d-none');
            });
        });
})

