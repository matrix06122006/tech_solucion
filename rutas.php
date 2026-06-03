<?php
/**
 * Sistema de Rutas Centralizado
 * Maneja los redirects según el rol del usuario
 */

session_start();

class Rutas {
    
    /**
     * Redirigir según el rol del usuario
     */
    public static function redirigirPorRol() {
        if (isset($_SESSION['id_usuario']) && isset($_SESSION['rol'])) {
            if ($_SESSION['rol'] === 'administrador') {
                header('Location: view/admin/paneladmin.php');
            } else {
                header('Location: view/cliente/indexcliente.php');
            }
            exit;
        } else {
            header('Location: view/auth/login.php');
            exit;
        }
    }

    /**
     * Verificar si el usuario está autenticado
     */
    public static function verificarAutenticacion() {
        return isset($_SESSION['id_usuario']);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public static function esAdmin() {
        return isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';
    }

    /**
     * Verificar si el usuario es cliente
     */
    public static function esCliente() {
        return isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente';
    }

    /**
     * Redirigir al login si no está autenticado
     */
    public static function requiereAutenticacion() {
        if (!self::verificarAutenticacion()) {
            header('Location: ../auth/login.php');
            exit;
        }
    }

    /**
     * Redirigir al login si no es administrador
     */
    public static function requiereAdmin() {
        self::requiereAutenticacion();
        if (!self::esAdmin()) {
            header('Location: ../cliente/indexcliente.php');
            exit;
        }
    }

    /**
     * Redirigir al login si no es cliente
     */
    public static function requiereCliente() {
        self::requiereAutenticacion();
        if (!self::esCliente()) {
            header('Location: ../admin/paneladmin.php');
            exit;
        }
    }

    /**
     * Cerrar sesión y redirigir al login
     */
    public static function cerrarSesion() {
        session_destroy();
        header('Location: ../auth/login.php');
        exit;
    }
}
