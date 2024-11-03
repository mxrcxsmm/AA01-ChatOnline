CREATE SCHEMA `db_chatonline` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE db_chatonline;

CREATE TABLE `usuario` (
    `id_usuario` INT NOT NULL AUTO_INCREMENT,
    `usuario` VARCHAR(20) NOT NULL,
    `nombre` VARCHAR(50) NOT NULL,
    `passwd` LONGTEXT NOT NULL,
    PRIMARY KEY (`id_usuario`)
);

CREATE TABLE `solicitud_amistad` (
    `id_solicitudAmistad` INT NOT NULL AUTO_INCREMENT,
    `id_usuario_enviado` INT NOT NULL,
    `id_usuario_recibido` INT NOT NULL,
    PRIMARY KEY (`id_solicitudAmistad`),
    FOREIGN KEY (`id_usuario_enviado`) REFERENCES `usuario`(`id_usuario`),
    FOREIGN KEY (`id_usuario_recibido`) REFERENCES `usuario`(`id_usuario`)
);

ALTER TABLE solicitud_amistad ADD COLUMN status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending';

CREATE TABLE `amistad` (
    `id_amistad` INT NOT NULL AUTO_INCREMENT,
    `id_usuario1` INT NOT NULL,
    `id_usuario2` INT NOT NULL,
    PRIMARY KEY (`id_amistad`),
    FOREIGN KEY (`id_usuario1`) REFERENCES `usuario`(`id_usuario`),
    FOREIGN KEY (`id_usuario2`) REFERENCES `usuario`(`id_usuario`)
);

CREATE TABLE `mensaje` (
    `id_mensaje` INT NOT NULL AUTO_INCREMENT,
    `contenido` LONGTEXT NOT NULL,
    `imagen` LONGTEXT NOT NULL,
    `fecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `id_amistad` INT NOT NULL,
    `id_usuario_remitente` INT NOT NULL,
    PRIMARY KEY (`id_mensaje`),
    FOREIGN KEY (`id_amistad`) REFERENCES `amistad`(`id_amistad`),
    FOREIGN KEY (`id_usuario_remitente`) REFERENCES `usuario`(`id_usuario`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
