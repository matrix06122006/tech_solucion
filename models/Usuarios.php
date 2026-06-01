<?php

require_once __DIR__ . '/../core/Database.php';

class Usuarios {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    /**
     * Obtener usuario por correo
     */
    public function obtenerPorCorreo($correo) {
        $sql = "SELECT * FROM Usuario WHERE correo = :correo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        return $stmt->fetch();
    }

    /**
     * Obtener usuario por nombre de usuario
     */
    public function obtenerPorUsuario($usuario) {
        $sql = "SELECT * FROM Usuario WHERE usuario = :usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario' => $usuario]);
        return $stmt->fetch();
    }

    /**
     * Obtener usuario por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Usuario WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Registrar nuevo usuario
     */
    public function registrar($nombre, $correo, $usuario, $clave) {
        // Validar que el usuario no exista
        if ($this->obtenerPorUsuario($usuario) || $this->obtenerPorCorreo($correo)) {
            return false;
        }

        $sql = "INSERT INTO Usuario (nombre, correo, usuario, clave) VALUES (:nombre, :correo, :usuario, :clave)";
        $stmt = $this->db->prepare($sql);
        $claveEncriptada = password_hash($clave, PASSWORD_BCRYPT);

        return $stmt->execute([
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':usuario' => $usuario,
            ':clave' => $claveEncriptada
        ]);
    }

    /**
     * Validar credenciales del usuario
     */
    public function validarCredenciales($usuario, $clave) {
        $usuarioData = $this->obtenerPorUsuario($usuario);
        
        if (!$usuarioData) {
            return false;
        }

        if (password_verify($clave, $usuarioData['clave'])) {
            return $usuarioData;
        }

        return false;
    }

    /**
     * Obtener todos los usuarios
     */
    public function obtenerTodos() {
        $sql = "SELECT id_usuario, nombre, correo, usuario, fecha_registro FROM Usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Actualizar usuario
     */
    public function actualizar($id, $nombre, $correo) {
        $sql = "UPDATE Usuario SET nombre = :nombre, correo = :correo WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':correo' => $correo
        ]);
    }

    /**
     * Eliminar usuario
     */
    public function eliminar($id) {
        $sql = "DELETE FROM Usuario WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
