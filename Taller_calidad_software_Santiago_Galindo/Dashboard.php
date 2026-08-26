
<?php
require "auth.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel principal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="contenedor bienvenida">
        <h1>¡Bienvenido, <?= htmlspecialchars($_SESSION["username"]) ?>!</h1>
        <p>Has iniciado sesión correctamente.</p>
        <a class="btn-primario" href="usuarios.php">Gestionar usuarios</a>
        <a class="btn-salir" href="logout.php">Cerrar sesión</a>
    </div>
</body>
</html>

