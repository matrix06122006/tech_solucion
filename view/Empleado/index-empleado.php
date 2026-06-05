<?php
require_once __DIR__ . '/../../rutas.php';
Rutas::requiereAutenticacion();

// Simple comprobación de rol
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../../controllers/tareas.php';

$tareasController = new TareasController();

$mensaje_post = null;
$tipo_post = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_estado'])) {
    $tarea_id = intval($_POST['tarea_id'] ?? 0);
    $nuevo_estado = trim($_POST['nuevo_estado'] ?? '');
    if ($tarea_id && $nuevo_estado) {
        $resultado_post = $tareasController->actualizarEstado($tarea_id, $nuevo_estado);
        $mensaje_post = $resultado_post['mensaje'];
        $tipo_post = $resultado_post['estado'];
    }
}

$tareas_empleado = $tareasController->obtenerTareasEmpleado($_SESSION['id_usuario']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Empleado - Tech Solución</title>
    <link rel="stylesheet" href="../../assets/css/styles-admin.css">
</head>

<body>
    <div class="header">
        <div class="header-left">
            <h1>TECH SOLUCION</h1>
            <p>Panel Empleado</p>
        </div>
        <div class="header-right">
            <div class="user-info">
                <p>Bienvenido,</p>
                <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>
                <p style="font-size: 11px; margin-top: 3px;">Empleado</p>
            </div>
            <a href="../auth/login.php?logout=true" class="btn-logout">Cerrar Sesion</a>
        </div>
    </div>

    <div class="container">
        <div class="section">
            <div class="section-title">Tareas asignadas</div>

            <?php if (!empty($mensaje_post)): ?>
                <div style="padding:8px; color: <?php echo $tipo_post === 'exitoso' ? '#27ae60' : '#c0392b'; ?>;">
                    <?php echo htmlspecialchars($mensaje_post); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($tareas_empleado)): ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tareas_empleado as $tarea): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tarea['id_tarea']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['cliente_nombre'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($tarea['tipo_tarea']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['estado']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])); ?></td>
                                <td>
                                    <form method="POST" style="display:flex; gap:6px; align-items:center;">
                                        <input type="hidden" name="cambiar_estado" value="1">
                                        <input type="hidden" name="tarea_id" value="<?php echo htmlspecialchars($tarea['id_tarea']); ?>">
                                        <select name="nuevo_estado" required>
                                            <option value="">Selecciona...</option>
                                            <option value="En Progreso">En Progreso</option>
                                            <option value="Completada">Completada</option>
                                        </select>
                                        <button type="submit" class="btn-action">Cambiar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #999; text-align: center; padding: 20px;">No tienes tareas asignadas.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>