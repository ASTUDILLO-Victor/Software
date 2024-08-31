<?php
namespace App\Controller;
use App\Model\mainModel;
class userController extends mainModel{

    /*----------  Controlador registrar usuario  ----------*/
    public function registrarUsuarioControlador(){
        $cedula = strtoupper($this->limpiarCadena($_POST['usuario_cedula']));
        $nombre = strtoupper($this->limpiarCadena($_POST['usuario_nombre']));
        $apellido=strtoupper($this->limpiarCadena($_POST['usuario_apellido']));
        $usuario=$this->limpiarCadena($_POST['usuario_usuario']);
        $email=$this->limpiarCadena($_POST['usuario_email']);
        $clave1=$this->limpiarCadena($_POST['usuario_clave_1']);
        $clave2=$this->limpiarCadena($_POST['usuario_clave_2']);
        # Verificando campos obligatorios #
        if($cedula=="" or $nombre=="" || $apellido=="" || $usuario=="" || $clave1=="" || $clave2==""){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No has llenado todos los campos que son obligatorios",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }
        #verificando integridad de cedula #
        if($this->verificarDatos("[0-9]{10,10}",$cedula)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El cedula no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }
        # Verificando integridad de los datos #
        if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El NOMBRE no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }
        if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El APELLIDO no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }
        if($this->verificarDatos("[a-zA-Z0-9!@#$&%*()\\-.+,\/]{4,20}",$usuario)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El USUARIO no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }
        if($this->verificarDatos("[a-zA-Z0-9!@#$&%*()\\-.+,\/]{7,100}",$clave1) || $this->verificarDatos("[a-zA-Z0-9!@#$&%*()\\-.+,\/]{7,100}",$clave2)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"Las CLAVES no coinciden con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }
        # Verificando email #
        if($email!=""){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){//valida los correos electronicos 
                $check_email=$this->ejecutarConsulta("SELECT email FROM users WHERE email='$email'");
                if($check_email->rowCount()>0){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Ha ingresado un correo electrónico no valido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }
        # Verificando claves #
        if($clave1!=$clave2){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"Las contraseñas que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }else{
            $clave=password_hash($clave1,PASSWORD_BCRYPT,["cost"=>10]);
        }
        # Verificando usuario que no se repita en la base de datos #
        $check_usuario=$this->ejecutarConsulta("SELECT username FROM users WHERE username='$usuario'");
        if($check_usuario->rowCount()>0){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El USUARIO ingresado ya se encuentra registrado, por favor elija otro",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }
        $check_cedula=$this->ejecutarConsulta("SELECT cedula FROM users WHERE cedula='$cedula'");
        if($check_cedula->rowCount()>0){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"La cedula que ingresado ya se encuentra registrado, por favor elija otro",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Directorio de imagenes #
        $img_dir="../views/Fotos/";

        # Comprobar si se selecciono una imagen #
        //si el nombre del archivo es distinto a vacio y y el tamaño es mayor a 0
        if($_FILES['usuario_foto']['name']!="" && $_FILES['usuario_foto']['size']>0){

            # Creando directorio #
            if(!file_exists($img_dir)){//si no exite el directorio 
                if(!mkdir($img_dir,0777)){//crear la carpeta 
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"Error al crear el directorio",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                } 
            }

            # Verificando formato de imagenes SOLO ARCHIVOS JPG Y PNG#
            // SIRVE PARA VER QUE CONTENIDO TIENE 
            if(mime_content_type($_FILES['usuario_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['usuario_foto']['tmp_name'])!="image/png"){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La imagen que ha seleccionado es de un formato no permitido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Verificando peso de imagen #
            //VERIFICAR EL PESO
            if(($_FILES['usuario_foto']['size']/1024)>5120){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La imagen que ha seleccionado supera el peso permitido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Nombre de la foto #
            $foto=str_ireplace(" ","_",$nombre);//BUSCA UN ESPACIO EN BLACO Y PONE UN - Y EL NOMBRE
            $foto=$foto."_".rand(0,100);//NUMEROS ALEATOREOS 

            # Extension de la imagen #
            switch(mime_content_type($_FILES['usuario_foto']['tmp_name'])){//QUE TIPO DE IMAGEN ES PARA PONER EL TIPO
                case 'image/jpeg':
                    $foto=$foto.".jpg";
                break;
                case 'image/png':
                    $foto=$foto.".png";
                break;
            }

            chmod($img_dir,0777);//PERMISOS DE LECTURA Y ESCRITURA 

            # Moviendo imagen al directorio #
            if(!move_uploaded_file($_FILES['usuario_foto']['tmp_name'],$img_dir.$foto)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No podemos subir la imagen al sistema en este momento",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

        }else{
            //igual a un string vacio 
            $foto="";
        }


        $usuario_datos_reg=[
            [
                "campo_nombre"=>"cedula",
                "campo_marcador"=>":cedula",
                "campo_valor"=>$cedula
            ],
            [
                "campo_nombre"=>"nombre",
                "campo_marcador"=>":Nombre",
                "campo_valor"=>$nombre
            ],
            [
                "campo_nombre"=>"apellido",
                "campo_marcador"=>":Apellido",
                "campo_valor"=>$apellido
            ],
            [
                "campo_nombre"=>"email",
                "campo_marcador"=>":Email",
                "campo_valor"=>$email
            ],
            [
                "campo_nombre"=>"username",
                "campo_marcador"=>":Usuario",
                "campo_valor"=>$usuario
            ],
            [
                "campo_nombre"=>"password",
                "campo_marcador"=>":Clave",
                "campo_valor"=>$clave
            ],
            [
                "campo_nombre"=>"photo",
                "campo_marcador"=>":Foto",
                "campo_valor"=>$foto
            ],
        ];
        //
        $registrar_usuario=$this->guardarDatos("users",$usuario_datos_reg);

        if($registrar_usuario->rowCount()==1){
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"Usuario registrado",
                "texto"=>"El usuario ".$nombre." ".$apellido." se registro con exito",
                "icono"=>"success"
            ];
        }else{
            
            if(is_file($img_dir.$foto)){
                chmod($img_dir.$foto,0777);
                unlink($img_dir.$foto);
            }

            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No se pudo registrar el usuario, por favor intente nuevamente",
                "icono"=>"error"
            ];
        }

        return json_encode($alerta);

    }
    /*----------  Controlador listar usuario  ----------*/
    // public function listarUsuarioControlador($pagina,$registros,$url,$busqueda){//parametros a recibir

    //     $pagina=$this->limpiarCadena($pagina);
    //     $registros=$this->limpiarCadena($registros);

    //     $url=$this->limpiarCadena($url);
    //     $url=APP_URL.$url."/";

    //     $busqueda=$this->limpiarCadena($busqueda);
    //     $tabla="";


         
    //     $pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
    //     $inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

    //     if(isset($busqueda) && $busqueda!=""){

    //         $consulta_datos="SELECT * FROM users WHERE ((id!='".$_SESSION['id']."' AND id!='1') AND (nombre LIKE '%$busqueda%' OR apellido LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR username LIKE '%$busqueda%')) ORDER BY nombre ASC LIMIT $inicio,$registros";

    //         $consulta_total="SELECT COUNT(id) FROM users WHERE ((id!='".$_SESSION['id']."' AND id!='1') AND (nombre LIKE '%$busqueda%' OR apellido LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR username LIKE '%$busqueda%'))";

    //     }else{

    //         $consulta_datos="SELECT * FROM users WHERE id!='".$_SESSION['id']."' AND id!='1' ORDER BY nombre ASC LIMIT $inicio,$registros";

    //         $consulta_total="SELECT COUNT(id) FROM users WHERE id!='".$_SESSION['id']."' AND id!='1'";

    //     }

    //     $datos = $this->ejecutarConsulta($consulta_datos);// ejecutamos la consulta
    //     $datos = $datos->fetchAll();// la guardamos en array

    //     $total = $this->ejecutarConsulta($consulta_total);// ejecutar 
    //     $total = (int) $total->fetchColumn();// un array en entero

    //     $numeroPaginas =ceil($total/$registros);//total de paginas que va a esatr en la botonera de abajo
    //     // agregamos el html de las tablas//
    //     $tabla.='
    //         <div class="table-container">
    //         <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
    //             <thead>
    //                 <tr>
    //                     <th class="has-text-centered">#</th>
    //                     <th class="has-text-centered">Foto</th>
    //                     <th class="has-text-centered">Nombre</th>
    //                     <th class="has-text-centered">Usuario</th>
    //                     <th class="has-text-centered">Email</th>
    //                     <th class="has-text-centered" colspan="3">Opciones</th>
    //                 </tr>
    //             </thead>
    //             <tbody id="tablaDatos">
    //     ';

    //     if($total>=1 && $pagina<=$numeroPaginas){
    //         $contador=$inicio+1;
    //         $pag_inicio=$inicio+1;
    //         foreach($datos as $rows){
    //             $tabla .= '
    //             <tr class="has-text-centered">
    //                 <td class="has-text-centered">' . $contador . '</td>
    //                 <td class="has-text-centered">
    //                     <img class="is-rounded icono-imagen" src="' . APP_URL . 'app/views/fotos/' . $rows['photo'] . '" alt="Foto de Usuario" style="width: 50px; height: 50px; object-fit: cover;"/>
    //                 </td>
    //                 <td class="has-text-centered">' . $rows['nombre'] . ' ' . $rows['apellido'] . '</td>
    //                 <td class="has-text-centered">' . $rows['username'] . '</td>
    //                 <td class="has-text-centered">' . $rows['email'] . '</td>
    //                 <td>
    //                     <button type="button" class="button is-success is-rounded is-small" onclick="openUpdateModal(\'' . $rows['id'] . '\', \'' . htmlspecialchars($rows['nombre']) . '\', \'' . htmlspecialchars($rows['apellido']) . '\', \'' . htmlspecialchars($rows['username']) . '\', \'' . htmlspecialchars($rows['email']) . '\')">Actualizar</button>
    //     </td>
    //                 </td>
    //                 <td>
    //                     <form class="FormularioAjax" action="' . APP_URL . 'app/ajax/usuarioAjax.php" method="POST" autocomplete="off">
    //                         <input type="hidden" name="modulo_usuario" value="eliminar">
    //                         <input type="hidden" name="usuario_id" value="' . $rows['id'] . '">
    //                         <button type="submit" class="button is-danger is-rounded is-small">Eliminar</button>
    //                     </form>
    //                 </td>
    //             </tr>
    //         ';
    //             $contador++;
    //         }
    //         $pag_final=$contador-1;
    //     }else{
    //         if($total>=1){
    //             $tabla.='
    //                 <tr class="has-text-centered" >
    //                     <td colspan="7">
    //                         <a href="'.$url.'1/" class="button is-link is-rounded is-small mt-4 mb-4">
    //                             Haga clic acá para recargar el listado
    //                         </a>
    //                     </td>
    //                 </tr>
    //             ';
    //         }else{
    //             $tabla.='
    //                 <tr class="has-text-centered" >
    //                     <td colspan="7">
    //                         No hay registros en el sistema
    //                     </td>
    //                 </tr>
    //             ';
    //         }
    //     }

    //     $tabla.='</tbody></table></div>';

    //     ### Paginacion ###
    //     if($total>0 && $pagina<=$numeroPaginas){
    //         $tabla.='<p class="has-text-right">Mostrando usuarios <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

    //         $tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,10);
    //     }

    //     return $tabla;
    // }
    /*----------  Controlador listar usuario usuario  ----------*/
    public function lista_usuarios(){
         $consulta_datos="SELECT * FROM users WHERE id!='".$_SESSION['id']."' AND id!='1' ORDER BY nombre ASC";
         $datos = $this->ejecutarConsulta($consulta_datos);// ejecutamos la consulta
         $datos = $datos->fetchAll();
        return $datos;
    }
    /*----------  Controlador eliminar usuario  ----------*/
    public function eliminarUsuarioControlador(){

        $id=$this->limpiarCadena($_POST['usuario_id']);

        if($id==1){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No podemos eliminar el usuario principal del sistema",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando usuario #
        $datos=$this->ejecutarConsulta("SELECT * FROM users WHERE id='$id'");
        if($datos->rowCount()<=0){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos encontrado el usuario en el sistema",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }else{
            $datos=$datos->fetch();
        }

        $eliminarUsuario=$this->eliminarRegistro("users","id",$id);

        if($eliminarUsuario->rowCount()==1){

            if(is_file("../views/fotos/".$datos['photo'])){
                chmod("../views/fotos/".$datos['photo'],0777);
                unlink("../views/fotos/".$datos['photo']);
            }

            $alerta=[
                "tipo"=>"recargar",
                "titulo"=>"Usuario eliminado",
                "texto"=>"El usuario ".$datos['nombre']." ".$datos['apellido']." ha sido eliminado del sistema correctamente",
                "icono"=>"success"
            ];

        }else{

            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos podido eliminar el usuario ".$datos['nombre']." ".$datos['apellido']." del sistema, por favor intente nuevamente",
                "icono"=>"error"
            ];
        }

        return json_encode($alerta);
    }
    /*----------  Controlador actualizar usuario  ----------*/
    public function actualizarUsuarioControlador(){
      
            // Procesa el formulario (por ejemplo, guarda los datos en la base de datos)
          $id=$this->limpiarCadena($_POST['usuario_id']);
        # Verificando usuario #
        $datos=$this->ejecutarConsulta("SELECT * FROM users WHERE id='$id'");
        if($datos->rowCount()<=0){//PARA VER SI EL USUARIO EXISTE
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos encontrado el usuario en el sistema",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }else{
            $datos=$datos->fetch();// SI EXISTE LA GUARDAMOS EN UN ARRAY
        }
        // EL PRIEMRO ES EL USAURIO Y EL SEGUNDO  LA CALVE 
        $admin_usuario=$this->limpiarCadena($_POST['administrador_usuario']);
        $admin_clave=$this->limpiarCadena($_POST['administrador_clave']);

        # Verificando campos obligatorios admin #
        // VERIFICAMOS SI LA VARIABLE ESTAN LLENAS 
        if($admin_usuario=="" || $admin_clave==""){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No ha llenado todos los campos que son obligatorios, que corresponden a su USUARIO y CLAVE",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }
        // VERIFICAMOS EL FORMATO DEL USUARIO
        if($this->verificarDatos("[a-zA-Z0-9]{4,20}",$admin_usuario)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"Su USUARIO no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }
        // VERIFICAMOS LA CALVE
        if($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"Su CLAVE no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando administrador #
        //VERIFICAMOS QUE EL USUARIO QUE VA A ACTUALIZAR EXISTA 
        $check_admin=$this->ejecutarConsulta("SELECT * FROM users WHERE username='$admin_usuario' AND id='".$_SESSION['id']."'");
        if($check_admin->rowCount()==1){
            // LO HACEMOS UN ARRAY 
            $check_admin=$check_admin->fetch();
            //VERIFICAMOS QUE LA CALVE Y USURIO SEAN CORRECTOS
            if($check_admin['username']!=$admin_usuario || !password_verify($admin_clave,$check_admin['password'])){

                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"USUARIO o CLAVE de administrador incorrectos",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }else{
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"USUARIO o CLAVE de administrador incorrectos",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }


        # Almacenando datos#
        $nombre=$this->limpiarCadena($_POST['usuario_nombre']);
        $apellido=$this->limpiarCadena($_POST['usuario_apellido']);

        $usuario=$this->limpiarCadena($_POST['usuario_usuario']);
        $email=$this->limpiarCadena($_POST['usuario_email']);
        $clave1=$this->limpiarCadena($_POST['usuario_clave_1']);
        $clave2=$this->limpiarCadena($_POST['usuario_clave_2']);

        # Verificando campos obligatorios #
        if($nombre=="" || $apellido=="" || $usuario==""){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No has llenado todos los campos que son obligatorios",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando integridad de los datos #
        if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El NOMBRE no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El APELLIDO no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        if($this->verificarDatos("[a-zA-Z0-9]{4,20}",$usuario)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El USUARIO no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando email #
        if($email!="" && $datos['email']!=$email){// SI EL CAMPO EMAIL ESTA LLENO Y ES DISTINTO AL CORREO DE LA BASE DE DATOS 
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                // VAMOS A VER SI ESTA REPETIDO EL EMAIL
                $check_email=$this->ejecutarConsulta("SELECT email FROM users WHERE email='$email'");
                if($check_email->rowCount()>0){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Ha ingresado un correo electrónico no valido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }

        # Verificando claves #
        if($clave1!="" || $clave2!=""){
            if($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$clave1) || $this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$clave2)){

                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Las CLAVES no coinciden con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                if($clave1!=$clave2){

                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"Las nuevas CLAVES que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }else{
                    $clave=password_hash($clave1,PASSWORD_BCRYPT,["cost"=>10]);
                }
            }
        }else{
            $clave=$datos['password'];
        }

        # Verificando usuario #
        if($datos['username']!=$usuario){
            // VERIFICAMOS SI EL USUARIO INGRESADO YA EXISTE
            $check_usuario=$this->ejecutarConsulta("SELECT username FROM users WHERE username='$usuario'");
            if($check_usuario->rowCount()>0){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El USUARIO ingresado ya se encuentra registrado, por favor elija otro",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }
        //SI AL FINAL TODO ESTA HACEMOS EL ARRAY 
        $usuario_datos_up=[
            [
                "campo_nombre"=>"nombre",
                "campo_marcador"=>":Nombre",
                "campo_valor"=>$nombre
            ],
            [
                "campo_nombre"=>"apellido",
                "campo_marcador"=>":Apellido",
                "campo_valor"=>$apellido
            ],
            [
                "campo_nombre"=>"email",
                "campo_marcador"=>":Email",
                "campo_valor"=>$email
            ],
            [
                "campo_nombre"=>"username",
                "campo_marcador"=>":Usuario",
                "campo_valor"=>$usuario
            ],
            [
                "campo_nombre"=>"password",
                "campo_marcador"=>":Clave",
                "campo_valor"=>$clave
            ],
            
        ];

        $condicion=[
            "condicion_campo"=>"id",
            "condicion_marcador"=>":ID",
            "condicion_valor"=>$id
        ];

        if($this->actualizarDatos("users",$usuario_datos_up,$condicion)){
            //SI LOS DATOS QUE ACTUALIZA SON DE LA SESSION AVERTA ACTUALIZAMOS 
            
            if($id==$_SESSION['id']){
                $_SESSION['nombre']=$nombre;
                $_SESSION['apellido']=$apellido;
                $_SESSION['usuario']=$usuario;
            }

            $alerta=[
                "tipo"=>"recargar",
                "titulo"=>"Usuario actualizado",
                "texto"=>"Los datos del usuario ".$datos['nombre']." ".$datos['apellido']." se actualizaron correctamente",
                "icono"=>"success"
            ];
        }else{
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos podido actualizar los datos del usuario ".$datos['nombre']." ".$datos['apellido'].", por favor intente nuevamente",
                "icono"=>"error"
            ];
        }

        return json_encode($alerta);
    }
    /*------------------modal actualizar-----------------------*/
    public function actualizarUsuarioControladorModal(){
            // Procesa el formulario (por ejemplo, guarda los datos en la base de datos)
        $id=$this->limpiarCadena($_POST['id']);
        # Verificando usuario #
        $datos=$this->ejecutarConsulta("SELECT * FROM users WHERE id='$id'");
        if($datos->rowCount()<=0){//PARA VER SI EL USUARIO EXISTE
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos encontrado el usuario en el sistema",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }else{
            $datos=$datos->fetch();// SI EXISTE LA GUARDAMOS EN UN ARRAY
        }

        # Almacenando datos#
        $nombre=$this->limpiarCadena($_POST['nombre']);
        $apellido=$this->limpiarCadena($_POST['apellido']);

        $usuario=$this->limpiarCadena($_POST['username']);
        $email=$this->limpiarCadena($_POST['email']);
        # Verificando campos obligatorios #
        if($nombre=="" || $apellido=="" || $usuario==""){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No has llenado todos los campos que son obligatorios",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando integridad de los datos #
        if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El NOMBRE no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El APELLIDO no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        if($this->verificarDatos("[a-zA-Z0-9]{4,20}",$usuario)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El USUARIO no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando email #
        if($email!="" && $datos['email']!=$email){// SI EL CAMPO EMAIL ESTA LLENO Y ES DISTINTO AL CORREO DE LA BASE DE DATOS 
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                // VAMOS A VER SI ESTA REPETIDO EL EMAIL
                $check_email=$this->ejecutarConsulta("SELECT email FROM users WHERE email='$email'");
                if($check_email->rowCount()>0){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Ha ingresado un correo electrónico no valido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }

        # Verificando usuario #
        if($datos['username']!=$usuario){
            // VERIFICAMOS SI EL USUARIO INGRESADO YA EXISTE
            $check_usuario=$this->ejecutarConsulta("SELECT username FROM users WHERE username='$usuario'");
            if($check_usuario->rowCount()>0){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El USUARIO ingresado ya se encuentra registrado, por favor elija otro",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }
        //SI AL FINAL TODO ESTA HACEMOS EL ARRAY 
        $usuario_datos_up=[
            [
                "campo_nombre"=>"nombre",
                "campo_marcador"=>":Nombre",
                "campo_valor"=>$nombre
            ],
            [
                "campo_nombre"=>"apellido",
                "campo_marcador"=>":Apellido",
                "campo_valor"=>$apellido
            ],
            [
                "campo_nombre"=>"username",
                "campo_marcador"=>":Usuario",
                "campo_valor"=>$usuario
            ],
            [
                "campo_nombre"=>"email",
                "campo_marcador"=>":Email",
                "campo_valor"=>$email
            ],
        ];

        $condicion=[
            "condicion_campo"=>"id",
            "condicion_marcador"=>":id",
            "condicion_valor"=>$id
        ];

        if($this->actualizarDatos("users",$usuario_datos_up,$condicion)){
            //SI LOS DATOS QUE ACTUALIZA SON DE LA SESSION AVERTA ACTUALIZAMOS 
            
            if($id==$_SESSION['id']){
                $_SESSION['nombre']=$nombre;
                $_SESSION['apellido']=$apellido;
                $_SESSION['usuario']=$usuario;
            }

            $alerta=[
                "tipo"=>"recargar",
                "titulo"=>"Usuario actualizado",
                "texto"=>"Los datos del usuario ".$datos['nombre']." ".$datos['apellido']." se actualizaron correctamente",
                "icono"=>"success"
            ];
        }else{
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos podido actualizar los datos del usuario ".$datos['nombre']." ".$datos['apellido'].", por favor intente nuevamente",
                "icono"=>"error"
            ];
        }

        return json_encode($alerta);
    }
    /*----------  Controlador eliminar foto usuario  ----------*/
    public function eliminarFotoUsuarioControlador(){

        $id=$this->limpiarCadena($_POST['usuario_id']);

        # Verificando usuario #
        $datos=$this->ejecutarConsulta("SELECT * FROM users WHERE id='$id'");
        if($datos->rowCount()<=0){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos encontrado el usuario en el sistema",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }else{
            $datos=$datos->fetch();
        }

        # Directorio de imagenes #
        $img_dir="../views/fotos/";//DONDE ESTA LA FOTO

        chmod($img_dir,0777);//PERMISOS DE ESCRITURA Y ELIMINAR

        if(is_file($img_dir.$datos['photo'])){//SI LA FOTO ESTA

            chmod($img_dir.$datos['photo'],0777);// PERMISOS DE ESCRITURA Y ELIMINAR

            if(!unlink($img_dir.$datos['photo'])){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Error al intentar eliminar la foto del usuario, por favor intente nuevamente",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }else{
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos encontrado la foto del usuario en el sistema",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        $usuario_datos_up=[
            [
                "campo_nombre"=>"photo",
                "campo_marcador"=>":photo",
                "campo_valor"=>""
            ],
        ];

        $condicion=[
            "condicion_campo"=>"id",
            "condicion_marcador"=>":id",
            "condicion_valor"=>$id
        ];

        if($this->actualizarDatos("users",$usuario_datos_up,$condicion)){

            if($id==$_SESSION['id']){
                $_SESSION['foto']="";
            }

            $alerta=[
                "tipo"=>"recargar",
                "titulo"=>"Foto eliminada",
                "texto"=>"La foto del usuario ".$datos['nombre']." ".$datos['apellido']." se elimino correctamente",
                "icono"=>"success"
            ];
        }else{
            $alerta=[
                "tipo"=>"recargar",
                "titulo"=>"Foto eliminada",
                "texto"=>"No hemos podido actualizar algunos datos del usuario ".$datos['nombre']." ".$datos['apellido'].", sin embargo la foto ha sido eliminada correctamente",
                "icono"=>"warning"
            ];
        }

        return json_encode($alerta);
    }


    /*----------  Controlador actualizar foto usuario  ----------*/
    public function actualizarFotoUsuarioControlador(){

        $id=$this->limpiarCadena($_POST['usuario_id']);

        # Verificando usuario #
        $datos=$this->ejecutarConsulta("SELECT * FROM users WHERE id='$id'");
        if($datos->rowCount()<=0){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos encontrado el usuario en el sistema",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }else{
            $datos=$datos->fetch();
        }

        # Directorio de imagenes #
        $img_dir="../views/fotos/";//DONDE ESTARA LA FOTO

        # Comprobar si se selecciono una imagen #
        if($_FILES['usuario_foto']['name']=="" && $_FILES['usuario_foto']['size']<=0){// ES EL INPUT 
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No ha seleccionado una foto para el usuario",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Creando directorio #
        if(!file_exists($img_dir)){
            if(!mkdir($img_dir,0777)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Error al crear el directorio",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            } 
        }

        # Verificando formato de imagenes #
        if(mime_content_type($_FILES['usuario_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['usuario_foto']['tmp_name'])!="image/png"){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"La imagen que ha seleccionado es de un formato no permitido",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando peso de imagen #
        if(($_FILES['usuario_foto']['size']/1024)>5120){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"La imagen que ha seleccionado supera el peso permitido",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Nombre de la foto #
        if($datos['photo']!=""){
            $foto=explode(".", $datos['photo']);//separarlafoto dividimos el nombre y la extencio
            $foto=$foto[0];//solo queremos el nombre
        }else{
            $foto=str_ireplace(" ","_",$datos['nombre']);
            $foto=$foto."_".rand(0,100);
        }
        

        # Extension de la imagen #
        switch(mime_content_type($_FILES['usuario_foto']['tmp_name'])){
            case 'image/jpeg':
                $foto=$foto.".jpg";
            break;
            case 'image/png':
                $foto=$foto.".png";
            break;
        }

        chmod($img_dir,0777);

        # Moviendo imagen al directorio #
        if(!move_uploaded_file($_FILES['usuario_foto']['tmp_name'],$img_dir.$foto)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No podemos subir la imagen al sistema en este momento",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Eliminando imagen anterior #
        if(is_file($img_dir.$datos['photo']) && $datos['photo']!=$foto){
            chmod($img_dir.$datos['photo'], 0777);
            unlink($img_dir.$datos['photo']);
        }

        $usuario_datos_up=[
            [
                "campo_nombre"=>"photo",
                "campo_marcador"=>":photo",
                "campo_valor"=>$foto
            ],
        ];

        $condicion=[
            "condicion_campo"=>"id",
            "condicion_marcador"=>":id",
            "condicion_valor"=>$id
        ];

        if($this->actualizarDatos("users",$usuario_datos_up,$condicion)){

            if($id==$_SESSION['id']){
                $_SESSION['foto']=$foto;
            }

            $alerta=[
                "tipo"=>"recargar",
                "titulo"=>"Foto actualizada",
                "texto"=>"La foto del usuario ".$datos['nombre']." ".$datos['apellido']." se actualizo correctamente",
                "icono"=>"success"
            ];
        }else{

            $alerta=[
                "tipo"=>"recargar",
                "titulo"=>"Foto actualizada",
                "texto"=>"No hemos podido actualizar algunos datos del usuario ".$datos['nombre']." ".$datos['apellido']." , sin embargo la foto ha sido actualizada",
                "icono"=>"warning"
            ];
        }

        return json_encode($alerta);
    }

}