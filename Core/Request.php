<?php

namespace MVC\Core;

class Request
{
    public function obtenerPath()
    {
        $uri = urldecode(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );

        $uri = ($uri[0] != '/') ? '/' . $uri : $uri;

        // Se descompone la URL por medio de la ruta de la API, para separarla de los parametros pasados en la URL
        $pathToRemove   = ($_SERVER["DOCUMENT_ROOT"] == 'C:/xampp/htdocs') ? 'lexgotravel' : '';
        $position       = strpos($uri, $pathToRemove);

        if ($position !== false) {
            $uri = substr($uri, $position + strlen($pathToRemove));
        }

        return $uri;
    }

    public function obtenerMetodo()
    {
        $metodo = strtolower($_SERVER["REQUEST_METHOD"]);

        return $metodo;
    }
}
