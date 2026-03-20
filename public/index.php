<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Proyecto - Inicio</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>Bienvenido</h1>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <p>Ya estás conectado como <strong><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></strong></p>
                <a href="dashboard.php">Ir al Dashboard</a> | <a href="logout.php">Cerrar sesión</a>
            <?php else: ?>
                <a href="login.php">Iniciar Sesión</a> | <a href="registro.php">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>