const inputsCodigoPaises = document.querySelectorAll('.codigo-paises');
inputsCodigoPaises.forEach(e => {

    fetch(`https://restcountries.com/v3.1/alpha/${e.value}`)
        .then(res => res.json())
        .then(data => {
            const pais = data[0]
            e.insertAdjacentHTML("afterend", `
                <img class="bandera-pais d-inline" src="${pais.flags.png}" style="max-width: 20px"  alt="Bandera de ${pais.translations.spa.common}">
                <p class="nombre-pais d-inline">${pais.translations.spa.common}</p>
            `);

        })
        .catch(error => console.error("Error al obtener el país:", error));
});

