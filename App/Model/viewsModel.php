<?php
namespace App\Model;
//modelos de las vistas 
use \PDO;
if (is_file(__DIR__ . "/../../Config/server.php")) {// si la ruta existe 
    require_once __DIR__ . "/../../Config/server.php";// me trae la ruta
}
class viewsModel
{
    private $server = DB_SERVER;
    private $db = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;
    /*function para conectar a la base de datos  */
    protected function conectar()
    {
        $conexion = new PDO("mysql:host=" . $this->server . ";dbname=" . $this->db, $this->user, $this->pass);
        $conexion->exec("SET CHARACTER SET utf8");
        return $conexion;
    }
    /*fin de la function */

    //funcion para las vistas
    // solo se usa en esta clase y en la que hereda 
    //nombre de la vista $vista

    // resive un valor desde viewscontroller que es el nombre de la vista 
    protected function obtenerVistaModelo($vista, $id)
    {
        // Esta función se encarga de decidir qué página (vista) mostrar en función de los permisos del usuario.
        
        // Primero, escribimos la consulta para obtener los permisos del rol de usuario.
        $sql = "
            SELECT roles.name AS role_name, permissions.name AS permission_name
            FROM roles
            JOIN role_permissions ON roles.id = role_permissions.role_id
            JOIN permissions ON role_permissions.permission_id = permissions.id
            WHERE roles.id = :id
        ";
        
        // Preparamos la consulta para que la base de datos la entienda y la ejecute.
        $query = $this->conectar()->prepare($sql);
        
        // Aquí vinculamos el número que representa el rol del usuario (el ID) a la consulta.
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Ahora ejecutamos la consulta para obtener los resultados de la base de datos.
        $query->execute();
        
        // Guardamos todos los resultados que obtuvimos de la consulta en una lista (array) que tiene filas y columnas.
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Extraemos solo los nombres de las vistas que están permitidas para el rol del usuario.
        $vistasPermitidas = array_column($resultados, 'permission_name');
        
        // Ahora verificamos si la vista solicitada (la página que se quiere mostrar) está permitida.
        if (in_array($vista, $vistasPermitidas)) {
            // Si la vista está permitida, verificamos si el archivo de la vista realmente existe en la carpeta de vistas.
            if (is_file("./App/views/CONTENT/" . $vista . "-view.php")) {
                // Si el archivo existe, guardamos la ruta al archivo.
                $contenido = "./App/views/CONTENT/" . $vista . "-view.php";
            } else {
                // Si la vista está permitida pero el archivo no existe, mostramos un error (404).
                $contenido = "404";
            }
        } elseif (is_file("./App/views/CONTENT/" . $vista . "-view.php")) {
            // Si la vista no está permitida pero el archivo sí existe, mostramos un mensaje que dice "block".
            $contenido = "block";
        } elseif ($vista == "login" || $vista == "index") {
            // Si la vista que se pide es "login" o "index" (la página principal), mostramos la vista de login.
            $contenido = "login";
        } elseif ($vista == "logOut") {
            // Si la vista que se pide es "logOut", redirigimos a la página de cierre de sesión.
            $contenido = "logOut";
        } else {
            // Si la vista no está permitida, no existe el archivo, y no es "login", "index", ni "logOut", mostramos un error (404).
            $contenido = "404";
        }
        
        // Finalmente, devolvemos el resultado, que es la ruta del archivo de la vista que se va a mostrar.
        return $contenido;
    }
    
}
