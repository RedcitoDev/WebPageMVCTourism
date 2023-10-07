<?php

namespace MVC\Core;

class Response {
    public function codigoRespuesta($codigo) {
        http_response_code($codigo);
    }
}


