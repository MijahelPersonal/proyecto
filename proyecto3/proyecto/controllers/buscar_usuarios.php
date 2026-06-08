<?php
session_start();

if(!isset($_SESSION["id"])) {
    exit;
}

require_once "../models/Usuario.php";

$usuarioModel = new Usuario();

$q = isset($_GET["q"]) ? trim($_GET["q"]) : "";

if($q == "") {
    exit;
}

$usuarios = $usuarioModel->buscarUsuarios($q);

foreach($usuarios as $user):
?>

<a href="usuario/perfil.php?id=<?php echo $user["id"]; ?>" class="resultado-usuario">

    <div class="resultado-avatar">
        <?php if(!empty($user["foto_perfil"]) && $user["foto_perfil"] != "default.png"): ?>
            <img src="../public/uploads/perfiles/<?php echo htmlspecialchars($user["foto_perfil"]); ?>">
        <?php else: ?>
            <?php echo strtoupper(substr($user["nombre"], 0, 2)); ?>
        <?php endif; ?>
    </div>

    <div>
        <strong class="<?php echo ($user["rol"] == "admin") ? "nombre-admin-dorado" : ""; ?>">
            <?php echo htmlspecialchars($user["nombre"]); ?>
        </strong>

        <small>
            <?php echo ($user["rol"] == "admin") ? "Administrador" : "Usuario"; ?>
        </small>
    </div>

</a>

<?php endforeach; ?>