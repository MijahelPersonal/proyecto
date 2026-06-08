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
    <title>Comunidades - STRUCH</title>
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
        <h2>🏷️ Crea tu propia Comunidad</h2>
        <p>Organiza conversaciones por temas como fútbol, juegos, estudios o memes.</p>
    </div>

    <form class="form-card" action="../../controllers/CategoriaController.php" method="POST">
        <input type="text" name="nombre" placeholder="Nombre de la comunidad" required>
        <textarea name="descripcion" placeholder="Descripción de la comunidad"></textarea>
        <button type="submit" name="crear_categoria">Crear comunidad</button>
    </form>

    <div class="section-title">
        <h2>Comunidades creadas por usuarios</h2>
    </div>

    <div class="categoria-lista">
        <?php if(empty($categorias)): ?>
            <div class="card">
                <p>No hay comunidades creadas todavía.</p>
            </div>
        <?php endif; ?>

        <?php foreach($categorias as $cat): ?>
            <a class="categoria-card" href="ver.php?id=<?php echo $cat["id"]; ?>">
                <h3># <?php echo htmlspecialchars($cat["nombre"]); ?></h3>
                <p><?php echo htmlspecialchars($cat["descripcion"]); ?></p>
                <span>Entrar a la comunidad →</span>
            </a>
        <?php endforeach; ?>
    </div>

</div>

<script src="../../public/js/animaciones.js"></script>
</body>
</html>