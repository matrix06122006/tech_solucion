CREATE DATABASE IF NOT EXISTS tech_solucionbd;
USE tech_solucionbd;

CREATE TABLE Usuario(
id_usuario INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(100) NOT NULL,
correo VARCHAR(100) UNIQUE,
usuario VARCHAR(50) NOT NULL,
clave VARCHAR(255),
fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELIMITER //
CREATE PROCEDURE sp_insertar_usuario(
IN p_nombre VARCHAR(100),
IN p_correo VARCHAR(100),
IN p_usuario VARCHAR(50),
IN p_clave VARCHAR(100),
)
BEGIN

INSERT INTO Usuario(
nombre,
correo,
usuario,
clave,
)
VALUES(
p_nombre,
p_correo,
p_usuario,
p_clave,
);

END //
DELIMITER ;