<?PHP

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>


<div class="container is-fluid mb-6">
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle">Lista de usuario</h2>
</div>
<div class="container pb-6 pt-6">
    <section class="section">
        <div class="container">
            <h1 class="title">Filtrar Tabla de Usuarios</h1>

            <!-- Filtros -->
            <div class="columns is-multiline">
                <!-- Filtro por Nombre -->
                <div class="column is-4">
                    <div class="field">
                        <label class="label">Filtrar por Nombre</label>
                        <div class="control has-icons-left">
                            <input class="input" type="text" id="nombreFiltro" placeholder="Nombre">
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Filtro por Usuario -->
                <div class="column is-4">
                    <div class="field">
                        <label class="label">Filtrar por Usuario</label>
                        <div class="control has-icons-left">
                            <input class="input" type="text" id="usuarioFiltro" placeholder="Usuario">
                            <span class="icon is-small is-left">
                                <i class="fas fa-user-tag"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Filtro por Email -->
                <div class="column is-4">
                    <div class="field">
                        <label class="label">Filtrar por Email</label>
                        <div class="control has-icons-left">
                            <input class="input" type="text" id="emailFiltro" placeholder="Email">
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Botón de Filtrado -->
                <div class="column is-12">
                    <div class="field">
                        <div class="control">
                            <button class="button is-primary is-fullwidth" onclick="filtrarTabla()">
                                <span class="icon">
                                    <i class="fas fa-filter"></i>
                                </span>
                                <span>Filtrar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <br>
    <br>
    <?php
    use App\Controller\userController;
    $insUsuario = new userController();
    echo $insUsuario->listarUsuarioControlador($url[1], 5, $url[0], "");
    ?>
</div> 
<?php  require_once "./App/views/inc/modal/formulario_update.php";?>
 <!-- fin modal -->
<script>
    function filtrarTabla() {
        const nombreFiltro = document.getElementById('nombreFiltro').value.toLowerCase();
        const usuarioFiltro = document.getElementById('usuarioFiltro').value.toLowerCase();
        const emailFiltro = document.getElementById('emailFiltro').value.toLowerCase();

        const tabla = document.getElementById('tablaDatos');
        const filas = tabla.getElementsByTagName('tr');

        for (let i = 0; i < filas.length; i++) {
            const celdas = filas[i].getElementsByTagName('td');
            const nombre = celdas[2].textContent.toLowerCase(); // Columna 1: Nombre
            const usuario = celdas[3].textContent.toLowerCase(); // Columna 2: Usuario
            const email = celdas[4].textContent.toLowerCase(); // Columna 3: Email

            let mostrarFila = true;

            if (nombreFiltro && !nombre.includes(nombreFiltro)) {
                mostrarFila = false;
            }

            if (usuarioFiltro && !usuario.includes(usuarioFiltro)) {
                mostrarFila = false;
            }

            if (emailFiltro && !email.includes(emailFiltro)) {
                mostrarFila = false;
            }

            filas[i].style.display = mostrarFila ? '' : 'none';
        }
    }
</script>