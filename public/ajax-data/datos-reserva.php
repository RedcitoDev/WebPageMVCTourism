<?php
    session_start();

    include_once('vendor/autoload.php');
    
    use Classes\typesHotel;

    $type         = new typesHotel();

    $post_content = file_get_contents("php://input");
    $post_content = json_decode($post_content, true);

    // Datos del titular
    $nombreTitular      = filter_var($post_content['datosTitular']['nombreTitular'],    FILTER_SANITIZE_STRING);
    $apellidoTitular    = filter_var($post_content['datosTitular']['apellidoTitular'],  FILTER_SANITIZE_STRING);

    // TODO: Verificar que el correo sea el mismo
    $correoTitular      = filter_var($post_content['datosTitular']['correoTitular'],    FILTER_VALIDATE_EMAIL);
    $confirmCorreo      = filter_var($post_content['datosTitular']['confirmCorreo'],    FILTER_VALIDATE_EMAIL);

    $regexGuionNumeros  = '/^\+?[-0-9]+/';
    $telefonoTitular    = filter_var($post_content['datosTitular']['telefonoTitular'],  FILTER_SANITIZE_STRING);
    $telefonoTitular    = preg_match($regexGuionNumeros, $telefonoTitular, $matches);
    $telefonoTitular    = $matches[0];
    
    $viajeTrabajo       = filter_var($post_content['datosTitular']['viajeTrabajo'],     FILTER_VALIDATE_BOOLEAN);
    $ofertasCorreo      = filter_var($post_content['datosTitular']['ofertasCorreo'],    FILTER_SANITIZE_STRING);
    $pedidoEspecial     = filter_var($post_content['datosTitular']['pedidoEspecial'],   FILTER_SANITIZE_STRING);

    $datosHuespedes = $post_content["datosHuespedes"];

    // Aqui no se que se hace jajaja
    if (isset($_SESSION['datoCliente'])) {
        $id = $_SESSION['datoCliente']['idCliente'];
        $_SESSION['datoCliente'] = array("nombre" => $nombreTitular, "apellido" => $apellidoTitular, "correo" => $correoTitular, "telefono" => $telefonoTitular, "idCliente" => $id);
        $datosCliente = $_SESSION['datoCliente'];
        $json = json_encode($datosCliente);
        $res = $type->UpdateClient($json);
    } else {
        $_SESSION['datoCliente'] = array("nombre" => $nombreTitular, "apellido" => $apellidoTitular, "correo" => $correoTitular, "telefono" => $telefonoTitular, "idCliente" => "");
        $datosCliente = $_SESSION['datoCliente'];
        $json = json_encode($datosCliente);
        $res = $type->AddClient($json);
        $_SESSION['datoCliente']['idCliente'] = $res;
    }
    
    $infoSesion['rateKey'] = $_SESSION['rateKey'];
    
    if ( array_key_exists('rateKey', $infoSesion) ) {
        $rateKeys          = [];
        $roomId = 0;
        
        // Primero ser hace un recorrido por el numero de ratekeys diferentes
        for($i=0; $i < count($infoSesion['rateKey']['habitaciones']); $i++) {
            $ratekey = $infoSesion['rateKey']['habitaciones'][$i]['rateKey'];
            $numeroHabitaciones = $infoSesion['rateKey']['habitaciones'][$i]['numeroHabitaciones'];

            $paxes = null;

            // Ahora se hace un recorrido por las ratekeys (Puede ser que haya mas de una igual, en estos casos no se crean nuevos elementos si no solo se suma el roomId)
            for($j=0; $j < $numeroHabitaciones; $j++) {
                $roomId++;
                
                // Código funcional
                $name = $datosHuespedes[$roomId - 1]["nombreHuespedTitular"];
                $name = explode(' ', $name);
                $paxes[] = array("roomId" => $j + 1, "type" => "AD", "name" => $name[0], "surname" => $name[count($name) -  1]);
                
                if (isset($datosHuespedes[$roomId - 1]["edadInfantes"])) {
                    for ($k=0, $l = count($datosHuespedes[$roomId - 1]["edadInfantes"]); $k < $l; $k++) { 
                        $paxes[] = array("roomId" => $j + 1, "type" => "CH", "age" => $datosHuespedes[$roomId - 1]["edadInfantes"][$k]);
                    }
                }
            }
            
            $rateKeys[] = array("rateKey" => $ratekey, "paxes" => $paxes);
        }
    }

    $datosReserva = array(  
                            "holder"            => array(   "name"      => $nombreTitular,
                                                            "surname"   => $apellidoTitular), 
                            "rooms"             => $rateKeys,
                            "clientReference"   => "LexGoTours",
                            "remark"            => $pedidoEspecial,
                            "language"          => "CAS"
                        );
                        //   "remark" => $pedidoEspecial, "paymentData" => "");

    $_SESSION['datosReserva'] = $datosReserva;
    session_write_close();

    $respuesta = json_encode($_SESSION['datosReserva']);
    echo $respuesta;
