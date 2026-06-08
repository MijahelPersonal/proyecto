<!DOCTYPE html>
<html>
<head>
    <title>Registro - STRUCH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/img/favicon.png">
    <link rel="stylesheet" href="../../public/css/estilos.css">
</head>
<body class="auth-body">

<div class="auth-layout">

    <section class="auth-brand">
        <h1 class="auth-logo">STRUCH</h1>
        <p class="auth-frase">
            Crea tu cuenta y empieza a compartir ideas, imágenes y publicaciones.
        </p>
    </section>

    <section class="auth-panel">
        <div class="auth-card">
            <h2>Crear cuenta</h2>

            <?php if(isset($_GET["error"]) && $_GET["error"] == "nombre"): ?>
                <div class="mensaje-error">
                    Ese nombre de usuario ya existe. Usa otro diferente.
                </div>
            <?php endif; ?>

            <?php if(isset($_GET["error"]) && $_GET["error"] == "correo"): ?>
                <div class="mensaje-error">
                    Ese correo ya está registrado.
                </div>
            <?php endif; ?>

            <form action="../../controllers/UsuarioController.php" method="POST">
                <input type="text" name="nombre" placeholder="Nombre de usuario único" required>
                <input type="email" name="correo" placeholder="Correo electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="registrar">Registrarse</button>
            </form>

            <p class="auth-link">
                ¿Ya tienes cuenta?
                <a href="login.php">Iniciar sesión</a>
            </p>
        </div>
    </section>

</div>

<script src="../../public/js/animaciones.js"></script>
</body>
</html>