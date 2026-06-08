<?php
session_start();

if(!isset($_SESSION["id"])) {
    header("Location: usuario/login.php");
    exit;
}

require_once "../models/Usuario.php";
require_once "../models/Categoria.php";
require_once "../models/Publicacion.php";
require_once "../models/Like.php";

$usuarioModel = new Usuario();
$categoriaModel = new Categoria();
$publicacionModel = new Publicacion();
$likeModel = new Like();

$usuarios = $usuarioModel->listar();
$categorias = $categoriaModel->listar();

$pagina = isset($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;
if($pagina < 1) {
    $pagina = 1;
}

$limite = 10;
$inicio = ($pagina - 1) * $limite;

$publicaciones = $publicacionModel->listarPaginado($inicio, $limite);

$totalUsuarios = $usuarioModel->contar();
$totalCategorias = $categoriaModel->contar();
$totalPublicaciones = $publicacionModel->contar();
$totalPaginas = ceil($totalPublicaciones / $limite);
?>

<!DOCTYPE html>
<html>
<head>
    <title>STRUCH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../public/img/favicon.png">
    <link rel="stylesheet" href="../public/css/estilos.css">
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <h1 class="logo">STRUCH</h1>
        <p class="slogan">Web creada por Mijahel.</p>

        <nav class="menu">
            <a href="home.php">🏠 Inicio</a>
            <a href="usuario/perfil.php?id=<?php echo $_SESSION["id"]; ?>">👤 Mi perfil</a>
            <a href="usuario/editar_perfil.php">✏️ Editar perfil</a>
            <a href="categoria/index.php">🏷️ Comunidades</a>
            <a href="publicacion/crear.php">📝 Crear publicación</a>
        </nav>

        <a href="../logout.php" class="btn-publicar">🚪 Cerrar sesión</a>
    </aside>

    <main class="contenido">

        <h2 class="titulo-seccion">👥 Usuarios registrados</h2>

        <div class="buscador-usuarios-box">
            <input 
                type="text" 
                id="buscarUsuario" 
                placeholder="🔍 Buscar usuario..."
                autocomplete="off"
            >

            <div id="resultadosUsuarios" class="resultados-usuarios"></div>
        </div>

        <div class="usuarios-wrapper">
            <button type="button" class="flecha-usuarios" onclick="moverUsuarios(-1)">‹</button>

            <div class="usuarios-carrusel" id="usuariosCarrusel">
                <?php foreach($usuarios as $user): ?>
                    <a href="usuario/perfil.php?id=<?php echo $user["id"]; ?>" class="usuario-card">

                        <div class="usuario-burbuja">
                            <?php if(!empty($user["foto_perfil"]) && $user["foto_perfil"] != "default.png"): ?>
                                <img 
                                    src="../public/uploads/perfiles/<?php echo htmlspecialchars($user["foto_perfil"]); ?>" 
                                    class="mini-foto-usuario"
                                    alt="Foto de perfil"
                                >
                            <?php else: ?>
                                <?php echo strtoupper(substr($user["nombre"], 0, 2)); ?>
                            <?php endif; ?>
                        </div>

                        <span class="<?php echo ($user["rol"] == "admin") ? "nombre-admin-dorado" : ""; ?>">
                            <?php echo htmlspecialchars($user["nombre"]); ?>
                        </span>

                        <?php if($user["rol"] == "admin"): ?>
                            <small class="mini-admin">admin</small>
                        <?php endif; ?>

                    </a>
                <?php endforeach; ?>
            </div>

            <button type="button" class="flecha-usuarios" onclick="moverUsuarios(1)">›</button>
        </div>

        <section class="publicar-grande">
            <h2>¿Qué quieres compartir hoy?</h2>
            <p>Publica una idea, imagen, debate o comentario para la comunidad.</p>
            <a href="publicacion/crear.php">✨ Publica aquí</a>
        </section>

        <h2 class="titulo-seccion">🔥 Publicaciones recientes</h2>

        <div id="contenedor-publicaciones">

            <?php if(empty($publicaciones)): ?>
                <div class="post-card">
                    <p>No hay publicaciones todavía.</p>
                </div>
            <?php endif; ?>

            <?php foreach($publicaciones as $post): ?>
                <?php
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

                        <button 
                            type="button" 
                            class="btn-like <?php echo $dioLike ? 'like-activo' : ''; ?>"
                            data-id="<?php echo $post["id"]; ?>"
                        >
                            👍 <span><?php echo $totalLikes; ?></span>
                        </button>

                        <a href="publicacion/ver.php?id=<?php echo $post["id"]; ?>">💬 Ver comentarios</a>

                        <?php if($_SESSION["rol"] == "admin" || $_SESSION["id"] == $post["usuario_id"]): ?>
                            <a href="../controllers/PublicacionController.php?eliminar=<?php echo $post["id"]; ?>" class="eliminar">
                                🗑️ Eliminar
                            </a>
                        <?php endif; ?>

                    </div>

                </div>
            <?php endforeach; ?>

        </div>

        <?php if($totalPaginas > 1): ?>
            <div class="paginacion">
                <?php if($pagina > 1): ?>
                    <a href="home.php?pagina=<?php echo $pagina - 1; ?>">‹</a>
                <?php endif; ?>

                <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
                    <a 
                        href="home.php?pagina=<?php echo $i; ?>" 
                        class="<?php echo ($pagina == $i) ? 'activo' : ''; ?>"
                    >
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if($pagina < $totalPaginas): ?>
                    <a href="home.php?pagina=<?php echo $pagina + 1; ?>">›</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </main>

    <aside class="rightbar">

        <div class="panel">
            <h3>🌐 Comunidades</h3>

            <?php foreach($categorias as $cat): ?>
                <a href="categoria/ver.php?id=<?php echo $cat["id"]; ?>" class="panel-link">
                    # <?php echo htmlspecialchars($cat["nombre"]); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="panel">
            <h3>📊 Estadísticas</h3>

            <div class="stat-line">
                <span>👥 Usuarios</span>
                <strong><?php echo $totalUsuarios; ?></strong>
            </div>

            <div class="stat-line">
                <span>🏷️ Comunidades</span>
                <strong><?php echo $totalCategorias; ?></strong>
            </div>

            <div class="stat-line">
                <span>📝 Publicaciones</span>
                <strong><?php echo $totalPublicaciones; ?></strong>
            </div>
        </div>

    </aside>

</div>

<script src="../public/js/animaciones.js"></script>

<script>
function moverUsuarios(direccion) {
    const carrusel = document.getElementById("usuariosCarrusel");

    let distancia = 1050;

    if(window.innerWidth <= 768){
        distancia = 350;
    }

    carrusel.scrollBy({
        left: direccion * distancia,
        behavior: "smooth"
    });
}

const inputBuscarUsuario = document.getElementById("buscarUsuario");
const resultadosUsuarios = document.getElementById("resultadosUsuarios");

if(inputBuscarUsuario) {
    inputBuscarUsuario.addEventListener("keyup", function() {
        const texto = this.value.trim();

        if(texto.length < 2) {
            resultadosUsuarios.innerHTML = "";
            resultadosUsuarios.style.display = "none";
            return;
        }

        fetch("../controllers/buscar_usuarios.php?q=" + encodeURIComponent(texto))
            .then(response => response.text())
            .then(data => {
                resultadosUsuarios.innerHTML = data;
                resultadosUsuarios.style.display = "block";
            });
    });
}

document.addEventListener("click", function(e) {
    const boton = e.target.closest(".btn-like");

    if(!boton) {
        return;
    }

    const id = boton.dataset.id;

    fetch("../controllers/LikeController.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "publicacion_id=" + encodeURIComponent(id)
    })
    .then(response => response.json())
    .then(data => {
        if(!data.error) {
            boton.querySelector("span").textContent = data.total;

            if(data.activo) {
                boton.classList.add("like-activo");
            } else {
                boton.classList.remove("like-activo");
            }
        }
    })
    .catch(error => console.log("Error en like:", error));
});

<?php if($pagina == 1): ?>
function actualizarPublicaciones() {
    fetch("../controllers/cargar_publicaciones.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("contenedor-publicaciones").innerHTML = data;
        })
        .catch(error => console.log("Error al actualizar publicaciones:", error));
}

setInterval(actualizarPublicaciones, 10000);
<?php endif; ?>
</script>

</body>
</html>