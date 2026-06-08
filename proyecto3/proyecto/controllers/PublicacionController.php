<?php

session_start();

require_once "../models/Publicacion.php";

$publicacion = new Publicacion();

if(isset($_POST["crear_publicacion"])) {

    if(!isset($_SESSION["id"])) {
        header("Location: ../views/usuario/login.php");
        exit;
    }

    $usuario_id = $_SESSION["id"];
    $categoria_id = $_POST["categoria_id"];
    $titulo = trim($_POST["titulo"]);
    $contenido = trim($_POST["contenido"]);
    $imagen = null;

    if($titulo == "" || $contenido == "" || $categoria_id == "") {
        header("Location: ../views/publicacion/crear.php?error=campos");
        exit;
    }

    if(!empty($_FILES["imagen"]["name"])) {

        $archivo = $_FILES["imagen"];
        $nombreOriginal = $archivo["name"];
        $tmp = $archivo["tmp_name"];
        $error = $archivo["error"];
        $size = $archivo["size"];

        $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
        $permitidas = ["jpg", "jpeg", "png", "gif", "webp"];

        if($error === 0) {

            if(!in_array($extension, $permitidas)) {
                header("Location: ../views/publicacion/crear.php?error=formato");
                exit;
            }

            if($size > 5 * 1024 * 1024) {
                header("Location: ../views/publicacion/crear.php?error=peso");
                exit;
            }

            $imagen = "post_" . $usuario_id . "_" . time() . "." . $extension;
            $rutaDestino = "../public/uploads/" . $imagen;

            if(!move_uploaded_file($tmp, $rutaDestino)) {
                header("Location: ../views/publicacion/crear.php?error=subida");
                exit;
            }
        }
    }

    $publicacion->crear(
        $usuario_id,
        $categoria_id,
        $titulo,
        $contenido,
        $imagen
    );

    header("Location: ../views/home.php");
    exit;
}

if(isset($_GET["eliminar"])) {

    if(!isset($_SESSION["id"])) {
        header("Location: ../views/usuario/login.php");
        exit;
    }

    $id = $_GET["eliminar"];

    $publicacion->eliminar($id);

    header("Location: ../views/home.php");
    exit;
}
if(isset($_POST["editar_publicacion"])) {

    if(!isset($_SESSION["id"])) {
        header("Location: ../views/usuario/login.php");
        exit;
    }

    $id = $_POST["id"];
    $categoria_id = $_POST["categoria_id"];
    $titulo = trim($_POST["titulo"]);
    $contenido = trim($_POST["contenido"]);

    $post = $publicacion->buscarPorId($id);

    if(!$post) {
        header("Location: ../views/home.php");
        exit;
    }

    if($_SESSION["rol"] != "admin" && $_SESSION["id"] != $post["usuario_id"]) {
        header("Location: ../views/home.php");
        exit;
    }

    $imagen = null;

    if(!empty($_FILES["imagen"]["name"])) {

        $archivo = $_FILES["imagen"];
        $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
        $permitidas = ["jpg", "jpeg", "png", "gif", "webp"];

        if(!in_array($extension, $permitidas)) {
            header("Location: ../views/publicacion/editar.php?id=$id&error=formato");
            exit;
        }

        if($archivo["size"] > 5 * 1024 * 1024) {
            header("Location: ../views/publicacion/editar.php?id=$id&error=peso");
            exit;
        }

        $imagen = "post_" . $_SESSION["id"] . "_" . time() . "." . $extension;
        move_uploaded_file($archivo["tmp_name"], "../public/uploads/" . $imagen);
    }

    $publicacion->actualizar($id, $categoria_id, $titulo, $contenido, $imagen);

    header("Location: ../views/publicacion/ver.php?id=" . $id . "&ok=editado");
    exit;
}

header("Location: ../views/home.php");
exit;