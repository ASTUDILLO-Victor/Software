<!-- modal -->
<div id="updateModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head has-background-primary">
            <p class="modal-card-title has-text-white">Actualizar Usuario</p>
            <button class="delete is-large has-background-white" aria-label="close" onclick="closeUpdateModal()"></button>
        </header>
        <form id="updateFormModal" class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off">
            <input type="hidden" name="modulo_usuario" value="actualizar1">
            <section class="modal-card-body">
                <input type="hidden" name="id" id="userId">
                <div class="field">
                    <label class="label">Nombre</label>
                    <div class="control has-icons-left">
                        <input class="input is-rounded" type="text" name="nombre" id="userNombre" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Apellido</label>
                    <div class="control has-icons-left">
                        <input class="input is-rounded" type="text" name="apellido" id="userApellido" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-user-tag"></i>
                        </span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Usuario</label>
                    <div class="control has-icons-left">
                        <input class="input is-rounded" type="text" name="username" id="userUsername" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-user-circle"></i>
                        </span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control has-icons-left">
                        <input class="input is-rounded" type="email" name="email" id="userEmail" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button type="submit" class="button is-success is-rounded">Guardar cambios</button>
                <button type="button" class="button is-light is-rounded" onclick="closeUpdateModal()">Cancelar</button>
            </footer>
        </form>
    </div>
</div>