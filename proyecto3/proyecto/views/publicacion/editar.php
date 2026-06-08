<?php
session_start();

if(!isset($_SESSION["id"])) {
    header("Location: ../usuario/login.php");
    exit;
}

require_once "../../models/Publicacion.php";
require_once "../../models/Categoria.php";

$publicacionModel = new Publicacion();
$categoriaModel = new Categoria();

if(!isset($_GET["id"])) {
    header("Location: ../home.php");
    exit;
}

$post = $publicacionModel->buscarPorId($_GET["id"]);

if(!$post) {
    header("Location: ../home.php");
    exit;
}

if($_SESSION["rol"] != "admin" && $_SESSION["id"] != $post["usuario_id"]) {
    header("Location: ../home.php");
    exit;
}

$categorias = $categoriaModel->listar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar publicación - STRUCH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/img/favicon.png">
    <link rel="stylesheet" href="../../public/css/estilos.css">
</head>
<body>

<div class="form-page">

    <div class="volver-box">
        <a href="ver.php?id=<?php echo $post["id"]; ?>" class="btn-volver">⬅ Volver</a>
    </div>

    <div class="page-header">
        <h2>✏️ Editar publicación</h2>
        <p>Modifica tu publicación.</p>
    </div>

    <form class="form-card" action="../../controllers/PublicacionController.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?php echo $post["id"]; ?>">

        <input 
            type="text" 
            name="titulo" 
            value="<?php echo htmlspecialchars($post["titulo"]); ?>" 
            required
        >

        <textarea name="contenido" required><?php echo htmlspecialchars($post["contenido"]); ?></textarea>

        <select name="categoria_id" required>
            <?php foreach($categorias as $cat): ?>
                <option value="<?php echo $cat["id"]; ?>" <?php if($cat["id"] == $post["categoria_id"]) echo "selected"; ?>>
                    # <?php echo htmlspecialchars($cat["nombre"]); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if(!empty($post["imagen"])): ?>
            <p>Imagen actual:</p>
            <img class="post-img" src="../../public/uploads/<?php echo htmlspecialchars($post["imagen"]); ?>">
        <?php endif; ?>

        <label class="file-label">
            📎 Cambiar imagen
            <input type="file" name="imagen" accept="image/*,.gif">
        </label>

        <button type="submit" name="editar_publicacion">Guardar cambios</button>
    </form>

</div>

</body>
</html>