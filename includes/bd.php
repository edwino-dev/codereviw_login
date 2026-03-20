<?php
// includes/bd.php

// Opcional: soporte para .env (recomendado en producción)
if (file_exists(__DIR__ . '/../.env')) {
    foreach (parse_ini_file(__DIR__ . '/../.env') as $k => $v) {
        if (!defined($k)) define($k, $v);
    }
}

define('DB_HOST',   defined('DB_HOST')   ? DB_HOST   : 'localhost');
define('DB_NAME',   defined('DB_NAME')   ? DB_NAME   : 'mi_proyecto');
define('DB_USER',   defined('DB_USER')   ? DB_USER   : 'root');
define('DB_PASS',   defined('DB_PASS')   ? DB_PASS   : '');
define('DB_CHARSET', defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4');

function obtenerConexion(): PDO
{
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Error de conexión a BD: " . $e->getMessage());
        http_response_code(503);
        die("Error interno del servidor. Intenta más tarde.");
    }
}
