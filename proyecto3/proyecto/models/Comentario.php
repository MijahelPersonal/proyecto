<?php

require_once __DIR__ . "/../config/conexion.php";

class Comentario {

    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    public function crear($publicacion_id, $usuario_id, $comentario) {
        $sql = "INSERT INTO comentarios(publicacion_id, usuario_id, comentario)
                VALUES(:publicacion_id, :usuario_id, :comentario)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":publicacion_id" => $publicacion_id,
            ":usuario_id" => $usuario_id,
            ":comentario" => $comentario
        ]);
    }

    public function listarPorPublicacion($publicacion_id) {
        $sql = "SELECT 
                    c.*,
                    u.nombre AS usuario,
                    u.rol AS usuario_rol
                FROM comentarios c
                INNER JOIN usuarios u ON c.usuario_id = u.id
                WHERE c.publicacion_id = :publicacion_id
                ORDER BY c.id ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ":publicacion_id" => $publicacion_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contar() {
        $sql = "SELECT COUNT(*) AS total FROM comentarios";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado["total"];
    }
}