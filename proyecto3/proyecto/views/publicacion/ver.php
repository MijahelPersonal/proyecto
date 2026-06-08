<?php

session_start();

if(!isset($_SESSION["id"])) {
    header("Location: ../usuario/login.php");
    exit;
}

require_once "../../models/Publicacion.php";
require_once "../../models/Comentario.php";
require_once "../../models/Like.php";

$publicacionModel = new Publicacion();
$comentarioModel = new Comentario();
$likeModel = new Like();

if(!isset($_GET["id"])) {
    header("Location: ../home.php");
    exit;
}

$post = $publicacionModel->buscarPorId($_GET["id"]);

if($post) {
    $comentarios = $comentarioModel->listarPorPublicacion($_GET["id"]);
} else {
    $comentarios = [];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Publicación - STRUCH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/img/favicon.png">
    <link rel="stylesheet" href="../../public/css/estilos.css">
</head>
<body>

<div class="form-page">

    <div class="volver-box">
        <a href="../home.php" class="btn-volver">⬅ Volver al inicio</a>
    </div>

    <?php if(!$post): ?>

        <div class="card">
            <h2>Publicación no encontrada</h2>
        </div>

    <?php else: ?>

        <div class="post-card">
            <h2><?php echo htmlspecialchars($post["titulo"]); ?></h2>

            <p class="meta">
                # <?php echo htmlspecialchars($post["categoria"]); ?> |
                <span class="<?php echo ($post["usuario_rol"] == "admin") ? "nombre-admin-dorado" : ""; ?>">
                    <?php echo htmlspecialchars($post["usuario"]); ?>
                </span>
            </p>

            <p class="contenido-post">
                <?php echo htmlspecialchars($post["contenido"]); ?>
            </p>

            <?php if(!empty($post["imagen"])): ?>
                <img 
                    class="post-img grande" 
                    src="../../public/uploads/<?php echo htmlspecialchars($post["imagen"]); ?>"
                    alt="Imagen de publicación"
                >
            <?php endif; ?>

            <div class="acciones">
                <form action="../../controllers/LikeController.php" method="POST" class="form-like">
                    <input type="hidden" name="publicacion_id" value="<?php echo $post["id"]; ?>">
                    <input type="hidden" name="volver" value="../views/publicacion/ver.php?id=<?php echo $post["id"]; ?>">

                    <button type="submit" class="btn-like">
                        <?php echo $likeModel->usuarioDioLike($_SESSION["id"], $post["id"]) ? "❤️" : "🤍"; ?>
                        <?php echo $likeModel->contar($post["id"]); ?>
                    </button>
                </form>

                <?php if($_SESSION["rol"] == "admin" || $_SESSION["id"] == $post["usuario_id"]): ?>
                    <a href="editar.php?id=<?php echo $post["id"]; ?>">✏️ Editar publicación</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="section-title">
            <h2>💬 Comentarios</h2>
        </div>

        <?php if(empty($comentarios)): ?>
            <div class="card">
                <p>No hay comentarios todavía.</p>
            </div>
        <?php endif; ?>

        <?php foreach($comentarios as $com): ?>
            <div class="comentario">
                <strong class="<?php echo ($com["usuario_rol"] == "admin") ? "nombre-admin-dorado" : ""; ?>">
                    <?php echo htmlspecialchars($com["usuario"]); ?>
                </strong>

                <p><?php echo htmlspecialchars($com["comentario"]); ?></p>
            </div>
        <?php endforeach; ?>

        <form class="form-card" action="../../controllers/ComentarioController.php" method="POST">
            <input type="hidden" name="publicacion_id" value="<?php echo $post["id"]; ?>">

            <textarea name="comentario" placeholder="Escribe un comentario..." required></textarea>

            <button type="submit" name="crear_comentario">Comentar</button>
        </form>

    <?php endif; ?>

</div>

<script src="../../public/js/animaciones.js"></script>
</body>
</html>