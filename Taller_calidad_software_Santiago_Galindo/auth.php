<?php
// Incluir este archivo al inicio de cualquier página que requiera sesión activa
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}