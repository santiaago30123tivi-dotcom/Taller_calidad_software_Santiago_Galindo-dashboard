<?php
require "auth.php";
require "db.php";
require "funciones.php";

$id = (int) ($_GET["id"] ?? $_POST["id"] ?? 0);

$stmt = $pdo->prepare("SELECT id, username, email FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header("Location: usuarios.php");
    exit;
}

$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? ""; // opcional al editar

    $errorUser = validarUsername($username);
    $errorEmail = validarEmail($email);

    if ($errorUser) $errores[] = $errorUser;
    if ($errorEmail) $errores[] = $errorEmail;

    // Solo se valida la contraseña si el usuario escribió una nueva
    if ($password !== "") {
        $errorPass = validarPassword($password);
        if ($errorPass) $errores[] = $errorPass;
    }

    if (empty($errores)) {
        // Verificar que el username/email no choque con OTRO usuario distinto a este
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$username, $email, $id]);

        if ($stmt->fetch()) {
            $errores[] = "Ya existe otro usuario con ese nombre o correo.";
        } else {
            if ($password !== "") {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET username = ?, email = ?, password_hash = ? WHERE id = ?");
                $stmt->execute([$username, $email, $hash, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET username = ?, email = ? WHERE id = ?");
                $stmt->execute([$username, $email, $id]);
            }
            header("Location: usuarios.php?mensaje=editado");
            exit;
        }
    }

    // Para volver a mostrar lo que el usuario escribió si hubo error
    $usuario["username"] = $username;
    $usuario["email"] = $email;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar usuario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Editar usuario</h1>

        <?php if (!empty($errores)): ?>
            <div class="mensaje-error">
                <?php foreach ($errores as $err): ?>
                    <div><?= htmlspecialchars($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="usuario_editar.php">
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

            <label for="username">Nombre de usuario</label>
            <input type="text" id="username" name="username"
                   value="<?= htmlspecialchars($usuario['username']) ?>" required minlength="4">

            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email"
                   value="<?= htmlspecialchars($usuario['email']) ?>" required>

            <label for="password">Nueva contraseña</label>
            <input type="password" id="password" name="password" minlength="8">
            <small>Déjalo en blanco si no quieres cambiar la contraseña.</small>

            <button type="submit">Guardar cambios</button>
        </form>

        <div class="enlace">
            <a href="usuarios.php">&larr; Cancelar y volver al listado</a>
        </div>
    </div>
</body>
</html>