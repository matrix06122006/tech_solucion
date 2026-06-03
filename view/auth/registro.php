<?php
session_start();

require_once __DIR__ . '/../../controllers/usuarios.php';

// Si ya está autenticado, redirige a su panel
if (isset($_SESSION['id_usuario'])) {
    if ($_SESSION['rol'] === 'administrador') {
        header('Location: ../admin/paneladmin.php');
    } else {
        header('Location: ../cliente/indexcliente.php');
    }
    exit;
}

$mensaje = null;
$tipo_mensaje = null;

// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';
    $confirmar_clave = $_POST['confirmar_clave'] ?? '';

    // Validaciones
    if (empty($nombre) || empty($correo) || empty($usuario) || empty($clave)) {
        $tipo_mensaje = 'error';
        $mensaje = 'Por favor completa todos los campos';
    } elseif ($clave !== $confirmar_clave) {
        $tipo_mensaje = 'error';
        $mensaje = 'Las contraseñas no coinciden';
    } elseif (strlen($clave) < 6) {
        $tipo_mensaje = 'error';
        $mensaje = 'La contraseña debe tener al menos 6 caracteres';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $tipo_mensaje = 'error';
        $mensaje = 'El correo electrónico no es válido';
    } else {
        $controller = new UsuariosController();
        $resultado = $controller->registrar($nombre, $correo, $usuario, $clave);

        if ($resultado['estado'] === 'exitoso') {
            $tipo_mensaje = 'success';
            $mensaje = 'Registro exitoso. Redirigiendo al login...';
            header('refresh: 3; url=login.php');
        } else {
            $tipo_mensaje = 'error';
            $mensaje = $resultado['mensaje'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Tech Solución</title>
    <link rel="stylesheet" href="../../assets/css/styles-registro.css">
</head>
<body>
    <div class="container">
        <div class="registro-box">
            <div class="registro-header">
                <h1>TECH SOLUCION</h1>
                <p>Crear una nueva cuenta</p>
            </div>

            <?php if ($tipo_mensaje === 'error'): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php elseif ($tipo_mensaje === 'success'): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        required 
                        placeholder="Tu nombre completo"
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="correo">Correo Electronico</label>
                    <input 
                        type="email" 
                        id="correo" 
                        name="correo" 
                        required 
                        placeholder="tu@ejemplo.com"
                    >
                </div>

                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input 
                        type="text" 
                        id="usuario" 
                        name="usuario" 
                        required 
                        placeholder="Elige un nombre de usuario"
                    >
                </div>

                <div class="form-group">
                    <label for="clave">Contraseña</label>
                    <input 
                        type="password" 
                        id="clave" 
                        name="clave" 
                        required 
                        placeholder="Minimo 6 caracteres"
                    >
                    <div class="password-hint">Minimo 6 caracteres para mayor seguridad</div>
                </div>

                <div class="form-group">
                    <label for="confirmar_clave">Confirmar Contraseña</label>
                    <input 
                        type="password" 
                        id="confirmar_clave" 
                        name="confirmar_clave" 
                        required 
                        placeholder="Repite tu contraseña"
                    >
                </div>

                <button type="submit" class="btn-registro">Registrarse</button>
            </form>

            <div class="login-link">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesion aqui</a>
            </div>
        </div>
    </div>
</body>
</html>
