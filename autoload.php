<?php
//spl_autoload_register obtener el nombre de las clases que se usan en el sistema
//str_replace se usa para replansar el \ por / y clase tiene el nombre 
//con namepaces 
spl_autoload_register(function($clase){

    $archivo= __DIR__."/".$clase.".php";
    $archivo=str_replace("\\","/",$archivo);

    if(is_file($archivo)){
        require_once $archivo;
    } 
});
