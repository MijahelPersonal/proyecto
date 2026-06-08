<?php

require_once  __DIR__ . "/../config/conexion.php";

class Categoria {

    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    public function crear($nombre, $descripcion) {
        $sql = "INSERT INTO categorias(nombre, descripcion)
                VALUES(:nombre, :descripcion)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":nombre" => $nombre,
            ":descripcion" => $descripcion
        ]);
    }

    public function listar() {
        $sql = "SELECT * FROM categorias ORDER BY id DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM categorias WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function contar() {
    $sql = "SELECT COUNT(*) AS total FROM categorias";

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado["total"];
}
}