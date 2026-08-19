<?php
require "auth.php";
require "db.php";

$id = (int) ($_GET["id"] ?? 0);

if ($id > 0) {
    // Evita que el usuario elimine su propia cuenta mientras tiene la sesión activa
    if ($id === (int) $_SESSION["usuario_id"]) {
        header("Location: usuarios.php?mensaje=no_puedes_borrarte");
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: usuarios.php?mensaje=eliminado");
exit;