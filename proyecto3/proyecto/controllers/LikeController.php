<?php

session_start();

header("Content-Type: application/json");

if(!isset($_SESSION["id"])) {
    echo json_encode(["error" => true]);
    exit;
}

require_once "../models/Like.php";

$like = new Like();

$publicacion_id = $_POST["publicacion_id"] ?? null;

if(!$publicacion_id) {
    echo json_encode(["error" => true]);
    exit;
}

$like->toggle($_SESSION["id"], $publicacion_id);

echo json_encode([
    "error" => false,
    "total" => $like->contar($publicacion_id),
    "activo" => $like->usuarioDioLike($_SESSION["id"], $publicacion_id) ? true : false
]);
exit;