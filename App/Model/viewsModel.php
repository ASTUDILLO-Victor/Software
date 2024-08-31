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
    $dsn = "mysql:host={$this->server};dbname={$this->db}";
    $conexion = new PDO($dsn, $this->user, $this->pass);
    $conexion->exec("SET CHARACTER SET utf8");
    
    return $conexion;
}

protected function obtenerVistaModelo(string $vista, int $id): string
{
    $vistasPermitidas = $this->obtenerVistasPermitidasPorRol($id);

    if ($this->esVistaPermitida($vista, $vistasPermitidas)) {
        return $this->rutaVista($vista) ?? "404";
    } elseif ($this->esVistaEspecial($vista)) {
        return $vista;
    }

    return "404";
}

private function obtenerVistasPermitidasPorRol(int $id): array
{
    $sql = "
        SELECT permissions.name AS permission_name
        FROM roles
        JOIN role_permissions ON roles.id = role_permissions.role_id
        JOIN permissions ON role_permissions.permission_id = permissions.id
        WHERE roles.id = :id
    ";

    $query = $this->conectar()->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    return array_column($query->fetchAll(PDO::FETCH_ASSOC), 'permission_name');
}

private function esVistaPermitida(string $vista, array $vistasPermitidas): bool
{
    return in_array($vista, $vistasPermitidas);
}

private function rutaVista(string $vista): ?string
{
    $ruta = "./App/views/CONTENT/{$vista}-view.php";
    return is_file($ruta) ? $ruta : null;
}

private function esVistaEspecial(string $vista): bool
{
    return in_array($vista, ["login", "index", "logOut"]);
}

    
}
