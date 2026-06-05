<?php
require_once __DIR__ . '/../../rutas.php';
Rutas::requiereCliente();

require_once __DIR__ . '/../../models/Usuarios.php';
require_once __DIR__ . '/../../controllers/tareas.php';

$modeloUsuarios = new Usuarios();
$tareasController = new TareasController();
$usuarioActual = $modeloUsuarios->obtenerPorId($_SESSION['id_usuario']);
$solicitudesCliente = $tareasController->obtenerTareasCliente($_SESSION['id_usuario']);

if (isset($_GET['logout'])) {
    Rutas::cerrarSesion();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Tech Solución</title>
    <link rel="stylesheet" href="../../assets/css/styles-cliente.css">
</head>

<body>
    <div class="header">
        <div class="header-left">
            <h1>TECH SOLUCION</h1>
            <p>Mis Pedidos</p>
        </div>
        <div class="header-right">
            <div class="user-info">
                <p>Conectado como,</p>
                <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>
                <p style="font-size: 11px; margin-top: 3px;">Cliente</p>
            </div>
            <a href="indexcliente.php" class="btn-action btn-edit" style="margin-right: 10px; background-color: #2ecc71;">Volver</a>
            <a href="?logout=true" class="btn-logout">Cerrar Sesion</a>
        </div>
    </div>

    <div class="container">
        <div class="section">
            <div class="section-title">Mis solicitudes</div>
            <?php if (!empty($solicitudesCliente)): ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Descripcion</th>
                            <th>Empleado</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudesCliente as $solicitud): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($solicitud['id_tarea']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['tipo_tarea']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['empleado_nombre'] ?? 'Sin asignar'); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['estado']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_creacion'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #999; text-align: center; padding: 20px;">Aún no has enviado solicitudes. Usa el formulario para pedir servicio.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>