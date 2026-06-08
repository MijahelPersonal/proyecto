<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

require_once "../../models/Usuario.php";

$usuarioModel = new Usuario();
$usuario = $usuarioModel->buscarPorId($_SESSION["id"]);

if (!$usuario) {
    header("Location: ../home.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar perfil - STRUCH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/img/favicon.png">
    <link rel="stylesheet" href="../../public/css/estilos.css">
</head>
<body>

<div class="form-page">

    <div class="volver-box">
        <a href="perfil.php?id=<?php echo $_SESSION["id"]; ?>" class="btn-volver">⬅ Volver al perfil</a>
    </div>

    <div class="card">
        <h1>Editar perfil</h1>

        <?php if(isset($_GET["error"])): ?>
            <p class="error">
                <?php
                    if($_GET["error"] == "nombre_vacio") echo "El nombre no puede estar vacío.";
                    if($_GET["error"] == "nombre_usado") echo "Ese nombre ya está en uso.";
                    if($_GET["error"] == "formato") echo "Formato no permitido. Usa JPG, PNG, JPEG o WEBP.";
                    if($_GET["error"] == "peso") echo "La imagen pesa demasiado. Máximo 2 MB.";
                    if($_GET["error"] == "subida") echo "Hubo un error al subir la imagen.";
                    if($_GET["error"] == "no_subio") echo "No se pudo guardar la imagen.";
                    if($_GET["error"] == "permisos") echo "La carpeta perfiles no tiene permisos de escritura.";
                ?>
            </p>
        <?php endif; ?>

        <form action="../../controllers/UsuarioController.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="actualizar_perfil" value="1">

            <div class="preview-perfil">
                <?php if(!empty($usuario["foto_perfil"]) && $usuario["foto_perfil"] != "default.png"): ?>
                    <img 
                        src="../../public/uploads/perfiles/<?php echo htmlspecialchars($usuario["foto_perfil"]); ?>" 
                        alt="Foto de perfil"
                    >
                <?php else: ?>
                    <div class="perfil-avatar">
                        <?php echo strtoupper(substr($usuario["nombre"], 0, 2)); ?>
                    </div>
                <?php endif; ?>
            </div>

            <label>Nombre de usuario</label>
            <input 
                type="text" 
                name="nombre" 
                value="<?php echo htmlspecialchars($usuario["nombre"]); ?>" 
                required
            >

            <label>Biografía</label>
            <textarea 
                name="biografia" 
                rows="5"
                placeholder="Escribe algo sobre ti..."
            ><?php echo htmlspecialchars($usuario["biografia"] ?? ""); ?></textarea>

            <label>Género opcional</label>
            <select name="genero">
                <option value="">No especificar</option>

                <option value="masculino" <?php if(($usuario["genero"] ?? "") == "masculino") echo "selected"; ?>>
                    Masculino
                </option>

                <option value="femenino" <?php if(($usuario["genero"] ?? "") == "femenino") echo "selected"; ?>>
                    Femenino
                </option>

                <option value="otro" <?php if(($usuario["genero"] ?? "") == "otro") echo "selected"; ?>>
                    Otro
                </option>
            </select>

            <label>Foto de perfil</label>
            <input type="file" name="foto_perfil" accept="image/png, image/jpeg, image/jpg, image/webp">

            <button type="submit" class="btn-principal">Guardar cambios</button>

        </form>
    </div>

</div>

<script src="../../public/js/animaciones.js"></script>
</body>
</html>