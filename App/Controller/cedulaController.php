<?php
namespace App\Controller;
use App\Model\mainModel;
use PDO;
use PDOException;

class cedulaController{
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
    public function cedula()
    {
        if (isset($_POST['cedula'])) {
            $cedula = $_POST['cedula'];

            try {
                // Inicializa tu conexión a la base de datos
                $pdo = $this->conectar();

                // Consulta para verificar si la cédula ya existe
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE cedula = :cedula");
                $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
                $stmt->execute();

                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    echo "existe";
                    // Respuesta si la cédula ya está registrada
                } else {
                    echo "no_existe";
                     // Respuesta si la cédula no está registrada
                }

            } catch (PDOException $e) {
                echo "error"; // En caso de error en la consulta o conexión
            }
        }
    }
    
}