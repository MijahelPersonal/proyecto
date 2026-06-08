<?php

require_once __DIR__ . "/../config/conexion.php";

class Publicacion {

    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    public function crear($usuario_id, $categoria_id, $titulo, $contenido, $imagen) {
        $sql = "INSERT INTO publicaciones(usuario_id, categoria_id, titulo, contenido, imagen)
                VALUES(:usuario_id, :categoria_id, :titulo, :contenido, :imagen)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":usuario_id" => $usuario_id,
            ":categoria_id" => $categoria_id,
            ":titulo" => $titulo,
            ":contenido" => $contenido,
            ":imagen" => $imagen
        ]);
    }

    public function listarPaginado($inicio, $limite) {
        $sql = "SELECT p.*, 
                       u.nombre AS usuario,
                       u.rol AS usuario_rol,
                       c.nombre AS categoria
                FROM publicaciones p
                INNER JOIN usuarios u ON p.usuario_id = u.id
                INNER JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.id DESC
                LIMIT :inicio, :limite";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(":inicio", $inicio, PDO::PARAM_INT);
        $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorUsuario($usuario_id) {
        $sql = "SELECT p.*, c.nombre AS categoria
                FROM publicaciones p
                INNER JOIN categorias c ON p.categoria_id = c.id
                WHERE p.usuario_id = :usuario_id
                ORDER BY p.id DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":usuario_id" => $usuario_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorCategoria($categoria_id) {
        $sql = "SELECT p.*, 
                       u.nombre AS usuario,
                       u.rol AS usuario_rol,
                       c.nombre AS categoria
                FROM publicaciones p
                INNER JOIN usuarios u ON p.usuario_id = u.id
                INNER JOIN categorias c ON p.categoria_id = c.id
                WHERE p.categoria_id = :categoria_id
                ORDER BY p.id DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":categoria_id" => $categoria_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT p.*, 
                       u.nombre AS usuario,
                       u.rol AS usuario_rol,
                       c.nombre AS categoria
                FROM publicaciones p
                INNER JOIN usuarios u ON p.usuario_id = u.id
                INNER JOIN categorias c ON p.categoria_id = c.id
                WHERE p.id = :id
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminar($id) {
        $sqlComentarios = "DELETE FROM comentarios WHERE publicacion_id = :id";
        $stmtComentarios = $this->conexion->prepare($sqlComentarios);
        $stmtComentarios->execute([":id" => $id]);

        $sql = "DELETE FROM publicaciones WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([":id" => $id]);
    }

    public function contar() {
        $sql = "SELECT COUNT(*) AS total FROM publicaciones";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado["total"];
    }

    public function listarPorUsuarioPaginado($usuario_id, $inicio, $limite) {
        $sql = "SELECT p.*, c.nombre AS categoria
                FROM publicaciones p
                INNER JOIN categorias c ON p.categoria_id = c.id
                WHERE p.usuario_id = :usuario_id
                ORDER BY p.id DESC
                LIMIT :inicio, :limite";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->bindValue(":inicio", $inicio, PDO::PARAM_INT);
        $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPorUsuario($usuario_id) {
        $sql = "SELECT COUNT(*) AS total
                FROM publicaciones
                WHERE usuario_id = :usuario_id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":usuario_id" => $usuario_id]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado["total"];
    }

    public function actualizar($id, $categoria_id, $titulo, $contenido, $imagen = null) {
        if($imagen) {
            $sql = "UPDATE publicaciones
                    SET categoria_id = :categoria_id,
                        titulo = :titulo,
                        contenido = :contenido,
                        imagen = :imagen
                    WHERE id = :id";

            $stmt = $this->conexion->prepare($sql);

            return $stmt->execute([
                ":categoria_id" => $categoria_id,
                ":titulo" => $titulo,
                ":contenido" => $contenido,
                ":imagen" => $imagen,
                ":id" => $id
            ]);
        } else {
            $sql = "UPDATE publicaciones
                    SET categoria_id = :categoria_id,
                        titulo = :titulo,
                        contenido = :contenido
                    WHERE id = :id";

            $stmt = $this->conexion->prepare($sql);

            return $stmt->execute([
                ":categoria_id" => $categoria_id,
                ":titulo" => $titulo,
                ":contenido" => $contenido,
                ":id" => $id
            ]);
        }
    }
}