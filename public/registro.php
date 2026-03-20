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
$datos = ['nombre' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validarTokenCSRF();

    $datos['nombre'] = obtenerPost('nombre');
    $datos['email']  = obtenerPost('email');
    $pass            = trim($_POST['contrasena'] ?? '');

    if ($err = validarNombre($datos['nombre']))     $errores[] = $err;
    if ($err = validarEmail($datos['email']))       $errores[] = $err;
    if ($err = validarContrasena($pass))            $errores[] = $err;

    if (empty($errores)) {
        try {
            $pdo = obtenerConexion();

            $stmt = $pdo->prepare("SELECT 1 FROM usuarios WHERE email = ? LIMIT 1");
            $stmt->execute([$datos['email']]);
            if ($stmt->fetch()) {
                $errores[] = 'Ese correo ya está registrado.';
            } else {
                $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);

                $stmt = $pdo->prepare("
                    INSERT INTO usuarios (nombre, email, contrasena_hash)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$datos['nombre'], $datos['email'], $hash]);

                flash('success', 'Cuenta creada correctamente. Ya puedes iniciar sesión.');
                header("Location: login.php");
                exit;
            }
        } catch (Exception $e) {
            error_log("Error en registro: " . $e->getMessage());
            $errores[] = 'Error al crear la cuenta. Intenta más tarde.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>Crear Cuenta</h1>

            <?php mostrarFlash(); ?>
            <?php mostrarErrores($errores); ?>

            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">

                <label>Nombre completo</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($datos['nombre']) ?>" required maxlength="50">

                <label>Correo electrónico</label>
                <input type="email" name="email" value="<?= htmlspecialchars($datos['email']) ?>" required maxlength="100">

                <label>Contraseña</label>
                <input type="password" name="contrasena" required maxlength="72">

                <input type="submit" value="Registrarse">
            </form>

            <div class="links">
                <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </div>
    </div>
</body>

</html>