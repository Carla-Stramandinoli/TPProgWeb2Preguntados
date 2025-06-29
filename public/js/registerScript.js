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


// validacion con ajax en correo y nickname

const inputCorreo = document.getElementById('email');
const labelCorreo = document.getElementById('label-email');
const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


let timeout;

inputCorreo.addEventListener('input', () => {
    clearTimeout(timeout);



    timeout = setTimeout(() => {

        inputCorreo.classList.remove('border-success', 'border-danger');
        labelCorreo.classList.remove('text-success', 'text-danger');

        const valor = inputCorreo.value.trim();

        if (valor.length > 0 && regexCorreo.test(valor)) {
            fetch(`/ingreso/validarEmailUnicoAjax?email=${valor}`)
                .then(res => res.json())
                .then(data => {
                    if (data.disponible) {
                        inputCorreo.classList.add('border-success');
                        labelCorreo.classList.add('text-success');
                        labelCorreo.textContent = 'Este correo esta disponible!';
                    } else {
                        inputCorreo.classList.add('border-danger');
                        labelCorreo.classList.add('text-danger');
                        labelCorreo.textContent = 'Este correo ya esta en uso.';
                    }
                });
        } else {
            inputCorreo.classList.add('border-danger');
            labelCorreo.classList.add('text-danger');
            labelCorreo.textContent = 'Ingrese un formato valido.';
        }
    }, 500); // espera medio segundo después del último cambio
});


const inputNickname = document.getElementById('nickname');
const labelNickname = document.getElementById('label-nickname');

inputNickname.addEventListener('input', () => {
    clearTimeout(timeout);

    timeout = setTimeout(() => {

        const valor = inputNickname.value.trim();

        inputNickname.classList.remove('border-success', 'border-danger');
        labelNickname.classList.remove('text-success', 'text-danger');

        if (valor.length > 0) {
            fetch(`/ingreso/validarNicknameUnicoAjax?nickname=${valor}`)
                .then(res => res.json())
                .then(data => {
                    if (data.disponible) {
                        inputNickname.classList.add('border-success');
                        labelNickname.classList.add('text-success');
                        labelNickname.textContent = 'Este nickname esta disponible!';
                    } else {
                        inputNickname.classList.add('border-danger');
                        labelNickname.classList.add('text-danger');
                        labelNickname.textContent = 'Este nickname ya esta en uso.';
                    }
                });
        } else {
            inputNickname.classList.add('border-danger');
            labelNickname.classList.add('text-danger');
            labelNickname.textContent = 'Ingrese un formato valido.';
        }
    }, 500); // espera medio segundo después del último cambio
});

