

<div class="container is-fluid mb-6">
    <h1 class="title">Mi cuenta</h1>
    <h2 class="subtitle">Actualizar cuenta</h2>
</div>
<div class="container pb-6 pt-6">
    <?php
   
    $id=$_SESSION['id'];
    $datos = $insLogin->seleccionarDatos("Unico", "users", "id", $id);

    if ($datos->rowCount() == 1) {
        $datos = $datos->fetch();
        ?>
        <h2 class="title has-text-centered"><?php echo $datos['nombre'] . " " . $datos['apellido']; ?></h2>

        <p class="has-text-centered pb-6">
        </p>
        <form class="FormularioAjax box p-6" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off">

    <input type="hidden" name="modulo_usuario" value="actualizar">
    <input type="hidden" name="usuario_id" value="<?php echo $datos['id']; ?>">

    <h2 class="title is-4 has-text-centered">Actualizar Información de Usuario</h2>

    <div class="columns">
        <div class="column">
            <div class="field">
                <label class="label">Nombres</label>
                <div class="control has-icons-left">
                    <input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" value="<?php echo $datos['nombre']; ?>" required>
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
                    <input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" value="<?php echo $datos['apellido']; ?>" required>
                    <span class="icon is-small is-left">
                        <i class="fas fa-user"></i>
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
                    <input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" value="<?php echo $datos['username']; ?>" required>
                    <span class="icon is-small is-left">
                        <i class="fas fa-user-tag"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Email</label>
                <div class="control has-icons-left">
                    <input class="input" type="email" name="usuario_email" maxlength="70" value="<?php echo $datos['email']; ?>">
                    <span class="icon is-small is-left">
                        <i class="fas fa-envelope"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="notification is-info is-light has-text-centered">
        Si desea actualizar la clave de este usuario, por favor llene los 2 campos. Si NO desea actualizar la clave, deje los campos vacíos.
    </div>

    <div class="columns">
        <div class="column">
            <div class="field">
                <label class="label">Nueva clave</label>
                <div class="control has-icons-left">
                    <input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
                    <span class="icon is-small is-left">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Repetir nueva clave</label>
                <div class="control has-icons-left">
                    <input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
                    <span class="icon is-small is-left">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="notification is-warning is-light has-text-centered">
        Para poder actualizar los datos de este usuario, por favor ingrese su USUARIO y CLAVE con la que ha iniciado sesión.
    </div>

    <div class="columns">
        <div class="column">
            <div class="field">
                <label class="label">Usuario</label>
                <div class="control has-icons-left">
                    <input class="input" type="text" name="administrador_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
                    <span class="icon is-small is-left">
                        <i class="fas fa-user-shield"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Clave</label>
                <div class="control has-icons-left">
                    <input class="input" type="password" name="administrador_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                    <span class="icon is-small is-left">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="field has-text-centered">
        <button type="submit" class="button is-success is-rounded is-medium">Actualizar</button>
    </div>
</form>
        <?php
    } else {
        include "./app/views/inc/error_alert.php";
    }
    ?>


</div>