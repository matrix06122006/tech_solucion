<?php

class Database {
    private static $conexion;

    public static function conectar() {
        if (!self::$conexion) {
            $config = require __DIR__ . '/../config/database.php';
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4";
            self::$conexion = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$conexion;
    }

    /**
     * Establecer mensaje de sesión
     */
    public static function setMensajeSesion($tipo, $mensaje) {
        $_SESSION['mensaje_tipo'] = $tipo;
        $_SESSION['mensaje'] = $mensaje;
    }

    /**
     * Obtener y limpiar mensaje de sesión
     */
    public static function obtenerMensajeSesion() {
        $tipo = $_SESSION['mensaje_tipo'] ?? null;
        $mensaje = $_SESSION['mensaje'] ?? null;
        
        unset($_SESSION['mensaje_tipo']);
        unset($_SESSION['mensaje']);
        
        return ['tipo' => $tipo, 'mensaje' => $mensaje];
    }
}
