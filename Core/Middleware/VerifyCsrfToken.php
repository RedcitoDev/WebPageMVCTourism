<?php

namespace MVC\Core\Middleware;

use MVC\Core\App;

class VerifyCsrfToken
{
    // Here we store the generated form key
    private $tokenCsrf;

    public function __construct()
    {
        $this->tokenCsrf = $_SESSION['token'] ?? '';
    }

    //Function to generate the form key
    private function generarTokenCsrf()
    {
        $tokenCsrf   = md5(session_id()) . bin2hex(random_bytes(16));

        //Return the hash
        return $tokenCsrf;
    }

    // Function to output the form key
    public function inputTokenCsrf()
    {
        if (isset($_SESSION['token'])) {
            if (md5(session_id()) == substr($_SESSION['token'], 0, 32)) {
                $this->tokenCsrf = $_SESSION['token'];
            } else {
                $this->tokenCsrf = $this->generarTokenCsrf();
            }
        } else {
            $this->tokenCsrf = $this->generarTokenCsrf();
        }

        //Store the form key in the session
        $_SESSION["token"] = $this->tokenCsrf;

        //Output the form key
        echo "<input type='hidden' name='Csrf' id='Csrf' value='" . $this->tokenCsrf . "' />";
    }

    // Function to output the form key
    public function metaTokenCsrf()
    {
        if (isset($_SESSION['token'])) {
            if (md5(session_id()) == substr($_SESSION['token'], 0, 32)) {
                $this->tokenCsrf = $_SESSION['token'];
            } else {
                $this->tokenCsrf = $this->generarTokenCsrf();
            }
        } else {
            $this->tokenCsrf = $this->generarTokenCsrf();
        }

        //Store the form key in the session
        $_SESSION["token"] = $this->tokenCsrf;

        //Output the form key
        echo "<meta name='Csrf' content='" . $this->tokenCsrf . "' />";
    }

    // Function that validated the form key POST data
    // CSRF tokens should be generated on the server-side. They can be generated once per user session or for each request.
    // Per-request tokens are more secure than per-session tokens as the time range for an attacker to exploit the stolen tokens is minimal.
    // However, this may result in usability concerns. For example, the "Back" button browser capability is often hindered as the previous
    // page may contain a token that is no longer valid. Interaction with this previous page will result in a CSRF false positive security 
    // event at the server. In per-session token implementation after initial generation of token, the value is stored in the session and is 
    // used for each subsequent request until the session expires.
    public function validarTokenCsrf()
    {
        $csrfClient         = getallheaders()['Csrf'] ?? $_POST['Csrf'] ?? '';

        $csrfClient         = filter_var($csrfClient, FILTER_SANITIZE_STRING);
        $this->tokenCsrf    = filter_var($this->tokenCsrf, FILTER_SANITIZE_STRING);

        //We use the old formKey and not the new generated version
        if (!hash_equals($this->tokenCsrf, $csrfClient)) {
            App::$app->response->codigoRespuesta(403);
            
            App::$app->router->render('debug_page.php', [
                "tituloPagina" => "Permiso no autorizado",
                "codigoHttp" => "403",
                "nombreCodigoHttp" => "Forbidden",
                "descripcion" => "El código de error de respuesta HTTP 403 Forbidden indica que el servidor ha entendido nuestra petición, pero se niega a autorizarla.",
            ]);

            die;
        }
    }
}
