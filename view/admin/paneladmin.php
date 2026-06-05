<?php
require_once __DIR__ . '/../../rutas.php';
Rutas::requiereAdmin();

require_once __DIR__ . '/../../controllers/usuarios.php';
require_once __DIR__ . '/../../controllers/tareas.php';
require_once __DIR__ . '/../../models/Usuarios.php';
require_once __DIR__ . '/../../models/Tareas.php';

$controller = new UsuariosController();
$modeloUsuarios = new Usuarios();
$tareasController = new TareasController();
$todosUsuarios = $modeloUsuarios->obtenerTodos();
$tareas = $tareasController->obtenerTodasTareas();
$pendientes = array_filter($tareas, fn($t) => $t['estado'] === 'Pendiente');

if (isset($_GET['logout'])) {
    Rutas::cerrarSesion();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - Tech Solución</title>
    <link rel="stylesheet" href="../../assets/css/styles-admin.css">
</head>

<body>
    <div class="header">
        <div class="header-left">
            <h1>TECH SOLUCION</h1>
            <p>Panel Administrativo</p>
        </div>
        <div class="header-right">
            <div class="user-info">
                <p>Bienvenido,</p>
                <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>
                <p style="font-size: 11px; margin-top: 3px;">Administrador</p>
            </div>
            <a href="?logout=true" class="btn-logout">Cerrar Sesion</a>
        </div>
    </div>

    <div class="container">
        <!-- Sección de Bienvenida -->
        <div class="welcome-section">
            <div class="welcome-title">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></div>
            <div class="welcome-message">
                Eres un usuario Administrador del sistema Tech Solucion
            </div>
            <div class="welcome-details">
                <p><strong>ID Usuario:</strong> <?php echo htmlspecialchars($_SESSION['id_usuario']); ?></p>
                <p><strong>Usuario:</strong> <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
                <p><strong>Correo:</strong> <?php echo htmlspecialchars($_SESSION['correo']); ?></p>
                <p><strong>Rol:</strong> <span class="badge badge-admin">Administrador</span></p>
            </div>
        </div>

        <!-- Opciones de Administración -->
        <div class="section">
            <div class="section-title">Opciones de Administracion</div>
            <div class="admin-menu">
                <button class="menu-btn" onclick="window.location.href='gestionar_usu.php'">Gestionar Usuarios</button>
                <button class="menu-btn" onclick="alert('Funcion en desarrollo')">Reportes</button>
                <button class="menu-btn" onclick="alert('Funcion en desarrollo')">Configuracion</button>
                <button class="menu-btn" onclick="window.location.href='asignar_tareas.php'">Asignar Tareas</button>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($todosUsuarios); ?></div>
                <div class="stat-label">Usuarios Registrados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($todosUsuarios, fn($u) => $u['rol'] === 'administrador')); ?></div>
                <div class="stat-label">Administradores</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($todosUsuarios, fn($u) => $u['rol'] === 'empleado')); ?></div>
                <div class="stat-label">Empleados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($todosUsuarios, fn($u) => $u['rol'] === 'cliente')); ?></div>
                <div class="stat-label">Clientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($tareas); ?></div>
                <div class="stat-label">Solicitudes Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($pendientes); ?></div>
                <div class="stat-label">Solicitudes Pendientes</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Tareas recientes</div>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($tareas, 0, 6) as $tarea): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tarea['id_tarea']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['cliente_nombre'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($tarea['empleado_nombre'] ?? 'Sin asignar'); ?></td>
                                <td><?php echo htmlspecialchars($tarea['tipo_tarea']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['estado']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['descripcion']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #999; text-align: center; padding: 20px;">No hay tareas registradas aun.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>