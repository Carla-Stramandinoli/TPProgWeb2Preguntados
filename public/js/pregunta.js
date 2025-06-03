const reloj = document.getElementById("reloj");

// ✅ Cargamos el tiempo guardado, si existe
let tiempo = parseInt(localStorage.getItem("tiempoRestante")) || 10;

temporizadorDiezSegundos();
//setInterval(perdistePorTiempo, 1100);

const intervaloTemporizador = setInterval(temporizadorDiezSegundos, 1000);

//
// perdistePorTiempo();
// function perdistePorTiempo(){
//     setTimeout(() => {
//         window.location.href = "/jugarPartida/timeOut";
//     }, tiempo * 1000 + 800);
//
// }

function temporizadorDiezSegundos() {
    reloj.innerText = `Tiempo: ${tiempo}`;
    if (tiempo <= 0) {
        reloj.innerText = `Perdiste papu`;

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

