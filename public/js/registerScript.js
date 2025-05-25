// codigo para que se actualize el texto del input al valor del archivo que subio
const inputFile = document.getElementById('foto-perfil');
const nombreArchivo = document.getElementById('nombre-archivo');

inputFile.addEventListener('change', function () {
    if (this.files && this.files.length > 0) {
        nombreArchivo.textContent = this.files[0].name;
    } else {
        nombreArchivo.textContent = 'Foto de perfil'; // Por si cancela la selección
    }
});