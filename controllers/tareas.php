<?php

require_once __DIR__ . '/../models/Tareas.php';
require_once __DIR__ . '/../models/Usuarios.php';

class TareasController
{
    private $modeloTareas;
    private $modeloUsuarios;

    public function __construct()
    {
        $this->modeloTareas = new Tareas();
        $this->modeloUsuarios = new Usuarios();
    }

    public function crearTareaCliente($id_cliente, $descripcion, $tipo_tarea)
    {
        $empleados = $this->modeloUsuarios->obtenerEmpleados();
        $id_empleado = $empleados[0]['id_usuario'] ?? null;
        $estado = $id_empleado ? 'Asignada' : 'Pendiente';

        if ($this->modeloTareas->crear($id_cliente, $descripcion, $tipo_tarea, $id_empleado, $estado)) {
            return [
                'estado' => 'exitoso',
                'mensaje' => $id_empleado ? 'Solicitud creada y asignada automáticamente.' : 'Solicitud creada. No hay empleados disponibles, quedará como pendiente.'
            ];
        }

        return ['estado' => 'error', 'mensaje' => 'No se pudo guardar la solicitud.'];
    }

    public function crearTareaAdmin($id_cliente, $descripcion, $tipo_tarea, $id_empleado = null, $estado = 'Pendiente')
    {
        if ($this->modeloTareas->crear($id_cliente, $descripcion, $tipo_tarea, $id_empleado, $estado)) {
            return ['estado' => 'exitoso', 'mensaje' => 'Tarea registrada correctamente.'];
        }

        return ['estado' => 'error', 'mensaje' => 'No se pudo crear la tarea.'];
    }

    public function asignarEmpleado($id_tarea, $id_empleado, $estado = 'Asignada')
    {
        if ($this->modeloTareas->asignarEmpleado($id_tarea, $id_empleado, $estado)) {
            return ['estado' => 'exitoso', 'mensaje' => 'Empleado asignado correctamente.'];
        }

        return ['estado' => 'error', 'mensaje' => 'No se pudo asignar el empleado.'];
    }

    public function obtenerTodasTareas()
    {
        return $this->modeloTareas->obtenerTodas();
    }

    public function obtenerTareasCliente($id_cliente)
    {
        return $this->modeloTareas->obtenerPorCliente($id_cliente);
    }

    public function obtenerTareasEmpleado($id_empleado)
    {
        return $this->modeloTareas->obtenerPorEmpleado($id_empleado);
    }

    public function actualizarEstado($id_tarea, $estado)
    {
        if ($this->modeloTareas->actualizarEstado($id_tarea, $estado)) {
            return ['estado' => 'exitoso', 'mensaje' => 'Estado actualizado correctamente.'];
        }

        return ['estado' => 'error', 'mensaje' => 'No se pudo actualizar el estado.'];
    }
}
