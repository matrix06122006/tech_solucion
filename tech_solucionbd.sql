CREATE DATABASE IF NOT EXISTS tech_solucionbd;
USE tech_solucionbd;

CREATE TABLE if not exists Usuario(
id_usuario INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(100) NOT NULL,
correo VARCHAR(100) UNIQUE,
usuario VARCHAR(50) NOT NULL,
clave VARCHAR(255),
rol ENUM('administrador','cliente') NOT NULL,
fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELIMITER //
CREATE PROCEDURE sp_insertar_usuario(
IN p_nombre VARCHAR(100),
IN p_correo VARCHAR(100),
IN p_usuario VARCHAR(50),
IN p_clave VARCHAR(100),
IN p_rol VARCHAR(20)
)
BEGIN

INSERT INTO Usuario(
nombre,
correo,
usuario,
clave,
rol
)
VALUES(
p_nombre,
p_correo,
p_usuario,
p_clave,
P_rol
);

END //
DELIMITER ;

CALL sp_insertar_usuario(
'David Pastrana',
'davidp@gmail.com',
'admin',
'1234',
'administrador'
);

CALL sp_insertar_usuario(
'Laura Torres',
'laura@sneaker.com',
'vendedor1',
'1234',
'cliente'
);