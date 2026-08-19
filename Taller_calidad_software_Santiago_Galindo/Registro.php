
<?php

require "db.php";
require "funciones.php";

$errores = [];
$exito = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Obtener datos del formulario
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // Validar usuario, correo y contraseña
    $errorUser = validarUsername($username);
    $errorEmail = validarEmail($email);
    $errorPass = validarPassword($password);

    if ($errorUser) {
        $errores[] = $errorUser;
    }

    if ($errorEmail) {
        $errores[] = $errorEmail;
    }

    if ($errorPass) {
        $errores[] = $errorPass;
    }

    // Si no existen errores
    if (empty($errores)) {

        // Comprobar si ya existe el usuario o el correo
        $stmt = $pdo->prepare(
            "SELECT id
             FROM usuarios
             WHERE username = ? OR email = ?
             LIMIT 1"
        );

        $stmt->execute([
            $username,
            $email
        ]);

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {

            $errores[] = "Ya existe un usuario con ese nombre o correo.";

        } else {

            try {

                // Guardar la contraseña directamente, sin hash
                $stmt = $pdo->prepare(
                    "INSERT INTO usuarios
                    (username, email, password_hash)
                    VALUES (?, ?, ?)"
                );

                $stmt->execute([
                    $username,
                    $email,
                    $password
                ]);

                $exito = true;

            } catch (PDOException $e) {

                $errores[] = "Ocurrió un error al registrar el usuario.";
            }
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

    <title>Registro de usuario</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="contenedor">

        <h1>Crear cuenta</h1>

        <?php if ($exito): ?>

            <div class="mensaje-exito">

                ¡Usuario registrado con éxito!

                Ya puedes
                <a href="login.php">iniciar sesión</a>.

            </div>

        <?php else: ?>

            <?php if (!empty($errores)): ?>

                <div class="mensaje-error">

                    <?php foreach ($errores as $err): ?>

                        <div>
                            <?= htmlspecialchars(
                                $err,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                action="registro.php"
            >

                <label for="username">
                    Nombre de usuario
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?= htmlspecialchars(
                        $_POST['username'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                    minlength="4"
                    maxlength="50"
                    autocomplete="username"
                >


                <label for="email">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars(
                        $_POST['email'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                    maxlength="150"
                    autocomplete="email"
                >


                <label for="password">
                    Contraseña
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    minlength="8"
                    autocomplete="new-password"
                >

                <small>
                    Mín. 8 caracteres, 1 mayúscula,
                    1 número y 1 carácter especial.
                </small>


                <button type="submit">
                    Registrarse
                </button>

            </form>


            <div class="enlace">

                ¿Ya tienes cuenta?

                <a href="login.php">
                    Inicia sesión
                </a>

            </div>

        <?php endif; ?>

    </div>

</body>

</html>



