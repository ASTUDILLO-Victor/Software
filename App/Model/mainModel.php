<?php
// tendra funciones que se utilizan mas de una ves como consulta o conexion 

namespace App\Model;
use \PDO;
if (is_file(__DIR__ . "/../../Config/server.php")) {// si la ruta existe 
    require_once __DIR__ . "/../../Config/server.php";// me trae la ruta
}
class mainModel
{
    // private solo se puede accerder de la misma clase 
    private $server = DB_SERVER;
    private $db = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;

    protected function conectar()
    {
        $conexion = new PDO("mysql:host=".$this->server.";dbname=".$this->db,$this->user,$this->pass);
			$conexion->exec("SET CHARACTER SET utf8");
			return $conexion;
    }

    // funcion para consultas a la base de datos 
    protected function ejecutarConsulta($consulta)
    {//$consulta es la consulta que vamos hacer
        $sql = $this->conectar()->prepare($consulta);//conectamos a la base y preparamos la consulta 
        $sql->execute();// se ejecuta la consulta
        return $sql;
    }
    public function ejecutarConsulta1($consulta, $params = [])
    {
        // Obtener la conexión PDO
        $pdo = $this->conectar();
        
        // Preparar la consulta
        $sql = $pdo->prepare($consulta);
        
        // Vincular los parámetros, si existen
        foreach ($params as $param => $value) {
            $sql->bindValue($param, $value);
        }
        
        // Ejecutar la consulta
        $sql->execute();
        
        // Devolver el objeto PDOStatement
        return $sql;
    }
    

    //funcion para eviatar la inyeccion sql primer filtro
    public function limpiarCadena($cadena)
    {
        //no van a estar oermitido en el texto que ingrese el usuario
        $palabras = [
            "<script>",
            "</script>",
            "<script src",
            "<script type="
            ,
            "SELECT * FROM",
            "SELECT ",
            " SELECT ",
            "DELETE FROM",
            "INSERT INTO",
            "DROP TABLE",
            "DROP DATABASE",
            "TRUNCATE TABLE",
            "SHOW TABLES",
            "SHOW DATABASES",
            "<?php",
            "?>",
            "--",
            "^",
            "<",
            ">",
            "==",
            "=",
            ";",
            "::"
        ];
        $cadena = trim($cadena);// borra los espacios en blanco
        $cadena = stripslashes($cadena);// quitan barras invertidas
        foreach ($palabras as $palabra) {
            $cadena = str_ireplace($palabra, "", $cadena);// replansar las palabras
        }
        $cadena = trim($cadena);// quita los espacios en blanco 
        $cadena = stripslashes($cadena);// las barras se quitan 
        return $cadena;
    }

    // segundo filtro
    protected function verificarDatos($filtro, $cadena)
    {
        if (preg_match("/^" . $filtro . "$/", $cadena)) {
            return false;
        } else {
            return true;
        }

    }
    //guardar datos en la base de datos
    protected function guardarDatos($tabla, $datos)
    {
        // variable para almacenar la consulta
        $query = "INSERT INTO $tabla ("; //inico del query 

        $c = 0;
        foreach ($datos as $clave) {//se recorre el array datos que tiene 3  key 
            if ($c >= 1) {
                $query .= ",";//concatenar la coma 
            }
            $query .= $clave["campo_nombre"];//agrega esto a continuacion del query 
            $c++;
        }
        $query .= ")VALUES(";//el resto del quey 
        $c = 0;
        foreach ($datos as $clave) {//se recorre el array datos que tiene 3  key
            if ($c >= 1) {$query .= ","; }//concatenar la coma
            $query .= $clave["campo_marcador"];// agrega los marcadores 
            $c++;
        }
        $query .= ")";// fenalizacion del query 
        $sql = $this->conectar()->prepare($query);//se prepara LA QUERY COMPLETA PARA EJECUTARLA 
        foreach ($datos as $clave) {
            $sql->bindParam($clave["campo_marcador"], $clave["campo_valor"]);// este metodo vincula o sustituye de la consulta sql un marcador (:name)con el valor real de la variable php
        }
        $sql->execute();// se ejecuta la consulta 
        return $sql;// devolvemos la respuesta 
    }


    // modelo para selecionar datos select
    public function seleccionarDatos($tipo, $tabla, $campo, $id)
    {
        $tipo = $this->limpiarCadena($tipo);// enviamos tipo a la funcion limpiar cadena para evitar inyc sql
        $tabla = $this->limpiarCadena($tabla);
        $campo = $this->limpiarCadena($campo);
        $id = $this->limpiarCadena($id);

        if($tipo=="Unico"){//buscar por id
            $sql=$this->conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:ID");
            $sql->bindParam(":ID",$id);//cambiar el marcador por la id 
        }elseif($tipo=="Normal"){// buscar sin id
            $sql=$this->conectar()->prepare("SELECT $campo FROM $tabla");
        }elseif($tipo=="diferente"){

        }
        $sql->execute();

        return $sql;
    }

    //modificado para poder trar la hora de sesion iniciada 
    
// actualizar datos
protected function actualizarDatos($tabla, $datos, $condiciones) {
    $query = "UPDATE $tabla SET ";

    $C = 0;
    foreach ($datos as $clave) {
        if ($C >= 1) {
            $query .= ","; // Agregamos una coma para separar los campos
        }
        $query .= $clave["campo_nombre"] . "=" . $clave["campo_marcador"]; // Construimos el SET
        $C++;
    }

    // Construimos la cláusula WHERE con múltiples condiciones
    $query .= " WHERE ";
    $condiciones_count = count($condiciones);
    foreach ($condiciones as $index => $condicion) {
        if ($index > 0) {
            $query .= " AND "; // Agregamos "AND" entre condiciones
        }
        $query .= $condicion["condicion_campo"] . "=" . $condicion["condicion_marcador"];
    }

    $sql = $this->conectar()->prepare($query);

    // Asignamos los valores a los marcadores
    foreach ($datos as $clave) {
        $sql->bindParam($clave["campo_marcador"], $clave["campo_valor"]);
    }

    // Asignamos los valores a los marcadores de las condiciones
    foreach ($condiciones as $condicion) {
        $sql->bindParam($condicion["condicion_marcador"], $condicion["condicion_valor"]);
    }

    $sql->execute();

    return $sql;
}
protected function actualizarDatos1($tabla,$datos,$condicion){
			
    $query="UPDATE $tabla SET ";// inicio el query 

    $C=0;
    foreach ($datos as $clave){
        if($C>=1){
            $query.=","; //este if sirve para poner la coma despues de poner campo nombre
        }
        $query.=$clave["campo_nombre"]."=".$clave["campo_marcador"];// resto del query
        $C++;
    }
    //resto del query
    $query.=" WHERE ".$condicion["condicion_campo"]."=".$condicion["condicion_marcador"];

    $sql=$this->conectar()->prepare($query);

    foreach ($datos as $clave){
        //cambiar la primera con la segunda
        $sql->bindParam($clave["campo_marcador"],$clave["campo_valor"]);
    }
    // cambiar  la primera por la segunda//
    $sql->bindParam($condicion["condicion_marcador"],$condicion["condicion_valor"]);

    $sql->execute();

    return $sql;
}

    /*---------- Funcion eliminar registro ----------*/
    protected function eliminarRegistro($tabla,$campo,$id){
        $sql=$this->conectar()->prepare("DELETE FROM $tabla WHERE $campo=:id");
        $sql->bindParam(":id",$id);
        $sql->execute();
        
        return $sql;
    }


    /*-------------------paginador----------------------*/
    protected function paginadorTablas ($pagina,$numeroPaginas,$url,$botones){/*  pagina la paguina que estoy , el numero de paginas , url para que simpre me mantega en la misma pagina */
        $tabla='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';//generamos la botonera
        if($pagina<=1){//esatmos en la paguina 1 y se desabilita el botn anterior 
            $tabla.='
            <a class="pagination-previous is-disabled" disabled >Anterior</a>
            <ul class="pagination-list">
            ';
        }else{
            $tabla.='
            <a class="pagination-previous" href="'.$url.($pagina-1).'/">Anterior</a> 
            <ul class="pagination-list">
                <li><a class="pagination-link" href="'.$url.'1/">1</a></li>
                <li><span class="pagination-ellipsis">&hellip;</span></li>
            ';
        }

        /*
        genrer un numero limitado de botones
         */
        $ci=0;
        for($i=$pagina; $i<=$numeroPaginas; $i++){

            if($ci>=$botones){
                break;
            }
            //
            if($pagina==$i){
                $tabla.='<li><a class="pagination-link is-current" href="'.$url.$i.'/">'.$i.'</a></li>';
            }else{
                $tabla.='<li><a class="pagination-link" href="'.$url.$i.'/">'.$i.'</a></li>';
            }

            $ci++;
        }

        /*desabilitar siguiente si estamos en la ultima paguina*/
        if($pagina==$numeroPaginas){
            $tabla.='
            </ul>
            <a class="pagination-next is-disabled" disabled >Siguiente</a>
            ';
        }else{
            $tabla.='
                <li><span class="pagination-ellipsis">&hellip;</span></li>
                <li><a class="pagination-link" href="'.$url.$numeroPaginas.'/">'.$numeroPaginas.'</a></li>
            </ul>
            <a class="pagination-next" href="'.$url.($pagina+1).'/">Siguiente</a>
            ';
        }

        $tabla.='</nav>';
        return $tabla;

    }

}