<?php

session_start();

if(!isset($_SESSION["id"])) {
    header("Location: ../usuario/login.php");
    exit;
}

require_once "../../models/Categoria.php";

$categoria = new Categoria();
$categorias = $categoria->listar();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Crear publicación - STRUCH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/img/favicon.png">
    <link rel="stylesheet" href="../../public/css/estilos.css">
</head>
<body>

<div class="form-page">

    <div class="volver-box">
        <a href="../home.php" class="btn-volver">⬅ Volver al inicio</a>
    </div>

    <div class="page-header">
        <h2>📝 Crear publicación</h2>
        <p>Comparte un mensaje, imagen, idea o debate con la comunidad.</p>
    </div>

    <form class="form-card" action="../../controllers/PublicacionController.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="titulo" placeholder="Título de la publicación" required>

        <textarea name="contenido" placeholder="Escribe tu publicación..." required></textarea>

        <select name="categoria_id" required>
            <option value="">Selecciona una comunidad</option>

            <?php foreach($categorias as $cat): ?>
                <option value="<?php echo $cat["id"]; ?>">
                    # <?php echo htmlspecialchars($cat["nombre"]); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label class="file-label">
            📎 Subir imagen o GIF
            <input type="file" name="imagen" accept="image/*,.gif">
        </label>

        <button type="submit" name="crear_publicacion">Publicar ahora</button>
    </form>

</div>

<script src="../../public/js/animaciones.js"></script>
</body>
</html>