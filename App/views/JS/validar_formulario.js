function SoloNumeros(e){
    var tecla = (document.all) ? e.keyCode : e.which;// 2
    if (tecla==8) return true; // 3
    var patron =/[0-9]/; // 4
    var te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}

function SoloLetras(e){
    var tecla = (document.all) ? e.keyCode : e.which;// 2
    if (tecla==8) return true; // 3
    var patron =/[A-Za-z\s]/; // 4
    var te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}

function validarNombre() {
    var nombre = document.getElementById('Enom').value;
    var elemento = document.getElementById('nombre-salida');
    if (nombre.length < 3) {
        elemento.innerHTML = 'El nombre debe tener al menos 3 caracteres';
    } else {
        elemento.innerHTML = '';
    }
    validarFormulario();
}

function validarApellido() {
    var apellido = document.getElementById('Eape').value;
    var elemento = document.getElementById('apellido-salida');
    if (apellido.length < 3) {
        elemento.innerHTML = 'El apellido debe tener al menos 3 caracteres';
    } else {
        elemento.innerHTML = '';
    }
    validarFormulario();
}

function validarCorreo(field) {
    const email = field.value;
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!regex.test(email)) {
        document.getElementById("mensaje-email").innerHTML = "Correo electrónico no válido";
        field.setCustomValidity("Correo electrónico no válido");
    } else {
        document.getElementById("mensaje-email").innerHTML = "";
        field.setCustomValidity("");
    }
    validarFormulario();
}