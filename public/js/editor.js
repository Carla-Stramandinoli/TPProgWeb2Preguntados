//funcion para que se cierren los collapse de bootstrap cuando abrir otro

document.addEventListener('DOMContentLoaded', function() {
    // Obtener todos los botones de collapse
    const collapseButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');

    collapseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-bs-target');

            // Cerrar todos los otros collapse
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