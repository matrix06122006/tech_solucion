<?php

class Database {
    private static $conexion;
    private const CLAVE_MENSAJE_SESION = 'DatabaseMensajeSesion';

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

    private static function iniciarSesionSiNo() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function setMensajeSesion($estado, $mensaje) {
        self::iniciarSesionSiNo();
        $_SESSION[self::CLAVE_MENSAJE_SESION] = [
            'estado' => $estado,
            'mensaje' => $mensaje
        ];
    }

    public static function obtenerMensajeSesion($limpiar = true) {
        self::iniciarSesionSiNo();
        $mensaje = $_SESSION[self::CLAVE_MENSAJE_SESION] ?? ['estado' => '', 'mensaje' => ''];

        if ($limpiar) {
            unset($_SESSION[self::CLAVE_MENSAJE_SESION]);
        }

        return $mensaje;
    }
}
