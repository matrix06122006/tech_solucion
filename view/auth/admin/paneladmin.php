<?php
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit;
}

// Verificar que sea administrador
if ($_SESSION['rol'] !== 'administrador') {
    header('Location: ../cliente/indexcliente.php');
    exit;
}

// Procesar cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../../controllers/usuarios.php';

$controller = new UsuariosController();
$modeloUsuarios = new Usuarios();
$usuarioActual = $modeloUsuarios->obtenerPorId($_SESSION['id_usuario']);
$todosUsuarios = $modeloUsuarios->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - Tech Solución</title>
    <link rel="stylesheet" href="../../../assets/css/styles-admin.css">
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

        <!-- Estadísticas -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($todosUsuarios); ?></div>
                <div class="stat-label">Usuarios Registrados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                    $admins = array_filter($todosUsuarios, fn($u) => $u['rol'] === 'administrador');
                    echo count($admins); 
                    ?>
                </div>
                <div class="stat-label">Administradores</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                    $clientes = array_filter($todosUsuarios, fn($u) => $u['rol'] === 'cliente');
                    echo count($clientes); 
                    ?>
                </div>
                <div class="stat-label">Clientes</div>
            </div>
        </div>

        <!-- Opciones de Administración -->
        <div class="section">
            <div class="section-title">Opciones de Administracion</div>
            <div class="admin-menu">
                <button class="menu-btn" onclick="alert('Funcion en desarrollo')">Gestionar Usuarios</button>
                <button class="menu-btn" onclick="alert('Funcion en desarrollo')">Reportes</button>
                <button class="menu-btn" onclick="alert('Funcion en desarrollo')">Configuracion</button>
                <button class="menu-btn" onclick="alert('Funcion en desarrollo')">Mensajes</button>
            </div>
        </div>

        <!-- Lista de Usuarios -->
        <div class="section">
            <div class="section-title">Lista de Usuarios del Sistema</div>
            <?php if (!empty($todosUsuarios)): ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($todosUsuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                <td>
                                    <span class="badge <?php echo $usuario['rol'] === 'administrador' ? 'badge-admin' : 'badge-cliente'; ?>">
                                        <?php echo ucfirst($usuario['rol']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-edit" onclick="alert('Editar usuario: ' + <?php echo $usuario['id_usuario']; ?>)">Editar</button>
                                        <?php if ($usuario['id_usuario'] !== $_SESSION['id_usuario']): ?>
                                            <button class="btn-action btn-delete" onclick="if(confirm('¿Eliminar usuario?')) alert('Usuario eliminado')">Eliminar</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #999; text-align: center; padding: 20px;">No hay usuarios registrados</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
