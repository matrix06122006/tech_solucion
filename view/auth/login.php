<?php
require_once __DIR__ . '/../../rutas.php';
// Manejar cierre de sesión por GET antes de cualquier redirección
if (isset($_GET['logout'])) {
    Rutas::cerrarSesion();
}

// Si ya está autenticado, redirige a su panel
if (Rutas::verificarAutenticacion()) {
    if (Rutas::esAdmin()) {
        header('Location: ../admin/paneladmin.php');
    } elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'empleado') {
        header('Location: ../Empleado/index-empleado.php');
    } else {
        header('Location: ../cliente/indexcliente.php');
    }
    exit;
}

require_once __DIR__ . '/../../controllers/usuarios.php';

$mensaje = null;
$tipo_mensaje = null;

// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';

    if (empty($usuario) || empty($clave)) {
        $tipo_mensaje = 'error';
        $mensaje = 'Por favor completa todos los campos';
    } else {
        $controller = new UsuariosController();
        $resultado = $controller->iniciarSesion($usuario, $clave);

        if ($resultado['estado'] === 'exitoso') {
            // El rol ya se guardó en la sesión en el controlador
            // Ahora solo redirigimos según el rol
            if ($_SESSION['rol'] === 'administrador') {
                header('Location: ../admin/paneladmin.php');
            } elseif ($_SESSION['rol'] === 'empleado') {
                header('Location: ../Empleado/index-empleado.php');
            } else {
                header('Location: ../cliente/indexcliente.php');
            }
            exit;
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
    <title>Login - Tech Solución</title>
    <link rel="stylesheet" href="../../assets/css/styles-auth.css">
</head>

<body>
    <div class="container">
        <div class="login-box">
            <div class="login-header">
                <h1>TECH SOLUCION</h1>
                <p>Inicia sesion en tu cuenta</p>
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
                    <label for="usuario">Usuario</label>
                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        required
                        placeholder="Ingresa tu usuario"
                        autofocus>
                </div>

                <div class="form-group">
                    <label for="clave">Contraseña</label>
                    <input
                        type="password"
                        id="clave"
                        name="clave"
                        required
                        placeholder="Ingresa tu contraseña">
                </div>

                <button type="submit" class="btn-login">Iniciar Sesion</button>
            </form>

            <div class="register-link">
                ¿No tienes cuenta? <a href="registro.php">Registrate aqui</a>
            </div>
        </div>
    </div>
</body>

</html>