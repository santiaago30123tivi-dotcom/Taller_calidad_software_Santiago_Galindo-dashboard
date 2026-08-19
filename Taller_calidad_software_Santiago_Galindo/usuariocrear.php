<?php
require "auth.php";
require "db.php";
require "funciones.php";

$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $errorUser = validarUsername($username);
    $errorEmail = validarEmail($email);
    $errorPass = validarPassword($password);

    if ($errorUser) $errores[] = $errorUser;
    if ($errorEmail) $errores[] = $errorEmail;
    if ($errorPass) $errores[] = $errorPass;

    if (empty($errores)) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            $errores[] = "Ya existe un usuario con ese nombre o correo.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);
            header("Location: usuarios.php?mensaje=creado");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo usuario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Nuevo usuario</h1>

        <?php if (!empty($errores)): ?>
            <div class="mensaje-error">
                <?php foreach ($errores as $err): ?>
                    <div><?= htmlspecialchars($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="usuario_crear.php">
            <label for="username">Nombre de usuario</label>
            <input type="text" id="username" name="username"
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required minlength="4">

            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required minlength="8">
            <small>Mín. 8 caracteres, 1 mayúscula, 1 número y 1 carácter especial.</small>

            <button type="submit">Guardar</button>
        </form>

        <div class="enlace">
            <a href="usuarios.php">&larr; Cancelar y volver al listado</a>
        </div>
    </div>
</body>
</html>