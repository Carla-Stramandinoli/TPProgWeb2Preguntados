const categorias = [
    "Historia", "Ciencia", "Geografía",
    "Deportes", "Entretenimiento", "Arte"
];

localStorage.setItem("tiempoRestante", 10);
localStorage.removeItem("redirigiendo");


const colores = ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"];
const canvas = document.getElementById("ruleta");
const ctx = canvas.getContext("2d");
const radius = canvas.width / 2;

let ruletaGirando = false;

function dibujarRuleta(angulo = 0) {
    ctx.clearRect(0, 0, canvas.width, canvas.height); // limpiar canvas

    const angle = 2 * Math.PI / categorias.length;

    for (let i = 0; i < categorias.length; i++) {
        const start = i * angle + angulo;
        const end = (i + 1) * angle + angulo;

        // Fondo
        ctx.beginPath();
        ctx.moveTo(radius, radius);
        ctx.arc(radius, radius, radius, start, end);
        ctx.fillStyle = colores[i % colores.length];
        ctx.fill();
        ctx.stroke();

        // Texto
        ctx.save();
        ctx.translate(radius, radius);
        ctx.rotate(start + angle / 2);
        ctx.textAlign = "right";
        ctx.fillStyle = "#fff";
        ctx.font = "20px Arial";
        ctx.fillText(categorias[i], radius - 10, 5);
        ctx.restore();
    }
}

dibujarRuleta(0);

function redirigir() {

    setTimeout(() => {
        window.location.href = `/jugarPartida/jugar`;
    }, 100); // 500 milisegundos = 0.5 segundos
}

let anguloActual = 0;

function girar() {
    const vueltas = 6;
    const anguloPorCategoria = 360 / categorias.length;
    const seccionElegida = categorias.indexOf(categoriaElegidaDesdeBack);

    // Centro del sector elegido (en grados)
    const centroCategoria = (seccionElegida + 0.5) * anguloPorCategoria;

    // Queremos que el centro de la categoría quede en 270° (la flecha)
    let anguloObjetivo = 270 - centroCategoria;

    // Asegurar que esté en rango [0, 360)
    if (anguloObjetivo < 0) anguloObjetivo += 360;

    const total = (360 * vueltas) + anguloObjetivo;
    const duracion = 1200;
    const inicio = performance.now();

    function animarRuleta(timestamp) {
        const tiempoPasado = timestamp - inicio;
        const progreso = Math.min(tiempoPasado / duracion, 1);

        const giro = total * easeOutCubic(progreso);
        anguloActual = giro % 360;

        const radianes = (anguloActual * Math.PI) / 180;
        dibujarRuleta(radianes);

        if (progreso < 1) {
            requestAnimationFrame(animarRuleta);
        } else {
            const categoriaGanadora = categorias[seccionElegida];
            document.getElementById("resultado").innerText = `¡Salió: ${categoriaGanadora}!`;
            redirigir();
            ruletaGirando = false;
        }
    }

    requestAnimationFrame(animarRuleta);

}

const botonGirar = document.getElementById("boton-girar");



botonGirar.addEventListener("click", () => {
    if (ruletaGirando) return;

    ruletaGirando = true;
    botonGirar.innerText = "Parar";
    girar();
});



// Easing para animación más natural
function easeOutCubic(t) {
    return 1 - Math.pow(1 - t, 3);
}