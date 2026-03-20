<?php
// includes/flash.php

function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = compact('tipo', 'mensaje');
}

function mostrarFlash(): void
{
    if (!isset($_SESSION['flash'])) return;
    $f = $_SESSION['flash'];
    $clase = ($f['tipo'] === 'success') ? 'success' : 'error';
    echo '<div class="alert ' . $clase . '">' . htmlspecialchars($f['mensaje']) . '</div>';
    unset($_SESSION['flash']);
}
