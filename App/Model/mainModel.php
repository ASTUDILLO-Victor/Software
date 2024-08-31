<?php
// tendra funciones que se utilizan mas de una ves como consulta o conexion 

namespace App\Model;

use \PDO;

if (is_file(__DIR__ . "/../../Config/server.php")) { // si la ruta existe 
    require_once __DIR__ . "/../../Config/server.php"; // me trae la ruta
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
        $conexion = new PDO("mysql:host=" . $this->server . ";dbname=" . $this->db, $this->user, $this->pass);
        $conexion->exec("SET CHARACTER SET utf8");
        return $conexion;
    }
    // funcion para consultas a la base de datos 
    public function ejecutarConsulta($consulta)
    {
        $conn = $this->conectar();
        $conn->beginTransaction();

        try {
            $sql = $conn->prepare($consulta);
            $sql->execute();
            $conn->commit();
            return $sql;
        } catch (\PDOException $ERROR) {
            $conn->rollback();
            die($ERROR->getMessage());
        }
    }
    public function ejecutarConsulta_con_parametros($consulta, $params = [])
    {
        $conn = $this->conectar();
        $conn->beginTransaction();

        try {
            $sql = $conn->prepare($consulta);

            foreach ($params as $param => $value) {
                $sql->bindValue($param, $value);
            }

            $sql->execute();
            $conn->commit();
            return $sql;
        } catch (\PDOException $ERROR) {
            $conn->rollback();
            die($ERROR->getMessage());
        }
    }
    //funcion para eviatar la inyeccion sql primer filtro
    public function limpiarCadena($cadena)
    {
        //no van a estar oermitido en el texto que ingrese el usuario
        $palabras = [
            "<script>",
            "</script>",
            "<script src",
            "<script type=",
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
        $cadena = trim($cadena); // borra los espacios en blanco
        $cadena = stripslashes($cadena); // quitan barras invertidas
        foreach ($palabras as $palabra) {
            $cadena = str_ireplace($palabra, "", $cadena); // replansar las palabras
        }
        $cadena = trim($cadena); // quita los espacios en blanco 
        $cadena = stripslashes($cadena); // las barras se quitan 
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
        $conn = $this->conectar();
        $conn->beginTransaction();

        try {
            $query = "INSERT INTO $tabla (";
            $c = 0;
            foreach ($datos as $clave) {
                if ($c >= 1) {
                    $query .= ",";
                }
                $query .= $clave["campo_nombre"];
                $c++;
            }
            $query .= ") VALUES (";
            $c = 0;
            foreach ($datos as $clave) {
                if ($c >= 1) {
                    $query .= ",";
                }
                $query .= $clave["campo_marcador"];
                $c++;
            }
            $query .= ")";

            $sql = $conn->prepare($query);

            foreach ($datos as $clave) {
                $sql->bindParam($clave["campo_marcador"], $clave["campo_valor"]);
            }

            $sql->execute();
            $conn->commit();
            return $sql;
        } catch (\PDOException $ERROR) {
            $conn->rollback();
            die($ERROR->getMessage());
        }
    }
    // modelo para selecionar datos select
    public function seleccionarDatos($tipo, $tabla, $campo, $id)
    {
        $conn = $this->conectar();
        $conn->beginTransaction();

        try {
            $tipo = $this->limpiarCadena($tipo);
            $tabla = $this->limpiarCadena($tabla);
            $campo = $this->limpiarCadena($campo);
            $id = $this->limpiarCadena($id);

            if ($tipo == "Unico") {
                $sql = $conn->prepare("SELECT * FROM $tabla WHERE $campo=:ID");
                $sql->bindParam(":ID", $id);
            } elseif ($tipo == "Normal") {
                $sql = $conn->prepare("SELECT $campo FROM $tabla");
            }

            $sql->execute();
            $conn->commit();
            return $sql;
        } catch (\PDOException $ERROR) {
            $conn->rollback();
            die($ERROR->getMessage());
        }
    }
    // actualizar datos
    protected function actualizarsession($tabla, $datos, $condiciones)
    {
        $conn = $this->conectar();
        $conn->beginTransaction();

        try {
            $query = "UPDATE $tabla SET ";
            $C = 0;
            foreach ($datos as $clave) {
                if ($C >= 1) {
                    $query .= ",";
                }
                $query .= $clave["campo_nombre"] . "=" . $clave["campo_marcador"];
                $C++;
            }

            $query .= " WHERE ";
            foreach ($condiciones as $index => $condicion) {
                if ($index > 0) {
                    $query .= " AND ";
                }
                $query .= $condicion["condicion_campo"] . "=" . $condicion["condicion_marcador"];
            }

            $sql = $conn->prepare($query);

            foreach ($datos as $clave) {
                $sql->bindParam($clave["campo_marcador"], $clave["campo_valor"]);
            }

            foreach ($condiciones as $condicion) {
                $sql->bindParam($condicion["condicion_marcador"], $condicion["condicion_valor"]);
            }

            $sql->execute();
            $conn->commit();
            return $sql;
        } catch (\PDOException $ERROR) {
            $conn->rollback();
            die($ERROR->getMessage());
        }
    }
    protected function actualizarDatos($tabla, $datos, $condicion)
    {
        $conn = $this->conectar();
        $conn->beginTransaction();

        try {
            $query = "UPDATE $tabla SET ";
            $C = 0;
            foreach ($datos as $clave) {
                if ($C >= 1) {
                    $query .= ",";
                }
                $query .= $clave["campo_nombre"] . "=" . $clave["campo_marcador"];
                $C++;
            }

            $query .= " WHERE " . $condicion["condicion_campo"] . "=" . $condicion["condicion_marcador"];

            $sql = $conn->prepare($query);

            foreach ($datos as $clave) {
                $sql->bindParam($clave["campo_marcador"], $clave["campo_valor"]);
            }

            $sql->bindParam($condicion["condicion_marcador"], $condicion["condicion_valor"]);

            $sql->execute();
            $conn->commit();
            return $sql;
        } catch (\PDOException $ERROR) {
            $conn->rollback();
            die($ERROR->getMessage());
        }
    }
    /*---------- Funcion eliminar registro ----------*/
    protected function eliminarRegistro($tabla, $campo, $id)
    {
        $conn = $this->conectar();
        $conn->beginTransaction();

        try {
            $sql = $conn->prepare("DELETE FROM $tabla WHERE $campo=:id");
            $sql->bindParam(":id", $id);
            $sql->execute();
            $conn->commit();
            return $sql;
        } catch (\PDOException $ERROR) {
            $conn->rollback();
            die($ERROR->getMessage());
        }
    }

    /*-------------------paginador----------------------*/
    // protected function paginadorTablas($pagina, $numeroPaginas, $url, $botones)
    // {/*  pagina la paguina que estoy , el numero de paginas , url para que simpre me mantega en la misma pagina */
    //     $tabla = '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">'; //generamos la botonera
    //     if ($pagina <= 1) { //esatmos en la paguina 1 y se desabilita el botn anterior 
    //         $tabla .= '
    //         <a class="pagination-previous is-disabled" disabled >Anterior</a>
    //         <ul class="pagination-list">
    //         ';
    //     } else {
    //         $tabla .= '
    //         <a class="pagination-previous" href="' . $url . ($pagina - 1) . '/">Anterior</a> 
    //         <ul class="pagination-list">
    //             <li><a class="pagination-link" href="' . $url . '1/">1</a></li>
    //             <li><span class="pagination-ellipsis">&hellip;</span></li>
    //         ';
    //     }

    //     /*
    //     genrer un numero limitado de botones
    //      */
    //     $ci = 0;
    //     for ($i = $pagina; $i <= $numeroPaginas; $i++) {

    //         if ($ci >= $botones) {
    //             break;
    //         }
    //         //
    //         if ($pagina == $i) {
    //             $tabla .= '<li><a class="pagination-link is-current" href="' . $url . $i . '/">' . $i . '</a></li>';
    //         } else {
    //             $tabla .= '<li><a class="pagination-link" href="' . $url . $i . '/">' . $i . '</a></li>';
    //         }

    //         $ci++;
    //     }

    //     /*desabilitar siguiente si estamos en la ultima paguina*/
    //     if ($pagina == $numeroPaginas) {
    //         $tabla .= '
    //         </ul>
    //         <a class="pagination-next is-disabled" disabled >Siguiente</a>
    //         ';
    //     } else {
    //         $tabla .= '
    //             <li><span class="pagination-ellipsis">&hellip;</span></li>
    //             <li><a class="pagination-link" href="' . $url . $numeroPaginas . '/">' . $numeroPaginas . '</a></li>
    //         </ul>
    //         <a class="pagination-next" href="' . $url . ($pagina + 1) . '/">Siguiente</a>
    //         ';
    //     }

    //     $tabla .= '</nav>';
    //     return $tabla;
    // }
}
