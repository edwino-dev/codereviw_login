-- Ejecuta esto en tu cliente MySQL (phpMyAdmin, terminal, etc.)

CREATE DATABASE IF NOT EXISTS mi_proyecto
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mi_proyecto;

CREATE TABLE IF NOT EXISTS usuarios (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(50)  NOT NULL,
    email           VARCHAR(100) NOT NULL UNIQUE,
    contrasena_hash VARCHAR(255) NOT NULL,
    fecha_registro  DATETIME     DEFAULT CURRENT_TIMESTAMP
);

-- La columna contrasena_hash debe ser VARCHAR(255) para alojar
-- el hash completo que genera PHP (actualmente ~60 caracteres,
-- pero puede crecer si PHP adopta algoritmos más largos en el futuro)
