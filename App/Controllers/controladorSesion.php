<?php
namespace Controlador;

class controladorSesion {
    public function iniciar() {
        session_start();

        $_SESSION["id"] = session_id();

        session_write_close();
    }

    public function guardarDato() {
        session_start();

        if (!isset($_SESSION["id"])) {
            $_SESSION["id"] = session_id();
        }

        $clave = array_key_first( $_GET );
        unset($_SESSION[$clave]);
        $_SESSION[$clave] = $_GET[$clave];

        // Cerrar la sesion para permitir la escritura de variables por parte de otros archivos a la sesion
        session_write_close();
    }

    public function guardarJson() {
        $payload = file_get_contents('php://input');
        $payload = json_decode($payload, true);

        session_start();

        if (!isset($_SESSION["id"])) {
            $_SESSION["id"] = session_id();
        }

        $claves = array_keys( $payload );
        for ($i=0, $l = count($claves); $i < $l; $i++) { 
            $_SESSION[$claves[$i]] = $payload[$claves[$i]];
        }
        
        // Cerrar la sesion para permitir la escritura de variables por parte de otros archivos a la sesion
        session_write_close();

        http_response_code(200);
        echo file_get_contents('php://input');
    }

    public function leerDatos() {
        session_start();

        if (!isset($_SESSION["id"])) {
            $_SESSION["id"] = session_id();
        }

        $res = $_SESSION;

        // Cerrar la sesion para permitir la escritura de variables por parte de otros archivos a la sesion
        session_write_close();

        header('Content-type: application/json');
        echo json_encode($res);
    }

    public function borrar() {
        session_start();

        if (count($_GET) > 0) {
            if ( isset($_SESSION[$_GET["delete"]]) ) {
                unset($_SESSION[$_GET["delete"]]);
            }
        } else {
            // Destruir todas las variables de sesión.
            $_SESSION = array();
        }
        
        session_write_close();
    }

    public function destruir() {
        // Inicializar la sesión.
        // Si está usando session_name("algo"), ¡no lo olvide ahora!
        session_start();

        // Destruir todas las variables de sesión.
        $_SESSION = array();

        // Si se desea destruir la sesión completamente, borre también la cookie de sesión.
        // Nota: ¡Esto destruirá la sesión, y no la información de la sesión!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finalmente, destruir la sesión.
        session_destroy();
        session_write_close();
    }
}
