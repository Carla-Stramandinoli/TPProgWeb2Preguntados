function inicializarMapa(){

    // coordenadas de la UNLAM
    let latitudInicial = -34.67064;
    let longitudInicial = -58.562598;

    // le digo que renderize el mapa en el div con id mapa y que lo haga en las cordenadas indicadas y un zoom predeterminado
    const mapa = L.map("mapa").setView([latitudInicial, longitudInicial], 17);


    // seteo de que pagina se va a ir cargando el mapa
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; Preguntatres',
        noWrap: true
    }).addTo(mapa); // se lo agrego al mapa creado

    // Marcador personalizado
    const iconoPersonalizado = L.icon({
        iconUrl: '/public/images/icons/logo-mapa.png',
        iconSize: [55, 65],
        iconAnchor: [25, 50],
    });


    // agrego marcador al mapa para evaluar las coordenadas en la que se encuentra
    const marcadorDeUbicacion = L.marker([latitudInicial, longitudInicial], {
        icon: iconoPersonalizado,
        interactive: false
    }).addTo(mapa);// agrego el marcador al mapa


    // .on es como el .addEventListener de Js

    mapa.on('move', function () { // cuando se mueve el mapa, hace que el marcador se quede en el centro con la latitud y longitud correspondientes
        marcadorDeUbicacion.setLatLng(mapa.getCenter());
    });

    mapa.on('moveend', function () { // cuando se termina de mover, le preguntamos al openstreatmap a que pais y ciudad pertenece la latitud y longitud
        const centro = mapa.getCenter();
        const lat = centro.lat;
        const lng = centro.lng;

        console.log(`Centro: ${lat}, ${lng}`);

        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
            .then(res => res.json())
            .then(data => {
                console.log(data)
                if (data.error != null) document.getElementById("direccion").innerText = `No se encontraron resultados`;
                if (data.address != null) {
                    const direccion = data.address;
                    const ciudad = direccion.city || direccion.town || direccion.village || direccion.hamlet || direccion.municipality || direccion.state || "Ciudad desconocida";
                    const pais = direccion.country || "País desconocido";

                    document.getElementById("direccion").innerText = `Pais: ${pais}, Ciudad: ${ciudad}`;
                    document.getElementById("pais").value = pais;
                    document.getElementById("latitud").value = lat;
                    document.getElementById("longitud").value = lng;
                }
            })
            .catch(error => console.error(error));
    });
}

inicializarMapa();