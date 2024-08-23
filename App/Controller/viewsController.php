<?php
namespace App\Controller;
// hereda de modelos vista
use App\Model\viewsModel;// la clase padre es viewsModel
use \PDO;
class viewsController extends viewsModel
{

    public function obtenerVistaControlador($vista,$id)
    {// recivimos un parametro
        if ($vista != "") {// verificar si esta llena la variable
            $respuesta = $this->obtenerVistaModelo($vista,$id);//le mandamos el valor al modelo para que devuelva la ruta o 404 o login
        } elseif($vista =='logOut') {// si esta vacia cargamos el login 
            $respuesta = "logOut";
        }else{
            $respuesta='login';
        }
        return $respuesta;
    }

}