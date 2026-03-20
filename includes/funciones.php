<?php
// includes/funciones.php

function obtenerPost(string $campo): string
{
    return isset($_POST[$campo]) ? htmlspecialchars(trim(strip_tags($_POST[$campo])), ENT_QUOTES, 'UTF-8') : '';
}

function generarTokenCSRF(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validarTokenCSRF(): void
{
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        http_response_code(403);
        die('Token CSRF inválido. Intenta de nuevo.');
    }
    // Opcional: regenerar token después de usarlo (one-time use)
    unset($_SESSION['csrf_token']);
}

function validarNombre(string $nombre): ?string
{
    if (empty($nombre)) return 'El nombre es obligatorio.';
    $len = mb_strlen($nombre);
    if ($len < 2)   return 'El nombre debe tener al menos 2 caracteres.';
    if ($len > 50)  return 'El nombre no puede superar 50 caracteres.';
    if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\'\-]+$/u', $nombre)) {
        return 'El nombre contiene caracteres no permitidos.';
    }
    return null;
}

function validarEmail(string $email): ?string
{
    if (empty($email)) return 'El email es obligatorio.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return 'Formato de email inválido.';
    if (strlen($email) > 100) return 'El email es demasiado largo.';
    return null;
}

function validarContrasena(string $pass): ?string
{
    if (empty($pass)) return 'La contraseña es obligatoria.';
    $len = strlen($pass);
    if ($len < 8)  return 'Mínimo 8 caracteres.';
    if ($len > 72) return 'Máximo 72 caracteres.';
    if (!preg_match('/[a-z]/', $pass)) return 'Debe contener al menos una letra minúscula.';
    if (!preg_match('/[A-Z]/', $pass)) return 'Debe contener al mayor una letra mayúscula.';
    if (!preg_match('/[0-9]/', $pass)) return 'Debe contener al menos un número.';
    return null;
}

function mostrarErrores(array $errores): void
{
    if (empty($errores)) return;
    echo '<div class="alert error">';
    echo '<ul style="margin:0; padding-left:1.5rem;">';
    foreach ($errores as $err) {
        echo '<li>' . htmlspecialchars($err) . '</li>';
    }
    echo '</ul></div>';
}
