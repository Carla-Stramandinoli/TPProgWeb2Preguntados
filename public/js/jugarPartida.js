const categorias = [
    "Historia", "Ciencia", "Geografía",
    "Deportes", "Entretenimiento", "Arte"
];


const colores = ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"];
const canvas = document.getElementById("ruleta");
const ctx = canvas.getContext("2d");
const radius = canvas.width / 2;

function dibujarRuleta() {
    const angle = 2 * Math.PI / categorias.length;

    for (let i = 0; i < categorias.length; i++) {
        const start = i * angle;
        const end = (i + 1) * angle;

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
        ctx.font = "14px Arial";
        ctx.fillText(categorias[i], radius - 10, 5);
        ctx.restore();
    }
}

dibujarRuleta();

function enviarCategoria(categoria) {

    setTimeout(() => {
        window.location.href = `/jugarPartida/categoria?cat=${encodeURIComponent(categoria)}`;
    }, 500); // 500 milisegundos = 0.5 segundos
}

let anguloActual = 0;

function girar() {

    const vueltas = Math.floor(Math.random() * 3) + 6; // entre 3 y 5 vueltas
    const anguloPorCategoria = 360 / categorias.length;
    const seccionElegida = Math.floor(Math.random() * categorias.length);
    const anguloFinal = seccionElegida * anguloPorCategoria;

    const total = (360 * vueltas) + anguloFinal;

    const duracion = 2500; // duración en ms

    const inicio = performance.now();

    function animarRuleta(timestamp) {
        const tiempoPasado = timestamp - inicio;
        const progreso = Math.min(tiempoPasado / duracion, 1);

        const giro = total * easeOutCubic(progreso);
        anguloActual = giro % 360;

        canvas.style.transform = `rotate(${anguloActual}deg)`;

        if (progreso < 1) {
            requestAnimationFrame(animarRuleta);
        } else {
            const anguloFlecha = 270; // posición visual de la flecha
            const gradosBajoLaFlecha = (anguloFlecha - anguloActual + 360) % 360;
            const index = Math.floor(gradosBajoLaFlecha / anguloPorCategoria);

            const categoriaGanadora = categorias[index];

            document.getElementById("resultado").innerText = `¡Salió: ${categoriaGanadora}!`;
            enviarCategoria(categoriaGanadora);
        }
    }

    requestAnimationFrame(animarRuleta);

}


// Easing para animación más natural
function easeOutCubic(t) {
    return 1 - Math.pow(1 - t, 3);
}