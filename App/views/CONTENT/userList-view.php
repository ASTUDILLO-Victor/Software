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
    
    <?php

    use App\Controller\userController;

    $insUsuario = new userController();
    $users = $insUsuario->lista_usuarios();
    ?>
    <div class="container">
    <h2 class="title is-4 has-text-centered">Lista de Usuarios</h2>
    <table id="miTabla" class="table is-bordered is-striped is-hoverable is-fullwidth">
        <thead class="has-background-info">
            <tr>
                <th class="has-text-white">Cédula</th>
                <th class="has-text-white has-text-centered">Foto</th>
                <th class="has-text-white">Nombre</th>
                <th class="has-text-white">Usuario</th>
                <th class="has-text-white">Correo Electrónico</th>
                <th class="has-text-white">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['cedula']); ?></td>
                    <td class="has-text-centered">
                        <figure class="image is-64x64 is-inline-block">
                            <img class="is-rounded" src="<?php echo APP_URL . 'app/views/fotos/' . htmlspecialchars($user['photo']); ?>" alt="Foto de Usuario" style="object-fit: cover;" />
                        </figure>
                    </td>
                    <td><?php echo htmlspecialchars($user['nombre'])." ".htmlspecialchars($user['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <div class="buttons">
                            <button type="button" class="button is-success is-rounded is-small" onclick="openUpdateModal('<?php echo htmlspecialchars($user['id']); ?>', '<?php echo htmlspecialchars($user['nombre']); ?>', '<?php echo htmlspecialchars($user['apellido']); ?>', '<?php echo htmlspecialchars($user['username']); ?>', '<?php echo htmlspecialchars($user['email']); ?>')">Actualizar</button>
                            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" style="display:inline;">
                                <input type="hidden" name="modulo_usuario" value="eliminar">
                                <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                <button type="submit" class="button is-danger is-rounded is-small">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


</div>
<?php require_once "./App/views/inc/modal/formulario_update.php"; ?>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#miTabla').DataTable();
    });
</script>