<?php

session_start();

require_once "../models/Comentario.php";

if(!isset($_SESSION["id"])) {
    header("Location: ../views/usuario/login.php");
    exit;
}

$comentario = new Comentario();

if(isset($_POST["crear_comentario"])) {

    $comentario->crear(
        $_POST["publicacion_id"],
        $_SESSION["id"],
        $_POST["comentario"]
    );

    header("Location: ../views/publicacion/ver.php?id=" . $_POST["publicacion_id"]);
    exit;
}