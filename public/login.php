<?php
require_once __DIR__ . '/../includes/bd.php';
require_once __DIR__ . '/../includes/funciones.php';
require_once __DIR__ . '/../includes/flash.php';

session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit;
}

$errores = [];
$token = generarTokenCSRF();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validarTokenCSRF();

    $email      = obtenerPost('email');
    $contrasena = trim($_POST['contrasena'] ?? '');

    if (empty($email) || empty($contrasena)) {
        $errores[] = 'Completa todos los campos.';
    } else {
        try {
            $pdo = obtenerConexion();
            $stmt = $pdo->prepare("SELECT id, nombre, contrasena_hash FROM usuarios WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($contrasena, $user['contrasena_hash'])) {
                $errores[] = 'Credenciales incorrectas.';
            } else {
                $_SESSION['usuario_id']     = $user['id'];
                $_SESSION['usuario_nombre'] = $user['nombre'];
                flash('success', "¡Bienvenido, {$user['nombre']}!");

                // Regenerar token CSRF después de login exitoso
                unset($_SESSION['csrf_token']);

                header("Location: dashboard.php");
                exit;
            }
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            $errores[] = 'Error del servidor. Intenta más tarde.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>Iniciar Sesión</h1>

            <?php mostrarFlash(); ?>
            <?php mostrarErrores($errores); ?>

            <form method="post" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">

                <label>Email</label>
                <input type="email" name="email" required maxlength="100" autocomplete="username">

                <label>Contraseña</label>
                <input type="password" name="contrasena" required maxlength="72" autocomplete="current-password">

                <input type="submit" value="Iniciar Sesión">
            </form>

            <div class="links">
                <a href="registro.php">¿No tienes cuenta? Regístrate</a>
            </div>
        </div>
    </div>
</body>

</html>