function inicializarMapaPerfil(){

    let latitud = document.getElementById('latitud').value;
    let longitud = document.getElementById('longitud').value;

    // le digo que renderize el mapa en el div con id mapa y que lo haga en las cordenadas indicadas y un zoom predeterminado
    const mapa = L.map("mapa",{
        dragging: false,        // desactiva arrastrar
        touchZoom: false,       // desactiva zoom con dos dedos
        scrollWheelZoom: false, // desactiva zoom con la rueda del mouse
        doubleClickZoom: false, // desactiva zoom con doble clic
        boxZoom: false,         // desactiva zoom con selección de área
        keyboard: false         // desactiva navegación con el teclado
    }).setView([latitud, longitud], 15);

    // seteo de que pagina se va a ir cargando el mapa
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; Preguntatres',
    }).addTo(mapa); // se lo agrego al mapa creado

    // Marcador personalizado
    const iconoPersonalizado = L.icon({
        iconUrl: '/public/images/icons/logo-mapa.png',
        iconSize: [55, 65],
        iconAnchor: [25, 50],
    });

    // agrego marcador al mapa para evaluar las coordenadas en la que se encuentra
    const marcadorDeUbicacion = L.marker([latitud, longitud], {
        icon: iconoPersonalizado,
        interactive: false
    }).addTo(mapa);// agrego el marcador al mapa


    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitud}&lon=${longitud}&format=json`)
        .then(res => res.json())
        .then(data => {
            console.log(data)
            if (data.error != null) document.getElementById("direccion").innerText = `No se encontraron resultados`;
            if (data.address != null) {
                const direccion = data.address;
                const ciudad = direccion.city || direccion.town || direccion.village || direccion.hamlet || direccion.municipality || direccion.state || "Ciudad desconocida";
                const pais = direccion.country || "País desconocido";

                document.getElementById("pais").innerText = pais;
                document.getElementById("ciudad").innerText = ciudad;
            }
        })
        .catch(error => console.error(error));
}

inicializarMapaPerfil();