<?php
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit;
}

// Verificar que sea cliente
if ($_SESSION['rol'] !== 'cliente') {
    header('Location: ../admin/paneladmin.php');
    exit;
}

// Procesar cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../../models/Usuarios.php';

$modeloUsuarios = new Usuarios();
$usuarioActual = $modeloUsuarios->obtenerPorId($_SESSION['id_usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Tech Solución</title>
    <link rel="stylesheet" href="../../../assets/css/styles-cliente.css">
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
                <button class="option-btn" onclick="alert('Funcion en desarrollo')">Mis Pedidos</button>
                <button class="option-btn" onclick="alert('Funcion en desarrollo')">Favoritos</button>
                <button class="option-btn" onclick="alert('Funcion en desarrollo')">Contactar Soporte</button>
            </div>
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
