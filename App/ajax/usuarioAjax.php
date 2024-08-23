<?php
require_once "../../Config/app.php";
require_once "../views/inc/session_start.php";// tiene el inicio de la sesion
require_once "../../autoload.php";

use App\Controller\userController;

if(isset($_POST['modulo_usuario'])){
    // una instancia de todos los controler de usuario
    $insUsuario = new userController();

    if($_POST['modulo_usuario']=="registrar"){
        echo $insUsuario->registrarUsuarioControlador();
    }

    if($_POST['modulo_usuario']=="eliminar"){
        echo $insUsuario->eliminarUsuarioControlador();
    }

    if($_POST['modulo_usuario']=="actualizar"){
        echo $insUsuario->actualizarUsuarioControlador();
    }
    if($_POST['modulo_usuario']=="actualizar1"){
        echo $insUsuario->actualizarUsuarioControlador1();
    }

    if($_POST['modulo_usuario']=="eliminarFoto"){
        echo $insUsuario->eliminarFotoUsuarioControlador();
    }

    if($_POST['modulo_usuario']=="actualizarFoto"){
        echo $insUsuario->actualizarFotoUsuarioControlador();
    }
}else{
session_destroy();
header("Location: ".APP_URL."login/");
exit();
}