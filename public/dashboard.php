<?php
require_once __DIR__ . '/../includes/funciones.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mi Proyecto</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>¡Bienvenido, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>!</h1>
            <p>Este es tu panel de control privado.</p>
            <p><a href="logout.php">Cerrar sesión</a></p>
        </div>
    </div>
</body>

</html>