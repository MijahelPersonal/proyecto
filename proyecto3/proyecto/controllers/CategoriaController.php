<?php

session_start();

require_once "../models/Categoria.php";

if(!isset($_SESSION["id"])) {
    header("Location: ../views/usuario/login.php");
    exit;
}

$categoria = new Categoria();

if(isset($_POST["crear_categoria"])) {

    $categoria->crear(
        $_POST["nombre"],
        $_POST["descripcion"]
    );

    header("Location: ../views/categoria/index.php");
    exit;
}