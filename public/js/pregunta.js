const reloj = document.getElementById("reloj");

// ✅ Cargamos el tiempo guardado, si existe
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

