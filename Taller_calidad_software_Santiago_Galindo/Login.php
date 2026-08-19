<?php

session_start();
require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    // Validar que los campos estén completos
    if ($username === "" || $password === "") {

        $error = "Debes completar usuario y contraseña.";

    } else {

        // Buscar el usuario en la base de datos
        $stmt = $pdo->prepare(
            "SELECT id, username, password_hash
             FROM usuarios
             WHERE username = ?
             LIMIT 1"
        );

        $stmt->execute([$username]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Comprobar la contraseña
        if ($usuario && $password === $usuario["password_hash"]) {

            // Regenerar el ID de sesión por seguridad
            session_regenerate_id(true);

            // Guardar información del usuario en la sesión
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["username"] = $usuario["username"];

            // Ir al panel del usuario
            header("Location: dashboard.php");
            exit;

        } else {

            $error = "Usuario o contraseña incorrectos.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Iniciar sesión</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="contenedor">

        <h1>Iniciar sesión</h1>

        <?php if ($error !== ""): ?>

            <div class="mensaje-error">
                <?= htmlspecialchars(
                    $error,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </div>

        <?php endif; ?>


        <form method="POST" action="login.php">

            <label for="username">
                Nombre de usuario
            </label>

            <input
                type="text"
                id="username"
                name="username"
                value="<?= htmlspecialchars(
                    $_POST["username"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
                required
                autocomplete="username"
            >


            <label for="password">
                Contraseña
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
            >


            <button type="submit">
                Entrar
            </button>

        </form>


        <div class="enlace">

            ¿No tienes cuenta?

            <a href="registro.php">
                Regístrate
            </a>

        </div>

    </div>

</body>

</html>