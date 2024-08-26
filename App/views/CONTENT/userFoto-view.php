<div class="container is-fluid mb-6">
    <h1 class="title is-1 has-text-primary has-text-centered">Mi Foto de Perfil</h1>
    <h2 class="subtitle is-3 has-text-grey has-text-centered">Actualizar Foto de Perfil</h2>
</div>

<div class="container box shadow-lg pb-6 pt-6">
    <?php
        $id = $_SESSION['id'];
        $datos = $insLogin->seleccionarDatos("Unico", "users", "id", $id);

        if ($datos->rowCount() == 1) {
            $datos = $datos->fetch();
    ?>

    <h2 class="title is-4 has-text-centered has-text-weight-bold"><?php echo $datos['nombre'] . " " . $datos['apellido']; ?></h2>
    <div class="columns is-vcentered">
        <div class="column is-two-fifths">
            <figure class="image is-square mb-6">
                <img class="is-rounded" src="<?php echo APP_URL; ?>app/views/fotos/<?php echo is_file("./app/views/fotos/" . $datos['photo']) ? $datos['photo'] : 'default.png'; ?>">
            </figure>

            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off">
                <input type="hidden" name="modulo_usuario" value="eliminarFoto">
                <input type="hidden" name="usuario_id" value="<?php echo $datos['id']; ?>">
                <p class="has-text-centered">
                    <button type="submit" class="button is-danger is-light is-rounded is-fullwidth">Eliminar Foto</button>
                </p>
            </form>
        </div>

        <div class="column">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="modulo_usuario" value="actualizarFoto">
                <input type="hidden" name="usuario_id" value="<?php echo $datos['id']; ?>">

                <div class="field">
                    <label class="label">Foto o Imagen del Usuario</label>
                    <div class="file has-name is-boxed is-centered">
                        <label class="file-label">
                            <input class="file-input" type="file" name="usuario_foto" accept=".jpg, .png, .jpeg">
                            <span class="file-cta">
                                <span class="file-icon">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="file-label">Seleccione una foto</span>
                            </span>
                            <span class="file-name">JPG, JPEG, PNG. (MAX 5MB)</span>
                        </label>
                    </div>
                </div>

                <p class="has-text-centered mt-4">
                    <button type="submit" class="button is-success is-light is-rounded is-fullwidth">Actualizar Foto</button>
                </p>
            </form>
        </div>
    </div>

    <?php
        } else {
            include "./app/views/inc/error_alert.php";
        }
    ?>
</div>
