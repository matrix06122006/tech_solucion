CREATE DATABASE IF NOT EXISTS tech_solucionbd;
USE tech_solucionbd;

CREATE TABLE if not exists Usuario(
id_usuario INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(100) NOT NULL,
correo VARCHAR(100) UNIQUE,
usuario VARCHAR(50) NOT NULL,
clave VARCHAR(255),
rol ENUM('administrador','cliente','empleado') NOT NULL,
fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE if not exists Tarea(
    id_tarea INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_empleado INT NULL,
    descripcion TEXT NOT NULL,
    tipo_tarea VARCHAR(100) NOT NULL,
    estado ENUM('Pendiente','Asignada','En Progreso','Completada') DEFAULT 'Pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES Usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_empleado) REFERENCES Usuario(id_usuario) ON DELETE SET NULL
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
p_rol
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
'laura@gmail.com',
'vendedor1',
'1234',
'empleado'
);

CALL sp_insertar_usuario(
'daniel',
'daniel@gmail.com',
'daniel123',
'123456mm',
'cliente'
);