setTimeout(function () {
    const mensajes = document.getElementsByClassName('msj');
    for (let i = 0; i < mensajes.length; i++) {
        mensajes[i].style.display = 'none';
    }
}, 2000);