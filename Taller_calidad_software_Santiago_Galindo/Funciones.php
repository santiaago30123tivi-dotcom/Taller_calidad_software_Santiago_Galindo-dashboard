<?php
// Reglas de la contraseña:
// - Mínimo 8 caracteres
// - Al menos una mayúscula
// - Al menos un número
// - Al menos un caracter especial
function validarPassword(string $password): ?string {
    if (strlen($password) < 8) {
        return "La contraseña debe tener al menos 8 caracteres.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return "La contraseña debe tener al menos una letra mayúscula.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "La contraseña debe tener al menos un número.";
    }
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>_\-]/', $password)) {
        return "La contraseña debe tener al menos un caracter especial (!@#\$%, etc).";
    }
    return null; // null = válida
}

function validarUsername(string $username): ?string {
    $username = trim($username);
    if (strlen($username) < 4) {
        return "El nombre de usuario debe tener al menos 4 caracteres.";
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return "El nombre de usuario solo puede tener letras, números y guion bajo.";
    }
    return null;
}

function validarEmail(string $email): ?string {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "El correo electrónico no tiene un formato válido.";
    }
    return null;
}