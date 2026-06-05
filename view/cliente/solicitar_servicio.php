<?php
require_once __DIR__ . '/../../rutas.php';
Rutas::requiereCliente();

require_once __DIR__ . '/../../models/Usuarios.php';
require_once __DIR__ . '/../../controllers/tareas.php';

$modeloUsuarios = new Usuarios();
$tareasController = new TareasController();
$usuarioActual = $modeloUsuarios->obtenerPorId($_SESSION['id_usuario']);

$mensajeSolicitud = null;
$tipoSolicitud = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_tarea = trim($_POST['tipo_tarea'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if (empty($tipo_tarea) || empty($descripcion)) {
        $tipoSolicitud = 'error';
        $mensajeSolicitud = 'Por favor completa el tipo de servicio y la descripcion.';
    } else {
        $resultadoSolicitud = $tareasController->crearTareaCliente($_SESSION['id_usuario'], $descripcion, $tipo_tarea);
        if ($resultadoSolicitud['estado'] === 'exitoso') {
            header('Location: pedidos.php');
            exit;
        } else {
            $tipoSolicitud = 'error';
            $mensajeSolicitud = $resultadoSolicitud['mensaje'] ?? 'Error al crear la solicitud.';
        }
    }
}

if (isset($_GET['logout'])) {
    Rutas::cerrarSesion();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Servicio - Tech Solución</title>
    <link rel="stylesheet" href="../../assets/css/styles-cliente.css">
</head>

<body>
    <div class="header">
        <div class="header-left">
            <h1>TECH SOLUCION</h1>
            <p>Solicitar Servicio</p>
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
            <div class="section-title">Solicitar Servicio</div>
            <?php if ($tipoSolicitud === 'error'): ?>
                <div class="alert alert-error" style="margin-bottom: 15px;"><?php echo htmlspecialchars($mensajeSolicitud); ?></div>
            <?php endif; ?>
            <form method="POST" class="service-form">
                <div class="form-group">
                    <label for="tipo_tarea">Tipo de Servicio</label>
                    <select id="tipo_tarea" name="tipo_tarea" required>
                        <option value="">Selecciona el tipo de solicitud</option>
                        <option value="Arreglar celular">Arreglar celular</option>
                        <option value="Limpiar equipo">Limpiar equipo</option>
                        <option value="Soporte remoto">Soporte remoto</option>
                        <option value="Instalar software">Instalar software</option>
                        <option value="Otra">Otra</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripcion del problema</label>
                    <textarea id="descripcion" name="descripcion" required placeholder="Describe lo que necesitas..." rows="4"></textarea>
                </div>
                <button type="submit" class="btn-registro">Enviar Solicitud</button>
            </form>
        </div>
    </div>
</body>

</html>