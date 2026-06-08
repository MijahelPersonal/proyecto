<?php

require_once "../../models/Usuario.php";
require_once "../../models/Publicacion.php";
require_once "../../models/Comentario.php";

$usuarioModel = new Usuario();
$publicacionModel = new Publicacion();
$comentarioModel = new Comentario();

$totalUsuarios = $usuarioModel->contar();
$totalPublicaciones = $publicacionModel->contar();
$totalComentarios = $comentarioModel->contar();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - STRUCH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/img/favicon.png">
    <link rel="stylesheet" href="../../public/css/estilos.css">
</head>
<body class="auth-body">

<div class="auth-layout">

    <section class="auth-brand">
        <h1 class="auth-logo">STRUCH</h1>

        <p class="auth-frase">
            Conecta, comparte y descubre conversaciones en una comunidad moderna.
        </p>

        <div class="auth-stats">
            <div>
                <strong><?php echo $totalUsuarios; ?></strong>
                <span>Usuarios</span>
            </div>

            <div>
                <strong><?php echo $totalPublicaciones; ?></strong>
                <span>Publicaciones</span>
            </div>

            <div>
                <strong><?php echo $totalComentarios; ?></strong>
                <span>Comentarios</span>
            </div>
        </div>
    </section>

    <section class="auth-panel">
        <div class="auth-card">
            <h2>Bienvenido de nuevo</h2>

            <?php if(isset($_GET["error"])): ?>
                <div class="mensaje-error">
                    Correo o contraseña incorrectos.
                </div>
            <?php endif; ?>

            <?php if(isset($_GET["registro"])): ?>
                <div class="mensaje-exito">
                    Cuenta creada correctamente. Ahora inicia sesión.
                </div>
            <?php endif; ?>

            <form action="../../controllers/UsuarioController.php" method="POST">
                <input type="email" name="correo" placeholder="Correo electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="login">Iniciar sesión</button>
            </form>

            <p class="auth-link">
                ¿No tienes cuenta?
                <a href="registro.php">Crear cuenta</a>
            </p>
        </div>
    </section>

</div>

<script src="../../public/js/animaciones.js"></script>
</body>
</html>
