<?php
require "auth.php";
require "db.php";

$mensaje = $_GET["mensaje"] ?? "";

$stmt = $pdo->query("SELECT id, username, email, fecha_registro FROM usuarios ORDER BY id DESC");
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de usuarios</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos de respaldo por si css/style.css no carga */
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f2f4f8;
            margin: 0;
            padding: 2rem;
        }
        .contenedor-ancho {
            background: #fff;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 2.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2b2f38;
            font-size: 1.5rem;
            margin-top: 0;
        }
        .barra-acciones {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
            flex-wrap: wrap;
            gap: 0.6rem;
        }
        .btn-primario {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            background: #4b6ef5;
            padding: 0.55rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        .btn-primario:hover {
            background: #3a57cc;
        }
        .enlace-volver {
            color: #555;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .tabla {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        .tabla th,
        .tabla td {
            text-align: left;
            padding: 0.7rem 0.6rem;
            border-bottom: 1px solid #eee;
        }
        .tabla th {
            color: #666;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border-bottom: 2px solid #ddd;
        }
        .tabla tbody tr:hover {
            background: #f7f9fc;
        }
        .sin-datos {
            text-align: center;
            color: #888;
            padding: 1.5rem 0;
        }
        .acciones {
            display: flex;
            gap: 0.5rem;
        }
        .btn-editar,
        .btn-eliminar {
            display: inline-block;
            text-decoration: none;
            padding: 0.35rem 0.7rem;
            border-radius: 5px;
            font-size: 0.82rem;
            color: #fff;
        }
        .btn-editar {
            background: #4b6ef5;
        }
        .btn-editar:hover {
            background: #3a57cc;
        }
        .btn-eliminar {
            background: #e5484d;
        }
        .btn-eliminar:hover {
            background: #c53439;
        }
        .mensaje-exito {
            background: #e6f4ea;
            color: #1e7c34;
            padding: 0.6rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
        .mensaje-error {
            background: #fdecea;
            color: #b3261e;
            padding: 0.6rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="contenedor contenedor-ancho">
        <h1>Usuarios registrados</h1>

        <?php if ($mensaje === "creado"): ?>
            <div class="mensaje-exito">Usuario creado correctamente.</div>
        <?php elseif ($mensaje === "editado"): ?>
            <div class="mensaje-exito">Usuario actualizado correctamente.</div>
        <?php elseif ($mensaje === "eliminado"): ?>
            <div class="mensaje-exito">Usuario eliminado correctamente.</div>
        <?php elseif ($mensaje === "no_puedes_borrarte"): ?>
            <div class="mensaje-error">No puedes eliminar tu propia cuenta mientras tienes la sesión iniciada.</div>
        <?php endif; ?>

        <div class="barra-acciones">
            <a class="btn-primario" href="usuario_crear.php">+ Nuevo usuario</a>
            <a class="enlace-volver" href="dashboard.php">&larr; Volver al panel</a>
        </div>

        <table class="tabla">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Registrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="5" class="sin-datos">No hay usuarios registrados todavía.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u["id"] ?></td>
                            <td><?= htmlspecialchars($u["username"]) ?></td>
                            <td><?= htmlspecialchars($u["email"]) ?></td>
                            <td><?= htmlspecialchars($u["fecha_registro"]) ?></td>
                            <td class="acciones">
                                <a class="btn-editar" href="usuario_editar.php?id=<?= $u['id'] ?>">Editar</a>
                                <a class="btn-eliminar"
                                   href="usuario_eliminar.php?id=<?= $u['id'] ?>"
                                   onclick="return confirm('¿Seguro que quieres eliminar a &quot;<?= htmlspecialchars($u['username'], ENT_QUOTES) ?>&quot;? Esta acción no se puede deshacer.');">
                                   Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>