<div class="container is-fluid">
    <h1 class="title">Home</h1>
    <div class="columns is-flex is-justify-content-center">
        <?php
        if (is_file("./app/views/fotos/" . $_SESSION['foto'])) {//SI AL ARCHIVO EXISTE
            echo '<img class="is-rounded" src="' . APP_URL . 'app/views/fotos/' . $_SESSION['foto'] . '">';//MODSTRAMO LA FOTO 
        } else {
            echo '<img class="is-rounded" src="' . APP_URL . 'app/views/fotos/default.png">';//MOSTRAMOS LA IMAGEN POR DEFECTO
        }
        ?>
    </div>
    <div class="columns is-flex is-justify-content-center">
        <h2 class="subtitle">¡Bienvenido <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido']; ?>!</h2>
    </div>
    <div class="columns is-flex is-justify-content-center">
        <h2 class="subtitle">¡Bienvenido <?php echo $_SESSION['rol'] . " "?>!</h2>
    </div>
    <section class="section">
        <div class="container">
            <div class="columns is-centered is-variable is-3">
                <div class="column is-half">
                    <?php
                    use App\Model\mainModel;
                    $check = new mainModel();
                    // Supongamos que quieres obtener los datos del usuario con ID 5
                    // $tipo = "diferente";
                    // $tabla1 = "users";
                    // $tabla2 = "sessions";
                    // $campo = null; // Este campo no es necesario para 'diferente', así que lo dejamos null
                    // $id = $_SESSION['id'];
                    // $session=$_SESSION['token'];
                    
                    // $datos = $e->seleccionarDatos1($tipo, $tabla1, $tabla2, $campo, $id,$session);
                    // if ($datos->rowCount() == 1) {
                    //     $datos = $datos->fetch();
                    //     echo '<div class="notification is-info">';
                    //     echo '<h2 class="title">Hora de inicio de sesión:</h2>';
                    //     echo '<p class="subtitle">' . htmlspecialchars($datos['login_time']) . '</p>';
                    //     echo '</div>';
                    // }
                    // Ejemplo de consulta con parámetros
                    $query = "
                                SELECT users.*, sessions.*
                                FROM sessions
                                JOIN users ON sessions.user_id = users.id
                                WHERE users.id = :user_id AND sessions.session_token = :session_token
                                ";
                    // Parámetros para la consulta
                    $params = [
                        ':user_id' => $_SESSION['id'],
                        ':session_token' => $_SESSION['token']
                    ];
                    // Ejecutar la consulta
                    $stmt = $check->ejecutarConsulta1($query, $params);

                    // Verificar el número de filas devueltas
                    if ($stmt->rowCount() == 1) {
                        // La sesión está activa y coincide con el usuario y el token
                        $result = $stmt->fetch();
                        // Manejar los resultados
                        echo '<div class="notification is-info">';
                        echo '<h2 class="title">Hora de inicio de sesión:</h2>';
                        echo '<p class="subtitle">' . htmlspecialchars($result['login_time']) . '</p>';
                        echo '</div>';

                    } else {
                        // La sesión no está activa o no coincide con el usuario y el token
                    }


                    // ?>
                </div>

                <div class="column is-half">
                    <?php
                    $check = new mainModel();
                    $query = "
                                SELECT users.*, sessions.*
                                FROM sessions
                                JOIN users ON sessions.user_id = users.id
                                WHERE users.id = :user_id
                                ORDER BY sessions.logout_time DESC
                                LIMIT 1
                            ";
                    $params = 
                            [
                                ':user_id' => $_SESSION['id'],
                            ];
                    // Ejecutar la consulta
                    $stmt = $check->ejecutarConsulta1($query, $params);

                    // Verificar el número de filas devueltas
                    if ($stmt->rowCount() == 1) {
                        // La sesión está activa y coincide con el usuario y el token
                        $datos = $stmt->fetch();
                        // Manejar los resultados
                        echo '<div class="notification is-warning">';
                        echo '<h2 class="title">Hora del ultimo cierre de sesión::</h2>';
                        echo '<p class="subtitle">' . htmlspecialchars($datos['logout_time']) . '</p>';
                        echo '</div>';

                    } else {
                        // La sesión no está activa o no coincide con el usuario y el token
                    }
                    ?>


                </div>
            </div>
        </div>
    </section>