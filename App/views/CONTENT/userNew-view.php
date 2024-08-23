<div class="container is-fluid mb-6">
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle">Nuevo usuario</h2>
</div>

<div class="container pb-6 pt-6">

    <form class="FormularioAjax box" id="registrationForm" action="<?php echo APP_URL; ?>App/ajax/usuarioAjax.php"
        method="POST" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="modulo_usuario" value="registrar">

        <div class="columns">
            <div class="column">
                <div class="field">
                    <label class="label">Cédula</label>
                    <div class="control has-icons-left">
                        <input onkeypress="return SoloNumeros(event);" class="input" type="text" id="usuario_cedula"
                            name="usuario_cedula" pattern="[0-9]{10,10}" minlength="10" maxlength="10"
                            placeholder="Ingrese su cédula" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-id-card"></i>
                        </span>
                    </div>
                    <p class="help is-danger is-hidden" id="cedulaError">Cédula inválida</p>
                    <div id="cedula-error" class="help is-danger"></div>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Nombres</label>
                    <div class="control has-icons-left">
                        <input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}"
                            maxlength="40" placeholder="Ingrese sus nombres" oninput="validarNombre();"
                            onkeypress="return SoloLetras(event);" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Apellidos</label>
                    <div class="control has-icons-left">
                        <input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}"
                            maxlength="40" placeholder="Ingrese sus apellidos" oninput="validarApellido();"
                            onkeypress="return SoloLetras(event);" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-user-tag"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <div class="field">
                    <label class="label">Usuario</label>
                    <div class="control has-icons-left">
                        <input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}"
                            maxlength="20" placeholder="Ingrese su usuario" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-user-circle"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control has-icons-left">
                        <input class="input" type="email" name="usuario_email" maxlength="70"
                            oninput="validarCorreo(this);" placeholder="Ingrese su email">
                        <span class="icon is-small is-left">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <div class="field">
                    <label class="label">Clave</label>
                    <div class="control has-icons-left">
                        <input class="input" type="password" id="usuario_clave_1" name="usuario_clave_1" maxlength="18"
                            placeholder="Ingrese su clave" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <!-- Contenedor para los requisitos -->
                    <div id="password-requirements" class="help is-info" style="display: none;">
                        La contraseña debe contener:
                        <ul>
                            <li>Al menos 7 caracteres y máximo 18.</li>
                            <li>Al menos una letra mayúscula.</li>
                            <li>Al menos una letra minúscula.</li>
                            <li>Al menos un número.</li>
                            <li>Al menos un símbolo (@$!%*?&).</li>
                        </ul>
                    </div>
                    <p class="help is-danger is-hidden" id="claveError1">La clave no cumple los requisitos.</p>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Repetir clave</label>
                    <div class="control has-icons-left">
                        <input class="input" type="password" id="usuario_clave_2" name="usuario_clave_2" maxlength="18"
                            placeholder="Repita su clave" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <p class="help is-danger is-hidden" id="claveError2">Las claves no coinciden.</p>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <div class="field">
                    <div class="file has-name is-boxed">
                        <label class="file-label">
                            <input class="file-input" type="file" name="usuario_foto" accept=".jpg, .png, .jpeg">
                            <span class="file-cta">
                                <span class="file-label">Seleccione una foto</span>
                            </span>
                            <span class="file-name">JPG, JPEG, PNG. (MAX 5MB)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="field is-grouped is-grouped-centered">
            <div class="control">
                <button type="reset" class="button is-link is-light is-rounded">
                    <i class="fas fa-eraser"></i>&nbsp;Limpiar
                </button>
            </div>
            <div class="control">
                <button type="submit" class="button is-info is-rounded">
                    <i class="fas fa-save"></i>&nbsp;Guardar
                </button>
            </div>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('usuario_clave_1');
    const requirements = document.getElementById('password-requirements');

    // Mostrar los requisitos al hacer clic en el input
    passwordInput.addEventListener('focus', function() {
        requirements.style.display = 'block';
    });

    // Ocultar los requisitos cuando se pierde el enfoque
    passwordInput.addEventListener('blur', function() {
        requirements.style.display = 'none';
    });
});

    document.getElementById('usuario_clave_1').addEventListener('input', function () {
        const clave = this.value;
        const claveError1 = document.getElementById('claveError1');
        const claveRegEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,18}$/;

        if (clave === '') {
            // Ocultar el mensaje de error si el campo está vacío
            claveError1.classList.add('is-hidden');
        } else if (claveRegEx.test(clave)) {
            // Ocultar el mensaje de error si la contraseña es válida
            claveError1.classList.add('is-hidden');
        } else {
            // Mostrar el mensaje de error si la contraseña no es válida
            claveError1.classList.remove('is-hidden');
        }
    });

    document.getElementById('usuario_clave_2').addEventListener('input', function () {
        const clave1 = document.getElementById('usuario_clave_1').value;
        const clave2 = this.value;
        const claveError2 = document.getElementById('claveError2');

        if (clave1 === clave2) {
            claveError2.classList.add('is-hidden');
        } else {
            claveError2.classList.remove('is-hidden');
        }
    });

    document.getElementById('registrationForm').addEventListener('submit', function (event) {
        if (!document.getElementById('cedulaError').classList.contains('is-hidden') ||
            !document.getElementById('cedulaExistenteError').classList.contains('is-hidden') ||
            !document.getElementById('claveError1').classList.contains('is-hidden') ||
            !document.getElementById('claveError2').classList.contains('is-hidden')) {

            event.preventDefault();
            alert('Corrige los errores antes de enviar el formulario.');
        }
    });
</script>