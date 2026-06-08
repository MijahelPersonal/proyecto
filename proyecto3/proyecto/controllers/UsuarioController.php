<?php

session_start();

require_once "../models/Usuario.php";

$usuario = new Usuario();

if (isset($_POST["registrar"])) {

    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $password = $_POST["password"];

    if ($usuario->existeNombre($nombre)) {
        header("Location: ../views/usuario/registro.php?error=nombre");
        exit;
    }

    if ($usuario->existeCorreo($correo)) {
        header("Location: ../views/usuario/registro.php?error=correo");
        exit;
    }

    $usuario->registrar($nombre, $correo, $password);

    header("Location: ../views/usuario/login.php?registro=ok");
    exit;
}

if (isset($_POST["login"])) {

    $datos = $usuario->login(
        $_POST["correo"],
        $_POST["password"]
    );

    if ($datos) {
        $_SESSION["id"] = $datos["id"];
        $_SESSION["nombre"] = $datos["nombre"];
        $_SESSION["rol"] = $datos["rol"];

        header("Location: ../views/cargando.php");
        exit;
    } else {
        header("Location: ../views/usuario/login.php?error=1");
        exit;
    }
}

if (isset($_POST["actualizar_perfil"])) {

    if (!isset($_SESSION["id"])) {
        header("Location: ../views/usuario/login.php");
        exit;
    }

    $id = $_SESSION["id"];
    $nombre = trim($_POST["nombre"] ?? "");
    $biografia = trim($_POST["biografia"] ?? "");
    $genero = $_POST["genero"] ?? "";

    if ($nombre == "") {
        header("Location: ../views/usuario/editar_perfil.php?error=nombre_vacio");
        exit;
    }

    if ($usuario->existeNombre($nombre, $id)) {
        header("Location: ../views/usuario/editar_perfil.php?error=nombre_usado");
        exit;
    }

    $generosPermitidos = ["", "masculino", "femenino", "otro"];

    if (!in_array($genero, $generosPermitidos)) {
        $genero = "";
    }

    $usuario->actualizarPerfil($id, $nombre, $biografia, $genero);

    $_SESSION["nombre"] = $nombre;

    if (!empty($_FILES["foto_perfil"]["name"])) {

        $archivo = $_FILES["foto_perfil"];
        $nombreArchivo = $archivo["name"];
        $tmp = $archivo["tmp_name"];
        $error = $archivo["error"];
        $size = $archivo["size"];

        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
        $permitidas = ["jpg", "jpeg", "png", "webp"];

        if ($error !== 0) {
            header("Location: ../views/usuario/editar_perfil.php?error=subida");
            exit;
        }

        if (!in_array($extension, $permitidas)) {
            header("Location: ../views/usuario/editar_perfil.php?error=formato");
            exit;
        }

        if ($size > 2 * 1024 * 1024) {
            header("Location: ../views/usuario/editar_perfil.php?error=peso");
            exit;
        }

        $carpeta = "../public/uploads/perfiles/";

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $nuevoNombre = "user_" . $id . "_" . time() . "." . $extension;
        $ruta = $carpeta . $nuevoNombre;

        if (!move_uploaded_file($tmp, $ruta)) {
            header("Location: ../views/usuario/editar_perfil.php?error=no_subio");
            exit;
        }

        $usuario->actualizarFoto($id, $nuevoNombre);
    }

    header("Location: ../views/usuario/perfil.php?id=" . $id . "&ok=perfil");
    exit;
}