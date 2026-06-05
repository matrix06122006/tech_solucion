<?php
require_once __DIR__ . '/../../rutas.php';
Rutas::requiereCliente();

require_once __DIR__ . '/../../models/Usuarios.php';
require_once __DIR__ . '/../../controllers/tareas.php';

$modeloUsuarios = new Usuarios();
$usuarioActual = $modeloUsuarios->obtenerPorId($_SESSION['id_usuario']);

$tareasController = new TareasController();

$mensaje_post = null;
$tipo_post = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_tarea'])) {
    $tarea_id = intval($_POST['tarea_id'] ?? 0);
    if ($tarea_id) {
        $resultado = $tareasController->actualizarEstado($tarea_id, 'Completada');
        $mensaje_post = $resultado['mensaje'];
        $tipo_post = $resultado['estado'];
    }
}

$tareas_cliente = $tareasController->obtenerTareasCliente($_SESSION['id_usuario']);

if (isset($_GET['logout'])) {
    Rutas::cerrarSesion();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Tech Solución</title>
    <link rel="stylesheet" href="../../assets/css/styles-cliente.css">
</head>

<body>
    <div class="header">
        <div class="header-left">
            <h1>TECH SOLUCION</h1>
            <p>Plataforma de Clientes</p>
        </div>
        <div class="header-right">
            <div class="user-info">
                <p>Conectado como,</p>
                <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>
                <p style="font-size: 11px; margin-top: 3px;">Cliente</p>
            </div>
            <a href="?logout=true" class="btn-logout">Cerrar Sesion</a>
        </div>
    </div>

    <div class="container">
        <!-- Sección de Bienvenida -->
        <div class="welcome-section">
            <div class="welcome-title">Bienvenido</div>
            <div class="welcome-name"><?php echo htmlspecialchars($_SESSION['nombre']); ?></div>
            <div class="welcome-subtitle">
                Gracias por ser parte de nuestra plataforma Tech Solucion
            </div>
        </div>

        <!-- Tarjeta de Usuario -->
        <div class="user-card">
            <div class="user-card-title">Informacion de Cuenta</div>
            <div class="user-detail">
                <span class="user-detail-label">ID de Usuario:</span>
                <span class="user-detail-value"><?php echo htmlspecialchars($_SESSION['id_usuario']); ?></span>
            </div>
            <div class="user-detail">
                <span class="user-detail-label">Nombre:</span>
                <span class="user-detail-value"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
            </div>
            <div class="user-detail">
                <span class="user-detail-label">Usuario:</span>
                <span class="user-detail-value"><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
            </div>
            <div class="user-detail">
                <span class="user-detail-label">Correo Electronico:</span>
                <span class="user-detail-value"><?php echo htmlspecialchars($_SESSION['correo']); ?></span>
            </div>
            <div class="user-detail">
                <span class="user-detail-label">Tipo de Cuenta:</span>
                <span class="user-detail-value">Cliente Activo</span>
            </div>
            <div class="user-detail">
                <span class="user-detail-label">Fecha de Registro:</span>
                <span class="user-detail-value"><?php echo date('d/m/Y H:i', strtotime($usuarioActual['fecha_registro'] ?? 'now')); ?></span>
            </div>
        </div>

        <!-- Opciones Principales -->
        <div class="content-section">
            <div class="section-title">Opciones Disponibles</div>
            <div class="options-grid">
                <button class="option-btn" onclick="alert('Funcion en desarrollo')">Ver Productos</button>
                <button class="option-btn" onclick="window.location.href='pedidos.php'">Mis Pedidos</button>
                <button class="option-btn" onclick="window.location.href='solicitar_servicio.php'">Solicitar Servicio</button>
                <button class="option-btn" onclick="alert('Funcion en desarrollo')">Contactar Soporte</button>
            </div>
        </div>

        <!-- Mis Tareas -->
        <div class="content-section">
            <div class="section-title">Mis Tareas</div>
            <?php if (!empty($mensaje_post)): ?>
                <div style="padding:8px; color: <?php echo $tipo_post === 'exitoso' ? '#27ae60' : '#c0392b'; ?>;">
                    <?php echo htmlspecialchars($mensaje_post); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($tareas_cliente)): ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Empleado</th>
                            <th>Fecha</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tareas_cliente as $tarea): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tarea['id_tarea']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['tipo_tarea']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['estado']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['empleado_nombre'] ?? 'Sin asignar'); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])); ?></td>
                                <td>
                                    <?php if ($tarea['estado'] !== 'Completada'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="confirmar_tarea" value="1">
                                            <input type="hidden" name="tarea_id" value="<?php echo htmlspecialchars($tarea['id_tarea']); ?>">
                                            <button type="submit" class="btn-action">Confirmar completado</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color:#27ae60;">Completada</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #999; text-align: center; padding: 20px;">No tienes solicitudes registradas.</p>
            <?php endif; ?>
        </div>

        <!-- Características -->
        <div class="content-section">
            <div class="section-title">Caracteristicas de tu Cuenta</div>
            <div class="features-list">
                <div class="feature-card">
                    <div class="feature-title">Ofertas Exclusivas</div>
                    <div class="feature-desc">Acceso a promociones y descuentos especiales para clientes registrados</div>
                </div>
                <div class="feature-card">
                    <div class="feature-title">Seguimiento de Pedidos</div>
                    <div class="feature-desc">Rastrear tus compras en tiempo real desde tu panel</div>
                </div>
                <div class="feature-card">
                    <div class="feature-title">Pagos Seguros</div>
                    <div class="feature-desc">Realiza compras con total seguridad usando nuestro sistema encriptado</div>
                </div>
                <div class="feature-card">
                    <div class="feature-title">Soporte 24/7</div>
                    <div class="feature-desc">Nuestro equipo esta disponible para ayudarte en cualquier momento</div>
                </div>
                <div class="feature-card">
                    <div class="feature-title">Programa de Puntos</div>
                    <div class="feature-desc">Gana puntos con cada compra y canjealos por premios</div>
                </div>
                <div class="feature-card">
                    <div class="feature-title">Privacidad Garantizada</div>
                    <div class="feature-desc">Tu informacion personal esta protegida con los mas altos estandares</div>
                </div>
            </div>
        </div>

        <!-- Perfil del Usuario -->
        <div class="content-section">
            <div class="section-title">Tu Perfil</div>
            <div class="profile-section">
                <div class="profile-item">
                    <span class="profile-label">Nombre Completo</span>
                    <span class="profile-value"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                </div>
                <div class="profile-item">
                    <span class="profile-label">Correo Electronico</span>
                    <span class="profile-value"><?php echo htmlspecialchars($_SESSION['correo']); ?></span>
                </div>
                <div class="profile-item">
                    <span class="profile-label">Usuario</span>
                    <span class="profile-value"><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
                </div>
                <div class="profile-item">
                    <span class="profile-label">Estado</span>
                    <span class="profile-value">Activo</span>
                </div>
                <button class="btn-edit-profile" onclick="alert('Funcion en desarrollo - Editar perfil')">Editar Perfil</button>
            </div>
        </div>
    </div>
</body>

</html>