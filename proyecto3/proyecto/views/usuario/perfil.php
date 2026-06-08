<?php

session_start();

if(!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

require_once "../../models/Usuario.php";
require_once "../../models/Publicacion.php";

$usuarioModel = new Usuario();
$publicacionModel = new Publicacion();

if(!isset($_GET["id"])) {
    header("Location: ../home.php");
    exit;
}

$perfil = $usuarioModel->buscarPorId($_GET["id"]);

if(!$perfil) {
    header("Location: ../home.php");
    exit;
}

$totalPublicaciones = $usuarioModel->contarPublicaciones($perfil["id"]);
$totalComentarios = $usuarioModel->contarComentarios($perfil["id"]);

$pagina = isset($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;

if($pagina < 1) {
    $pagina = 1;
}

$limite = 5;
$inicio = ($pagina - 1) * $limite;

$publicaciones = $publicacionModel->listarPorUsuarioPaginado(
    $perfil["id"],
    $inicio,
    $limite
);

$totalPaginas = ceil($totalPublicaciones / $limite);

$fecha = "No disponible";

if(isset($perfil["fecha_registro"]) && $perfil["fecha_registro"] != "") {
    $fecha = date("d/m/Y", strtotime($perfil["fecha_registro"]));
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Perfil - STRUCH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/img/favicon.png">
    <link rel="stylesheet" href="../../public/css/estilos.css">
</head>
<body>

<div class="form-page">

    <div class="volver-box">
        <a href="../home.php" class="btn-volver">⬅ Volver al inicio</a>
    </div>

    <?php if(isset($_GET["ok"])): ?>
        <div class="card">
            <p>Perfil actualizado correctamente.</p>
        </div>
    <?php endif; ?>

    <div class="perfil-header">

        <div class="perfil-avatar">
            <?php if(!empty($perfil["foto_perfil"]) && $perfil["foto_perfil"] != "default.png"): ?>
                <img 
                    src="../../public/uploads/perfiles/<?php echo htmlspecialchars($perfil["foto_perfil"]); ?>" 
                    class="foto-perfil"
                    alt="Foto de perfil"
                >
            <?php else: ?>
                <?php echo strtoupper(substr($perfil["nombre"], 0, 2)); ?>
            <?php endif; ?>
        </div>

        <div>
            <h1><?php echo htmlspecialchars($perfil["nombre"]); ?></h1>

            <?php if($_SESSION["id"] == $perfil["id"]): ?>
                <a href="editar_perfil.php" class="btn-editar-perfil">✏️ Editar perfil</a>
            <?php endif; ?>

            <?php if($perfil["rol"] == "admin"): ?>
                <span class="perfil-rol admin">🛡️ Administrador</span>
            <?php else: ?>
                <span class="perfil-rol">👤 Usuario</span>
            <?php endif; ?>

            <p class="perfil-fecha">
                Miembro desde: <?php echo $fecha; ?>
            </p>

            <?php if(!empty($perfil["genero"])): ?>
                <p class="perfil-genero">
                    Género: <?php echo ucfirst(htmlspecialchars($perfil["genero"])); ?>
                </p>
            <?php endif; ?>

            <?php if(!empty($perfil["biografia"])): ?>
                <p class="perfil-bio">
                    <?php echo nl2br(htmlspecialchars($perfil["biografia"])); ?>
                </p>
            <?php else: ?>
                <p class="perfil-bio vacia">
                    Este usuario todavía no tiene biografía.
                </p>
            <?php endif; ?>
        </div>

    </div>

    <div class="perfil-stats">
        <div>
            <strong><?php echo $totalPublicaciones; ?></strong>
            <span>Publicaciones</span>
        </div>

        <div>
            <strong><?php echo $totalComentarios; ?></strong>
            <span>Comentarios</span>
        </div>
    </div>

    <div class="section-title">
        <h2>Publicaciones de <?php echo htmlspecialchars($perfil["nombre"]); ?></h2>
    </div>

    <?php if(empty($publicaciones)): ?>
        <div class="card">
            <p>Este usuario todavía no tiene publicaciones.</p>
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
                # <?php echo htmlspecialchars($post["categoria"]); ?>
            </p>

            <p class="contenido-post">
                <?php echo htmlspecialchars($post["contenido"]); ?>
            </p>

            <?php if(!empty($post["imagen"])): ?>
                <img 
                    class="post-img" 
                    src="../../public/uploads/<?php echo htmlspecialchars($post["imagen"]); ?>"
                    alt="Imagen de publicación"
                >
            <?php endif; ?>

            <div class="acciones">
                <a href="../publicacion/ver.php?id=<?php echo $post["id"]; ?>">
                    💬 Ver publicación
                </a>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if($totalPaginas > 1): ?>
        <div class="paginacion">
            <?php if($pagina > 1): ?>
                <a href="perfil.php?id=<?php echo $perfil["id"]; ?>&pagina=<?php echo $pagina - 1; ?>">‹</a>
            <?php endif; ?>

            <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
                <a 
                    href="perfil.php?id=<?php echo $perfil["id"]; ?>&pagina=<?php echo $i; ?>" 
                    class="<?php echo ($pagina == $i) ? 'activo' : ''; ?>"
                >
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if($pagina < $totalPaginas): ?>
                <a href="perfil.php?id=<?php echo $perfil["id"]; ?>&pagina=<?php echo $pagina + 1; ?>">›</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<script src="../../public/js/animaciones.js"></script>
</body>
</html>