<?php
session_start();

if(!isset($_SESSION["id"])) {
    header("Location: usuario/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cargando - STRUCH</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../public/img/favicon.png">
    <link rel="stylesheet" href="../public/css/estilos.css">
</head>

<body class="pantalla-carga">

    <div class="loader-box">
        <h1 class="logo-carga">STRUCH 1.0</h1>

        <div class="barra-carga">
            <div class="barra-carga-interna"></div>
        </div>

        <p>Cargando tu inicio...</p>
    </div>

    <script>
        setTimeout(() => {
            document.body.classList.add("salir-carga");
        }, 1800);

        setTimeout(() => {
            window.location.href = "home.php";
        }, 2400);
    </script>

</body>
</html>