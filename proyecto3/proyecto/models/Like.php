<?php

require_once __DIR__ . "/../config/conexion.php";

class Like {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    public function contar($publicacion_id) {
        $sql = "SELECT COUNT(*) AS total FROM likes WHERE publicacion_id = :publicacion_id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":publicacion_id" => $publicacion_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado["total"];
    }

    public function usuarioDioLike($usuario_id, $publicacion_id) {
        $sql = "SELECT id FROM likes 
                WHERE usuario_id = :usuario_id 
                AND publicacion_id = :publicacion_id
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ":usuario_id" => $usuario_id,
            ":publicacion_id" => $publicacion_id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function toggle($usuario_id, $publicacion_id) {
        if($this->usuarioDioLike($usuario_id, $publicacion_id)) {
            $sql = "DELETE FROM likes 
                    WHERE usuario_id = :usuario_id 
                    AND publicacion_id = :publicacion_id";
        } else {
            $sql = "INSERT INTO likes(usuario_id, publicacion_id)
                    VALUES(:usuario_id, :publicacion_id)";
        }

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":usuario_id" => $usuario_id,
            ":publicacion_id" => $publicacion_id
        ]);
    }
}