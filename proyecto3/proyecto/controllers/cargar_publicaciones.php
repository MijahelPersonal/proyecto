<?php
session_start();

if(!isset($_SESSION["id"])) {
    exit;
}

require_once "../models/Publicacion.php";
require_once "../models/Like.php";

$publicacionModel = new Publicacion();
$likeModel = new Like();

$publicaciones = $publicacionModel->listarPaginado(0, 10);

foreach($publicaciones as $post):
    $totalLikes = $likeModel->contar($post["id"]);
    $dioLike = $likeModel->usuarioDioLike($_SESSION["id"], $post["id"]);
?>

<div class="post-card">

    <h3>
        <a href="publicacion/ver.php?id=<?php echo $post["id"]; ?>">
            <?php echo htmlspecialchars($post["titulo"]); ?>
        </a>
    </h3>

    <p class="meta">
        # <?php echo htmlspecialchars($post["categoria"]); ?> |
        <a 
            href="usuario/perfil.php?id=<?php echo $post["usuario_id"]; ?>"
            class="link-usuario-limpio <?php echo ($post["usuario_rol"] == "admin") ? "nombre-admin-dorado" : ""; ?>"
        >
            <?php echo htmlspecialchars($post["usuario"]); ?>
        </a>
    </p>

    <p class="contenido-post">
        <?php echo htmlspecialchars($post["contenido"]); ?>
    </p>

    <?php if(!empty($post["imagen"])): ?>
        <img 
            class="post-img" 
            src="../public/uploads/<?php echo htmlspecialchars($post["imagen"]); ?>" 
            alt="Imagen de publicación"
        >
    <?php endif; ?>

    <div class="acciones">

        <form action="../controllers/LikeController.php" method="POST" class="form-like">
            <input type="hidden" name="publicacion_id" value="<?php echo $post["id"]; ?>">
            <input type="hidden" name="volver" value="../views/home.php">

            <button type="submit" class="btn-like <?php echo $dioLike ? 'like-activo' : ''; ?>">
                👍 <?php echo $totalLikes; ?>
            </button>
        </form>

        <a href="publicacion/ver.php?id=<?php echo $post["id"]; ?>">💬 Ver comentarios</a>

        <?php if($_SESSION["rol"] == "admin" || $_SESSION["id"] == $post["usuario_id"]): ?>
            <a href="../controllers/PublicacionController.php?eliminar=<?php echo $post["id"]; ?>" class="eliminar">
                🗑️ Eliminar
            </a>
        <?php endif; ?>
    </div>

</div>

<?php endforeach; ?>