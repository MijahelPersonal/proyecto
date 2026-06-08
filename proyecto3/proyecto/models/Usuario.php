<?php

require_once __DIR__ . "/../config/conexion.php";

class Usuario {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    public function existeNombre($nombre, $id = null) {
        if ($id) {
            $sql = "SELECT id FROM usuarios WHERE nombre = :nombre AND id != :id LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                ":nombre" => $nombre,
                ":id" => $id
            ]);
        } else {
            $sql = "SELECT id FROM usuarios WHERE nombre = :nombre LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([":nombre" => $nombre]);
        }

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeCorreo($correo) {
        $sql = "SELECT id FROM usuarios WHERE correo = :correo LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":correo" => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar($nombre, $correo, $password) {
        $sql = "INSERT INTO usuarios(nombre, correo, password)
                VALUES(:nombre, :correo, :password)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":nombre" => $nombre,
            ":correo" => $correo,
            ":password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function login($correo, $password) {
        $sql = "SELECT * FROM usuarios WHERE correo = :correo LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":correo" => $correo]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario["password"])) {
            return $usuario;
        }

        return false;
    }

    public function listar() {
        $sql = "SELECT id, nombre, rol, foto_perfil
                FROM usuarios
                ORDER BY id DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarUsuarios($q) {
        $sql = "SELECT id, nombre, rol, foto_perfil
                FROM usuarios
                WHERE nombre LIKE :q
                ORDER BY nombre ASC
                LIMIT 20";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ":q" => "%" . $q . "%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT id, nombre, correo, rol, fecha_registro, foto_perfil, biografia, genero
                FROM usuarios
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPerfil($id, $nombre, $biografia, $genero) {
        $sql = "UPDATE usuarios
                SET nombre = :nombre,
                    biografia = :biografia,
                    genero = :genero
                WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":nombre" => $nombre,
            ":biografia" => $biografia,
            ":genero" => $genero,
            ":id" => $id
        ]);
    }

    public function actualizarFoto($id, $foto_perfil) {
        $sql = "UPDATE usuarios
                SET foto_perfil = :foto_perfil
                WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":foto_perfil" => $foto_perfil,
            ":id" => $id
        ]);
    }

    public function contar() {
        $sql = "SELECT COUNT(*) AS total FROM usuarios";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado["total"];
    }

    public function contarPublicaciones($id) {
        $sql = "SELECT COUNT(*) AS total
                FROM publicaciones
                WHERE usuario_id = :id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":id" => $id]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado["total"];
    }

    public function contarComentarios($id) {
        $sql = "SELECT COUNT(*) AS total
                FROM comentarios
                WHERE usuario_id = :id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":id" => $id]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado["total"];
    }
}