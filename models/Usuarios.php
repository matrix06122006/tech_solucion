<?php

require_once __DIR__ . '/../core/Database.php';

class Usuarios
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    /**
     * Obtener usuario por correo
     */
    public function obtenerPorCorreo($correo)
    {
        $sql = "SELECT * FROM Usuario WHERE correo = :correo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        return $stmt->fetch();
    }

    /**
     * Obtener usuario por nombre de usuario
     */
    public function obtenerPorUsuario($usuario)
    {
        $sql = "SELECT * FROM Usuario WHERE usuario = :usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario' => $usuario]);
        return $stmt->fetch();
    }

    /**
     * Obtener usuario por ID
     */
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM Usuario WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Registrar nuevo usuario
     */
    public function registrar($nombre, $correo, $usuario, $clave, $rol = 'cliente')
    {
        // Validar que el usuario no exista
        if ($this->obtenerPorUsuario($usuario) || $this->obtenerPorCorreo($correo)) {
            return false;
        }

        $sql = "INSERT INTO Usuario (nombre, correo, usuario, clave, rol) VALUES (:nombre, :correo, :usuario, :clave, :rol)";
        $stmt = $this->db->prepare($sql);
        $claveEncriptada = password_hash($clave, PASSWORD_BCRYPT);

        return $stmt->execute([
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':usuario' => $usuario,
            ':clave' => $claveEncriptada,
            ':rol' => $rol
        ]);
    }

    /**
     * Validar credenciales del usuario
     */
    public function validarCredenciales($usuario, $clave)
    {
        $usuarioData = $this->obtenerPorUsuario($usuario);

        if (!$usuarioData) {
            return false;
        }

        // Intentar verificar con bcrypt (contraseñas encriptadas)
        if (password_verify($clave, $usuarioData['clave'])) {
            return $usuarioData;
        }

        // Si falla bcrypt, comparar como texto plano (compatibilidad con BD existente)
        if ($clave === $usuarioData['clave']) {
            return $usuarioData;
        }

        return false;
    }

    /**
     * Obtener todos los usuarios
     */
    public function obtenerTodos()
    {
        $sql = "SELECT id_usuario, nombre, correo, usuario, rol, fecha_registro FROM Usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener usuarios con rol cliente
     */
    public function obtenerClientes()
    {
        $sql = "SELECT id_usuario, nombre, correo, usuario, rol, fecha_registro FROM Usuario WHERE rol = 'cliente'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener usuarios con rol empleado
     */
    public function obtenerEmpleados()
    {
        $sql = "SELECT id_usuario, nombre, correo, usuario, rol, fecha_registro FROM Usuario WHERE rol = 'empleado'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener usuarios con rol administrador
     */
    public function obtenerAdministradores()
    {
        $sql = "SELECT id_usuario, nombre, correo, usuario, rol, fecha_registro FROM Usuario WHERE rol = 'administrador'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Crear un usuario con rol personalizado
     */
    public function crear($nombre, $correo, $usuario, $clave, $rol = 'cliente')
    {
        return $this->registrar($nombre, $correo, $usuario, $clave, $rol);
    }

    /**
     * Actualizar usuario
     */
    public function actualizar($id, $nombre, $correo, $rol)
    {
        $sql = "UPDATE Usuario SET nombre = :nombre, correo = :correo, rol = :rol WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':rol' => $rol
        ]);
    }

    /**
     * Eliminar usuario
     */
    public function eliminar($id)
    {
        $sql = "DELETE FROM Usuario WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
