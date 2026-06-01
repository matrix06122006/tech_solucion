<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Usuarios.php';

class UsuariosController {
    private $modeloUsuarios;

    public function __construct() {
        $this->modeloUsuarios = new Usuarios();
    }

    /**
     * Verificar conexión a la base de datos
     */
    public function verificarConexion() {
        try {
            $conexion = Database::conectar();
            return [
                'estado' => 'exitoso',
                'mensaje' => '✅ Conexión exitosa a la base de datos',
                'base_datos' => 'tech_solucionbd',
                'host' => 'localhost'
            ];
        } catch (PDOException $e) {
            return [
                'estado' => 'error',
                'mensaje' => '❌ Error de conexión',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Registrar usuario
     */
    public function registrar($nombre, $correo, $usuario, $clave) {
        if ($this->modeloUsuarios->registrar($nombre, $correo, $usuario, $clave)) {
            return ['estado' => 'exitoso', 'mensaje' => 'Usuario registrado correctamente'];
        } else {
            return ['estado' => 'error', 'mensaje' => 'El usuario o correo ya existe'];
        }
    }

    /**
     * Iniciar sesión
     */
    public function iniciarSesion($usuario, $clave) {
        $usuarioData = $this->modeloUsuarios->validarCredenciales($usuario, $clave);
        
        if ($usuarioData) {
            $_SESSION['id_usuario'] = $usuarioData['id_usuario'];
            $_SESSION['usuario'] = $usuarioData['usuario'];
            $_SESSION['nombre'] = $usuarioData['nombre'];
            $_SESSION['correo'] = $usuarioData['correo'];
            
            Database::setMensajeSesion('exitoso', 'Sesión iniciada correctamente');
            return ['estado' => 'exitoso', 'mensaje' => 'Sesión iniciada correctamente'];
        } else {
            Database::setMensajeSesion('error', 'Usuario o contraseña incorrectos');
            return ['estado' => 'error', 'mensaje' => 'Usuario o contraseña incorrectos'];
        }
    }

    /**
     * Cerrar sesión
     */
    public function cerrarSesion() {
        session_destroy();
        return ['estado' => 'exitoso', 'mensaje' => 'Sesión cerrada correctamente'];
    }
}

// Ejecutar verificación si se accede directamente
if (basename($_SERVER['PHP_SELF']) === 'usuarios.php') {
    $controller = new UsuariosController();
    $resultado = $controller->verificarConexion();
    
    if ($resultado['estado'] === 'exitoso') {
        echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px; border: 1px solid #c3e6cb;'>";
        echo "<h3>" . $resultado['mensaje'] . "</h3>";
        echo "<p>Base de datos: <strong>" . $resultado['base_datos'] . "</strong></p>";
        echo "<p>Host: <strong>" . $resultado['host'] . "</strong></p>";
        echo "</div>";
    } else {
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px; border: 1px solid #f5c6cb;'>";
        echo "<h3>" . $resultado['mensaje'] . "</h3>";
        echo "<p><strong>Error:</strong> " . $resultado['error'] . "</p>";
        echo "</div>";
    }
}
