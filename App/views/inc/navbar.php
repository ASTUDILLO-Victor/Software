<nav class="navbar">
<div class="navbar-brand">
    <a class="navbar-item" href="<?php echo APP_URL;?>
                                 use App\Controller\loginController;dashboard">
        <img src="<?php echo APP_URL;?>App/views/IMG/bulma.png" alt="Bulma" width="112" height="28">
    </a>
    <div class="navbar-burger" data-target="navbarExampleTransparentExample">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<div id="navbarExampleTransparentExample" class="navbar-menu">

    <div class="navbar-start">
        <a class="navbar-item" href="<?php echo APP_URL;?>dashboard"">
            Dashboard
        </a>

        <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link" href="#">
                Usuarios
            </a>
            <div class="navbar-dropdown is-boxed">

                <a class="navbar-item" href="<?php echo APP_URL;?>userNew/">
                    Nuevo
                </a>
                <a class="navbar-item" href="<?php echo APP_URL;?>userList/">
                    Lista
                </a>
                <a class="navbar-item" href="<?php echo APP_URL;?>userSearch/">
                    Buscar
                </a>

            </div>
        </div>
        <!-- administracion -->
        <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link" href="#">
                Administracion
            </a>
            <div class="navbar-dropdown is-boxed">

                <a class="navbar-item" href="">
                    Vistas
                </a>
                <a class="navbar-item" href="">
                    Rol
                </a>
                <a class="navbar-item" href="">
                    Permisos
                </a>

            </div>
        </div>
         <!-- fin -->
    </div>

    <div class="navbar-end">
    <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
            <?php echo htmlspecialchars($_SESSION['usuario']); ?>
        </a>
        <div class="navbar-dropdown is-boxed">

            <!-- Formulario para "Mi cuenta" -->
            <form action="<?php echo APP_URL; ?>userUpdate/" method="POST" style="display: inline;">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['id']); ?>">
                <button type="submit" class="navbar-item">Mi cuenta</button>
            </form>

            <!-- Formulario para "Mi foto" -->
            <form action="<?php echo APP_URL; ?>userFoto/" method="POST" style="display: inline;">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['id']); ?>">
                <button type="submit" class="navbar-item">Mi foto</button>
            </form>

            <hr class="navbar-divider">

            <!-- Enlace para "Salir" -->
            <a class="navbar-item" href="<?php echo APP_URL; ?>logOut" id="btn_exit">
                Salir
            </a>

        </div>
    </div>
</div>

</div>
</nav>