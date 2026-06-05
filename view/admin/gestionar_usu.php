<?php
require_once __DIR__ . '/../../rutas.php';
Rutas::requiereAdmin();

require_once __DIR__ . '/../../controllers/usuarios.php';
require_once __DIR__ . '/../../models/Usuarios.php';

$controller = new UsuariosController();
$modeloUsuarios = new Usuarios();

$mensaje = null;
$tipo_mensaje = null;
$editarUsuario = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $usuario = trim($_POST['usuario'] ?? '');
        $clave = trim($_POST['clave'] ?? '');
        $rol = trim($_POST['rol'] ?? 'cliente');

        if (empty($nombre) || empty($correo) || empty($usuario) || empty($clave) || empty($rol)) {
            $tipo_mensaje = 'error';
            $mensaje = 'Por favor completa todos los campos para crear un usuario.';
        } else {
            if ($modeloUsuarios->crear($nombre, $correo, $usuario, $clave, $rol)) {
                $tipo_mensaje = 'success';
                $mensaje = 'Usuario creado correctamente.';
            } else {
                $tipo_mensaje = 'error';
                $mensaje = 'No se pudo crear el usuario. El usuario o correo puede estar en uso.';
            }
        }
    }

    if ($action === 'update') {
        $id_usuario = intval($_POST['id_usuario'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $rol = trim($_POST['rol'] ?? 'cliente');

        if ($id_usuario && $nombre && $correo) {
            if ($modeloUsuarios->actualizar($id_usuario, $nombre, $correo, $rol)) {
                $tipo_mensaje = 'success';
                $mensaje = 'Usuario actualizado correctamente.';
            } else {
                $tipo_mensaje = 'error';
                $mensaje = 'No se pudo actualizar el usuario.';
            }
        } else {
            $tipo_mensaje = 'error';
            $mensaje = 'Datos incompletos para actualizar el usuario.';
        }
    }
}

if (isset($_GET['editar_id'])) {
    $editarUsuario = $modeloUsuarios->obtenerPorId(intval($_GET['editar_id']));
}

if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    if ($deleteId !== $_SESSION['id_usuario']) {
        if ($modeloUsuarios->eliminar($deleteId)) {
            $tipo_mensaje = 'success';
            $mensaje = 'Usuario eliminado correctamente.';
        } else {
            $tipo_mensaje = 'error';
            $mensaje = 'No se pudo eliminar el usuario.';
        }
    } else {
        $tipo_mensaje = 'error';
        $mensaje = 'No puedes eliminar tu propia cuenta.';
    }
}

$administradores = $modeloUsuarios->obtenerAdministradores();
$empleados = $modeloUsuarios->obtenerEmpleados();
$clientes = $modeloUsuarios->obtenerClientes();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios - Tech Solución</title>
    <link rel="stylesheet" href="../../assets/css/styles-admin.css">
</head>

<body>
    <div class="header">
        <div class="header-left">
            <h1>TECH SOLUCION</h1>
            <p>Gestionar Usuarios</p>
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
            <div class="section-title"><?php echo $editarUsuario ? 'Editar Usuario' : 'Agregar Usuario'; ?></div>
            <form method="POST" class="service-form">
                <?php if ($editarUsuario): ?>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($editarUsuario['id_usuario']); ?>">
                <?php else: ?>
                    <input type="hidden" name="action" value="create">
                <?php endif; ?>

                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($editarUsuario['nombre'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="correo">Correo Electronico</label>
                    <input type="email" id="correo" name="correo" required value="<?php echo htmlspecialchars($editarUsuario['correo'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="rol">Rol</label>
                    <select name="rol" id="rol" required>
                        <option value="administrador" <?php echo isset($editarUsuario['rol']) && $editarUsuario['rol'] === 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                        <option value="empleado" <?php echo isset($editarUsuario['rol']) && $editarUsuario['rol'] === 'empleado' ? 'selected' : ''; ?>>Empleado</option>
                        <option value="cliente" <?php echo isset($editarUsuario['rol']) && $editarUsuario['rol'] === 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                    </select>
                </div>

                <?php if (!$editarUsuario): ?>
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" id="usuario" name="usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="clave">Contraseña</label>
                        <input type="password" id="clave" name="clave" required>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label>Nombre de usuario actual</label>
                        <input type="text" value="<?php echo htmlspecialchars($editarUsuario['usuario']); ?>" disabled>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn-registro"><?php echo $editarUsuario ? 'Actualizar Usuario' : 'Crear Usuario'; ?></button>
                <?php if ($editarUsuario): ?>
                    <a href="gestionar_usu.php" class="btn-action btn-edit" style="margin-left: 10px;">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="section">
            <div class="section-title">Administradores</div>
            <?php if (!empty($administradores)): ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($administradores as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                <td>
                                    <a class="btn-action btn-edit" href="gestionar_usu.php?editar_id=<?php echo $usuario['id_usuario']; ?>">Editar</a>
                                    <?php if ($usuario['id_usuario'] !== $_SESSION['id_usuario']): ?>
                                        <a class="btn-action btn-delete" href="gestionar_usu.php?delete_id=<?php echo $usuario['id_usuario']; ?>" onclick="return confirm('¿Eliminar este usuario?');">Eliminar</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay administradores registrados.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <div class="section-title">Empleados</div>
            <?php if (!empty($empleados)): ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empleados as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                <td>
                                    <a class="btn-action btn-edit" href="gestionar_usu.php?editar_id=<?php echo $usuario['id_usuario']; ?>">Editar</a>
                                    <a class="btn-action btn-delete" href="gestionar_usu.php?delete_id=<?php echo $usuario['id_usuario']; ?>" onclick="return confirm('¿Eliminar este usuario?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay empleados registrados.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <div class="section-title">Clientes</div>
            <?php if (!empty($clientes)): ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                <td>
                                    <a class="btn-action btn-edit" href="gestionar_usu.php?editar_id=<?php echo $usuario['id_usuario']; ?>">Editar</a>
                                    <a class="btn-action btn-delete" href="gestionar_usu.php?delete_id=<?php echo $usuario['id_usuario']; ?>" onclick="return confirm('¿Eliminar este usuario?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay clientes registrados.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>