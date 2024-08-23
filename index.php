<?PHP
// SE EJECUTA TODA LA APLICACION
//las confinguaraciones
require_once "./Config/app.php";
//fin 

//el autoload 
require_once "./autoload.php";//el punto es para definir que estamos en el mismo nivel
//fin
require_once "./app/views/inc/session_start.php";







if(isset($_GET["views"])){
    $url=explode("/",$_GET["views"]);//dive en pedaso un string segun el cracter
}else{
    $url=["login"];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once "./app/views/inc/header.php";?>
    <!-- en esta ruta tendra el header -->
</head>
<body>
   

<!-- final -->
<?php 

use App\Controller\viewsController;
use App\Controller\loginController;

$insLogin = new loginController();
$viewsController = new viewsController();

// Verifica si 'id' está definida en $_SESSION antes de intentar acceder a ella
$vista = isset($_SESSION['rol']) ? $viewsController->obtenerVistaControlador($url[0], $_SESSION['rol']) : "login";

if ($vista == "login" or $vista == "404" or $vista=='logOut') {
    require_once "./App/views/CONTENT/" . $vista . "-view.php";
} elseif( $vista == 'block'){
    require_once "./App/views/inc/navbar.php";
    
    require_once "./App/views/CONTENT/" . $vista . "-view.php";
}  else {
    /* Cerrar la sesión si 'id' o 'usuario' no están definidos o están vacíos */
    if ((!isset($_SESSION['id']) || $_SESSION['id'] == "") || (!isset($_SESSION['usuario']) || $_SESSION['usuario'] == "")) {
        $insLogin->cerrarSesionControlador();
        exit();
    }
    require_once "./App/views/inc/navbar.php";
    
    require_once $vista;
} 

require_once "./App/views/inc/script.php";

?>

    
</body>
</html>