<?php
namespace App\Controller;
use App\Model\mainModel;
class loginController extends mainModel
{
    /*----------  Controlador iniciar sesion  ----------*/
    public function iniciarSesionControlador()
    {
        $usuario = $this->limpiarCadena($_POST['login_usuario']);// guardamos el usuario 
        $clave = $this->limpiarCadena($_POST['login_clave']);// guardamos la contraseña
        # Verificando campos obligatorios que no esten vacios #
        if ($usuario == "" || $clave == "") {
            echo "<script>
                Swal.fire({
                  icon: 'error',
                  title: 'Ocurrió un error inesperado',
                  text: 'No has llenado todos los campos que son obligatorios'
                });
            </script>";
        } else {

            # Verificando integridad de los datos #
            if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $usuario)) {
                echo "<script>
                    Swal.fire({
                      icon: 'error',
                      title: 'Ocurrió un error inesperado',
                      text: 'El USUARIO no coincide con el formato solicitado'
                    });
                </script>";
            } else {

                # Verificando integridad de los datos #
                if ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave)) {
                    echo "<script>
                        Swal.fire({
                          icon: 'error',
                          title: 'Ocurrió un error inesperado',
                          text: 'La CLAVE no coincide con el formato solicitado [a-zA-Z0-9$@.-'
                        });
                    </script>";
                } else {

                    # Verificando usuario #
                    $check_usuario = $this->ejecutarConsulta("SELECT * FROM users WHERE username='$usuario'");// verificamos si el usuario existe

                    if ($check_usuario->rowCount() == 1) {//si solo es una columna entra

                        $check_usuario = $check_usuario->fetch();// la sobre escribimos para hacer un array  de datos 

                        if ($check_usuario['username'] == $usuario && password_verify($clave, $check_usuario['password'])) {//comprobamos la contraseña
                            $ss="sessions";
                            /*verificamos si la session esta ativa o no  */
                            $session_verify = $this->ejecutarConsulta("SELECT * FROM $ss WHERE user_id = ".$check_usuario['id']." AND is_active = 1");
                            if ($session_verify->rowCount() >0) {
                                
                                    echo "<script>
                                        Swal.fire({
                                          icon: 'error',
                                          title: 'Ya tienes una sesión activa',
                                          text: 'Por favor, cierra la sesión antes de iniciar otra.'
                                        }).then(() => {
                                            // Limpiar los campos del formulario después de mostrar el mensaje
                                            document.querySelector('input[name=\"login_usuario\"]').value = '';
                                            document.querySelector('input[name=\"login_clave\"]').value = '';
                                        });
                                    </script>";
                                    exit; // Salimos de la función para evitar continuar con la ejecución
                                

                            }
                            /* fin de la verficaccion*/
                            // Generar un token de sesión
                            $session_token = bin2hex(random_bytes(32));
                            
                            /*realizamos el inserte de esta session */
                            $session_array=[
                                [ 
                                "campo_nombre"=>"user_id",
                                "campo_marcador"=>":user_id",
                                "campo_valor"=>$check_usuario['id']
                                ],
                                [ 
                                    "campo_nombre"=>"login_time",
                                    "campo_marcador"=>":login_time",
                                    "campo_valor"=>date("Y-m-d H:i:s")
                                ],
                                [ 
                                    "campo_nombre"=>"session_token",
                                    "campo_marcador"=>":session_token",
                                    "campo_valor"=>$session_token
                                ],
                                
                            ];
                            $this->guardarDatos('sessions',$session_array);
                            /* fin de la insert */
                            $session_rol = $this->ejecutarConsulta("SELECT * FROM user_roles WHERE user_id = ".$check_usuario['id']." ");
                            $session_rol=$session_rol->fetch();

                            $_SESSION['id'] = $check_usuario['id'];
                            $_SESSION['cedula'] = $check_usuario['cedula'];
                            $_SESSION['nombre'] = $check_usuario['nombre'];
                            $_SESSION['apellido'] = $check_usuario['apellido'];
                            $_SESSION['usuario'] = $check_usuario['username'];
                            $_SESSION['foto'] = $check_usuario['photo'];
                            $_SESSION['token']=$session_token;
                            $_SESSION['rol']=$session_rol['role_id'];
                        
                            $se = "sessions";
                            //cambiar

                            if (headers_sent()) {//LOS ENCABESADOS
                                echo "<script> window.location.href='" . APP_URL . "dashboard/'; </script>";
                            } else {
                                header("Location: " . APP_URL . "dashboard/");
                            }

                        } else {
                            echo "<script>
                                Swal.fire({
                                  icon: 'error',
                                  title: 'Ocurrió un error inesperado',
                                  text: 'Usuario o clave incorrectos'
                                });
                            </script>";
                        }

                    } else {
                        echo "<script>
                            Swal.fire({
                              icon: 'error',
                              title: 'Ocurrió un error inesperado',
                              text: 'Usuario o clave incorrectos'
                            });
                        </script>";
                    }
                }



            }
        }
    }

    /*----------  Controlador cerrar sesion  ----------*/
    public function cerrarSesionControlador()
    {
        /*
        update
         */
        $check_usuario = $this->ejecutarConsulta("SELECT * FROM sessions WHERE user_id='".$_SESSION['id']."'");// para traer la token
        if($check_usuario->rowCount()>0){
            $check_usuario = $check_usuario->fetch();// la sobre escribimos para hacer un array  de datos 
            $session_token = $_SESSION['token'];
            $user_id = $_SESSION['id'];
            $usuario_datos_up=[
                [
                    "campo_nombre"=>"logout_time",
                    "campo_marcador"=>":logout_time",
                    "campo_valor"=>date("Y-m-d H:i:s")
                ],
                [
                    "campo_nombre"=>"is_active",
                    "campo_marcador"=>":is_active",
                    "campo_valor"=>"0"
                ],
            ];
            $condicion= [
                [
                    "condicion_campo"=>"user_id",
                    "condicion_marcador"=>":user_id",
                    "condicion_valor"=>$user_id
                ],
                [
                    "condicion_campo"=>"session_token",
                    "condicion_marcador"=>":session_token",
                    "condicion_valor"=>$session_token
                ]
            ];
            $this->actualizarsession("sessions",$usuario_datos_up,$condicion);
        }
        
        /*
        end update
         */
        session_destroy();

        if (headers_sent()) {
            echo "<script> window.location.href='" . APP_URL . "login/'; </script>";
        } else {
            header("Location: " . APP_URL . "login/");
        }
    }
}