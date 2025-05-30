const reloj = document.getElementById("reloj");
let tiempo = 10;
setInterval(temporizadorDiezSegundos, 1000);
temporizadorDiezSegundos();
function temporizadorDiezSegundos(){



    reloj.innerText = `Tiempo: ${tiempo}` ;

    if (tiempo <= 0){
        reloj.innerText = `perdiste papu`;
    }
    tiempo--;
}

