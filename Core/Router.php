<?php

namespace MVC\Core;

use MVC\Core\Middleware\VerifyCsrfToken;

class Router
{
    protected $rutas           = [];
    public    $matches         = [];
    public    $fn              = null;

    public    Request $request;
    public    Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($url, $fn)
    {
        $this->rutas["get"][$url]       = $fn;
    }

    public function post($url, $fn)
    {
        $this->rutas["post"][$url]      = $fn;
    }

    public function put($url, $fn)
    {
        $this->rutas["put"][$url]       = $fn;
    }

    public function delete($url, $fn)
    {
        $this->rutas["delete"][$url]    = $fn;
    }

    public function evaluarRegexp($strMetodo, $uri)
    {
        $keys = array_keys($this->rutas[$strMetodo]);

        for ($i = 0, $l = count($keys); $i < $l; $i++) {
            if (preg_match('#^' . $keys[$i] . '/?$#', $uri, $this->matches)) {
                $this->fn = $this->rutas[$strMetodo][$keys[$i]];
                break;
            }
        }
    }

    public function comprobarRutas()
    {
        $uri    = $this->request->obtenerPath();
        $metodo = $this->request->obtenerMetodo();

        // This file allows us to emulate Apache's "mod_rewrite" functionality from the
        // built-in PHP web server. This provides a convenient way to test a Laravel
        // application without having installed a "real" web server software here.
        if ($uri === '/' && file_exists('./public/index.php')) {
            include './public/index.php';
            die;
        } else if ($uri !== '/' && (file_exists('./public' . $uri) || file_exists('./public' . $uri . '.php'))) {
            if (is_file('./public' . $uri)) {
                ob_start();
                include './public' . $uri;
                echo ob_get_clean();
                die;
            }

            include './public' . $uri . '.php';
            die;  
        }

        /////////////////////////////////////////////////////////////////////////////////////////
        //////////// Se obtiene el callback si es que existe y sus parámetros ///////////////////
        /////////////////////////////////////////////////////////////////////////////////////////
        $this->evaluarRegexp($metodo, $uri);

        if ($metodo === 'post') {
            session_start();
            $csrfClass = new VerifyCsrfToken();
            $csrfClass->validarTokenCsrf();
            session_write_close();
        }

        /////////////////////////////////////////////////////////////////////////////////////////
        ///////////////// Se ejecuta la función asociada a la ruta que se accede/////////////////
        /////////////////////////////////////////////////////////////////////////////////////////
        if ($this->fn) {
            if (is_array($this->fn)) {
                $this->fn[0] = new $this->fn[0]();
            }

            call_user_func_array($this->fn, array_slice($this->matches, 1));
        } else {
            $this->response->codigoRespuesta(404);
            $this->render("404.html");
        }
    }

    public function render($vista, $datos = [])
    {
        foreach ($datos as $llave => $valor) {
            $$llave = $valor;
        }

        include_once "./views/$vista";
    }

    public function renderTemplate($vistaTemplate, $vista, $datos = [])
    {
        foreach ($datos as $llave => $valor) {
            $$llave = $valor;
        }

        ob_start();
        include_once  "./views/templates/$vistaTemplate";
        $template =  ob_get_clean();

        ob_start();
        include_once  "./views/$vista";
        $content =  ob_get_clean();

        $render = str_replace("{{content}}", $template, $content);

        return $render;
    }
}


// Para poder mostrar una vista desde un controlador se debe seguir una sintaxis parecida a la siguiente:
// public function pruebaVista(Router $router) {
//     $router->render("ejemplo.php", [
//         "Hola" => "Hola como estas",
//         "Arreglo" => [1,2,3,4],
//         "titulo" => "Titulo desde el controlador"
//     ]);
// }

// Mencionar también que en las vistas las referencias a ficheros deben también cambiar para salir de la carpeta views y apuntar a la dirección deseada