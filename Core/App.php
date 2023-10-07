<?php

namespace MVC\Core;

class App
{
    public Router $router;
    public Request $request;
    public Response $response;
    public static App $app;

    public function __construct()
    {
        $this->request      = new Request();
        $this->response     = new Response();
        $this->router       = new Router($this->request, $this->response);

        self::$app = $this;
    }

    public function iniciar()
    {
        $this->router->comprobarRutas();
    }
}
