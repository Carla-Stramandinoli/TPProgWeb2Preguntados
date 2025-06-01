const reloj = document.getElementById("reloj");
let tiempo = 10;
setInterval(temporizadorDiezSegundos, 1000);
setInterval(perdistePorTiempo, 1100);
temporizadorDiezSegundos();
perdistePorTiempo();
function perdistePorTiempo(){
        setTimeout(() => {
            window.location.href = `/jugarPartida/timeOut`;
        }, 10500);

}
function temporizadorDiezSegundos(){



    reloj.innerText = `Tiempo: ${tiempo}` ;

    if (tiempo <= 0){
        reloj.innerText = `perdiste papu`;
    }
    tiempo--;
}

