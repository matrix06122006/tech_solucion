<?php

require_once __DIR__ . '/../core/Database.php';

class Tareas
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function crear($id_cliente, $descripcion, $tipo_tarea, $id_empleado = null, $estado = 'Pendiente')
    {
        $sql = "INSERT INTO Tarea (id_cliente, id_empleado, descripcion, tipo_tarea, estado) VALUES (:id_cliente, :id_empleado, :descripcion, :tipo_tarea, :estado)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_cliente' => $id_cliente,
            ':id_empleado' => $id_empleado,
            ':descripcion' => $descripcion,
            ':tipo_tarea' => $tipo_tarea,
            ':estado' => $estado,
        ]);
    }

    public function obtenerTodas()
    {
        $sql = "SELECT t.*, c.nombre AS cliente_nombre, c.usuario AS cliente_usuario, e.nombre AS empleado_nombre, e.usuario AS empleado_usuario
                FROM Tarea t
                LEFT JOIN Usuario c ON t.id_cliente = c.id_usuario
                LEFT JOIN Usuario e ON t.id_empleado = e.id_usuario
                ORDER BY t.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorCliente($id_cliente)
    {
        $sql = "SELECT t.*, e.nombre AS empleado_nombre, e.usuario AS empleado_usuario
                FROM Tarea t
                LEFT JOIN Usuario e ON t.id_empleado = e.id_usuario
                WHERE t.id_cliente = :id_cliente
                ORDER BY t.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_cliente' => $id_cliente]);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id_tarea)
    {
        $sql = "SELECT * FROM Tarea WHERE id_tarea = :id_tarea";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_tarea' => $id_tarea]);
        return $stmt->fetch();
    }

    public function asignarEmpleado($id_tarea, $id_empleado, $estado = 'Asignada')
    {
        $sql = "UPDATE Tarea SET id_empleado = :id_empleado, estado = :estado WHERE id_tarea = :id_tarea";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_tarea' => $id_tarea,
            ':id_empleado' => $id_empleado,
            ':estado' => $estado,
        ]);
    }

    public function obtenerPorEmpleado($id_empleado)
    {
        $sql = "SELECT t.*, c.nombre AS cliente_nombre, c.usuario AS cliente_usuario
                FROM Tarea t
                LEFT JOIN Usuario c ON t.id_cliente = c.id_usuario
                WHERE t.id_empleado = :id_empleado
                ORDER BY t.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_empleado' => $id_empleado]);
        return $stmt->fetchAll();
    }

    public function actualizarEstado($id_tarea, $estado)
    {
        $sql = "UPDATE Tarea SET estado = :estado WHERE id_tarea = :id_tarea";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_tarea' => $id_tarea,
            ':estado' => $estado,
        ]);
    }
}
