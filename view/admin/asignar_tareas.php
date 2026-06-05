<?php
require_once __DIR__ . '/../../rutas.php';
Rutas::requiereAdmin();

require_once __DIR__ . '/../../controllers/tareas.php';
require_once __DIR__ . '/../../models/Usuarios.php';

$modeloUsuarios = new Usuarios();
$tareasController = new TareasController();

$clientes = $modeloUsuarios->obtenerClientes();
$empleados = $modeloUsuarios->obtenerEmpleados();
$tareas = $tareasController->obtenerTodasTareas();

$mensaje = null;
$tipo_mensaje = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear_tarea'])) {
        $cliente_id = intval($_POST['cliente_id'] ?? 0);
        $tipo_tarea = trim($_POST['tipo_tarea'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $empleado_id = intval($_POST['empleado_id'] ?? 0) ?: null;
        $estado = trim($_POST['estado'] ?? 'Pendiente');

        if ($cliente_id && $tipo_tarea && $descripcion) {
            $resultado = $tareasController->crearTareaAdmin($cliente_id, $descripcion, $tipo_tarea, $empleado_id, $estado);
            $tipo_mensaje = $resultado['estado'];
            $mensaje = $resultado['mensaje'];
            $tareas = $tareasController->obtenerTodasTareas();
        } else {
            $tipo_mensaje = 'error';
            $mensaje = 'Completa todos los campos para crear la tarea.';
        }
    }

    if (isset($_POST['asignar_tarea'])) {
        $tarea_id = intval($_POST['tarea_id'] ?? 0);
        $empleado_id = intval($_POST['empleado_id'] ?? 0);

        if ($tarea_id && $empleado_id) {
            $resultado = $tareasController->asignarEmpleado($tarea_id, $empleado_id);
            $tipo_mensaje = $resultado['estado'];
            $mensaje = $resultado['mensaje'];
            $tareas = $tareasController->obtenerTodasTareas();
        } else {
            $tipo_mensaje = 'error';
            $mensaje = 'Selecciona una tarea y un empleado para asignar.';
        }
    }

    if (isset($_POST['actualizar_estado'])) {
        $tarea_id = intval($_POST['tarea_id'] ?? 0);
        $nuevo_estado = trim($_POST['nuevo_estado'] ?? '');

        if ($tarea_id && $nuevo_estado) {
            $resultado = $tareasController->actualizarEstado($tarea_id, $nuevo_estado);
            $tipo_mensaje = $resultado['estado'];
            $mensaje = $resultado['mensaje'];
            $tareas = $tareasController->obtenerTodasTareas();
        } else {
            $tipo_mensaje = 'error';
            $mensaje = 'Selecciona un estado válido.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Tareas - Tech Solución</title>
    <link rel="stylesheet" href="../../assets/css/styles-admin.css">
</head>

<body>
    <div class="header">
        <div class="header-left">
            <h1>TECH SOLUCION</h1>
            <p>Asignar Tareas</p>
        </div>
        <div class="header-right">
            <div class="user-info">
                <p>Bienvenido,</p>
                <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>
                <p style="font-size: 11px; margin-top: 3px;">Administrador</p>
            </div>
            <a href="paneladmin.php" class="btn-action btn-edit" style="margin-right: 10px; background-color: #2ecc71;">Volver al Panel</a>
            <a href="?logout=true" class="btn-logout">Cerrar Sesion</a>
        </div>
    </div>

    <div class="container">
        <?php if ($tipo_mensaje === 'error'): ?>
            <div class="section" style="border-left: 4px solid #e74c3c;">
                <p style="color: #c0392b;"><?php echo htmlspecialchars($mensaje); ?></p>
            </div>
        <?php elseif ($tipo_mensaje === 'success'): ?>
            <div class="section" style="border-left: 4px solid #27ae60;">
                <p style="color: #27ae60;"><?php echo htmlspecialchars($mensaje); ?></p>
            </div>
        <?php endif; ?>

        <div class="section">
            <div class="section-title">Nueva Solicitud / Asignar tarea</div>
            <form method="POST" class="service-form">
                <input type="hidden" name="crear_tarea" value="1">
                <div class="form-group">
                    <label for="cliente_id">Cliente</label>
                    <select id="cliente_id" name="cliente_id" required>
                        <option value="">Selecciona un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?php echo htmlspecialchars($cliente['id_usuario']); ?>"><?php echo htmlspecialchars($cliente['nombre'] . ' (' . $cliente['usuario'] . ')'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipo_tarea">Tipo de Servicio</label>
                    <select id="tipo_tarea" name="tipo_tarea" required>
                        <option value="">Selecciona una opcion</option>
                        <option value="Arreglar celular">Arreglar celular</option>
                        <option value="Limpiar equipo">Limpiar equipo</option>
                        <option value="Soporte remoto">Soporte remoto</option>
                        <option value="Instalar software">Instalar software</option>
                        <option value="Otra">Otra</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripcion</label>
                    <textarea id="descripcion" name="descripcion" rows="4" required placeholder="Describe lo que el cliente necesita..."></textarea>
                </div>
                <div class="form-group">
                    <label for="empleado_id">Empleado (opcional)</label>
                    <select id="empleado_id" name="empleado_id">
                        <option value="">Asignar automaticamente</option>
                        <?php foreach ($empleados as $empleado): ?>
                            <option value="<?php echo htmlspecialchars($empleado['id_usuario']); ?>"><?php echo htmlspecialchars($empleado['nombre'] . ' (' . $empleado['usuario'] . ')'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estado">Estado inicial</label>
                    <select id="estado" name="estado" required>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Asignada">Asignada</option>
                        <option value="En Progreso">En Progreso</option>
                        <option value="Completada">Completada</option>
                    </select>
                </div>
                <button type="submit" class="btn-registro">Guardar Tarea</button>
            </form>
        </div>

        <div class="section">
            <div class="section-title">Lista de solicitudes</div>
            <?php if (!empty($tareas)): ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Empleado</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Descripcion</th>
                            <th>Fecha</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tareas as $tarea): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tarea['id_tarea']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['cliente_nombre'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($tarea['empleado_nombre'] ?? 'Sin asignar'); ?></td>
                                <td><?php echo htmlspecialchars($tarea['tipo_tarea']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['estado']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['descripcion']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])); ?></td>
                                <td>
                                    <?php if (empty($tarea['empleado_nombre'])): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="asignar_tarea" value="1">
                                            <input type="hidden" name="tarea_id" value="<?php echo htmlspecialchars($tarea['id_tarea']); ?>">
                                            <select name="empleado_id" required style="width: 100%; margin-bottom: 5px;">
                                                <option value="">Selecciona empleado</option>
                                                <?php foreach ($empleados as $empleado): ?>
                                                    <option value="<?php echo htmlspecialchars($empleado['id_usuario']); ?>"><?php echo htmlspecialchars($empleado['nombre']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn-action btn-edit">Asignar</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="font-size: 12px; color: #555;">Asignado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" style="display:flex; gap:6px; align-items:center;">
                                        <input type="hidden" name="actualizar_estado" value="1">
                                        <input type="hidden" name="tarea_id" value="<?php echo htmlspecialchars($tarea['id_tarea']); ?>">
                                        <select name="nuevo_estado" required>
                                            <option value="">Estado...</option>
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Asignada">Asignada</option>
                                            <option value="En Progreso">En Progreso</option>
                                            <option value="Completada">Completada</option>
                                        </select>
                                        <button type="submit" class="btn-action">Actualizar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #999; text-align: center; padding: 20px;">No hay solicitudes registradas aun.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>