<?php

session_start();

if(!isset($_SESSION["id"])) {
    header("Location: ../usuario/login.php");
    exit;
}

require_once "../../models/Categoria.php";
require_once "../../models/Publicacion.php";

$categoriaModel = new Categoria();
$publicacionModel = new Publicacion();

$categoria = $categoriaModel->buscarPorId($_GET["id"]);
$publicaciones = $publicacionModel->listarPorCategoria($_GET["id"]);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Comunidad - STRUCH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/img/favicon.png">
    <link rel="stylesheet" href="../../public/css/estilos.css">
</head>
<body>

<div class="form-page">

    <div class="volver-box">
        <a href="../home.php" class="btn-volver">⬅ Volver al inicio</a>
    </div>

    <?php if(!$categoria): ?>

        <div class="card">
            <h2>Comunidad no encontrada</h2>
        </div>

    <?php else: ?>

        <div class="page-header">
            <h2># <?php echo htmlspecialchars($categoria["nombre"]); ?></h2>
            <p><?php echo htmlspecialchars($categoria["descripcion"]); ?></p>
        </div>

        <div class="publicar-grande">
            <h2>Publicar en esta comunidad</h2>
            <p>Crea una publicación relacionada con este tema.</p>
            <a href="../publicacion/crear.php">✨ Publicar aquí</a>
        </div>

        <div class="section-title">
            <h2>Mensajes de esta comunidad</h2>
        </div>

        <?php if(empty($publicaciones)): ?>
            <div class="card">
                <p>No hay publicaciones en esta comunidad todavía.</p>
            </div>
        <?php endif; ?>

        <?php foreach($publicaciones as $post): ?>
            <div class="post-card">
                <h3>
                    <a href="../publicacion/ver.php?id=<?php echo $post["id"]; ?>">
                        <?php echo htmlspecialchars($post["titulo"]); ?>
                    </a>
                </h3>

                <p class="meta">
                    👤 <?php echo htmlspecialchars($post["usuario"]); ?>
                </p>

                <p class="contenido-post">
                    <?php echo htmlspecialchars($post["contenido"]); ?>
                </p>

                <?php if($post["imagen"]): ?>
                    <img class="post-img" src="../../public/uploads/<?php echo $post["imagen"]; ?>">
                <?php endif; ?>

                <div class="acciones">
                    <a href="../publicacion/ver.php?id=<?php echo $post["id"]; ?>">
                        💬 Ver comentarios
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>

<script src="../../public/js/animaciones.js"></script>
</body>
</html>