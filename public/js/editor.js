//funcion para que se cierren los collapse de bootstrap cuando se abre otro

document.addEventListener('DOMContentLoaded', function () {
    const collapseButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');

    collapseButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-bs-target');

            collapseButtons.forEach(otherButton => {
                const otherTargetId = otherButton.getAttribute('data-bs-target');
                if (otherTargetId !== targetId) {
                    const otherCollapse = document.querySelector(otherTargetId);
                    if (otherCollapse && otherCollapse.classList.contains('show')) {
                        const bsCollapse = new bootstrap.Collapse(otherCollapse, {
                            toggle: false
                        });
                        bsCollapse.hide();
                    }
                }
            });
        });
    });
});

// funcion para que sacar los msj de exito/error de la pantalla
setTimeout(function () {
    const mensajes = document.getElementsByClassName('msj');
    for (let i = 0; i < mensajes.length; i++) {
        mensajes[i].style.display = 'none';
    }
}, 2000);
