<?php

class Conexion {

    private $host = "";
    private $db = "";
    private $user = "";
    private $pass = "";

    public function conectar() {

        try {

            $conexion = new PDO(
                "mysql:host=$this->host;dbname=$this->db;charset=utf8mb4",
                $this->user,
                $this->pass
            );

            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conexion;

        } catch(PDOException $e) {

            die("Error de conexión: " . $e->getMessage());

        }

    }
}
?>