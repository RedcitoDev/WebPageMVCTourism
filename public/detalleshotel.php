<?php 
    include_once "vendor/autoload.php";

    use MVC\Core\Middleware\VerifyCsrfToken;
    use Controlador\controladorHotel;

    $controladorHotel = new controladorHotel();

    // Primero que nada se obtiene la información de la sesion para poder recuperar datos en caso de que existan
    session_start();
    $infoSesion = $_SESSION;

    $csrfClass = new VerifyCsrfToken();
    
    ob_start();
    
    $csrfClass->metaTokenCsrf();
    $csrfMeta = ob_get_clean();

    session_write_close();

    // Se resetea el valor de todas las variables que almacenan la cantidad de pasajeros
    $totalPersonas  = 0;
    $totalAdultos   = 0;
    $totalMenores   = 0;

    //Calcula el total de personas (Por medio de los datos de la sesión)
    if (isset($infoSesion["occupancies"])) {
        for ($i=0, $l=count($infoSesion["occupancies"]); $i < $l; $i++) { 
            if (isset($infoSesion["occupancies"][$i]["adults"])) {
                $totalAdultos += $infoSesion["occupancies"][$i]["adults"];
            }
        
            if (isset($infoSesion["occupancies"][$i]["children"])) {
                $totalMenores += $infoSesion["occupancies"][$i]["children"];
            }
        }
        
        $totalPersonas = $totalAdultos + $totalMenores;
    }

    $totalPersonas      = ($totalPersonas > 0) ? $totalPersonas : 1;

    // Esta es una funcion que se utiliza para poder asignar el nombre de las facilidades a una variable, por lo que formatea 
    // adecuadamente el texto para poder ser asignado al nombre de una variable
    function formatearString($str) {
        $search         = ["(",    ")",    "/",    "-",    ".",    "á",    "é",    "í",    "ó",    "ú",     "ü",    "ñ"];
        $replace        = ["",     "",     "",     "",     "",     "a",    "e",    "i",    "o",    "u",     "u",    "n"];
        $nuevoString    = str_replace(" ", "", ucwords( str_replace($search, $replace, strtolower($str)) ) );
        
        if ($nuevoString == "") {
            $nuevoString = "undefined";
        } else if (is_numeric($nuevoString[0])) {
            $nuevoString = "_" . $nuevoString;
        }
        
        return $nuevoString;
    }

    function establecerServidor($direccion) {
        $url= ($_SERVER["DOCUMENT_ROOT"] == 'C:/xampp/htdocs') ? "http://localhost/lexgotravel/" . $direccion : "https://lexgotravel.com/" . $direccion;

        return $url;
    }

    // El codigo de hotel se obtiene mediante el parametro code de la URL
    $codigoHotel    = $_GET["code"];

    if (isset($infoSesion['Disponibilidad'])) {
        $response       = $infoSesion['Disponibilidad'];
    }

    // Si no hay informacion de disponibilidad en la sesion (Ej. se accede directo a los detalles de un hotel sin hacer una busqueda de disponibilidad)
    // entonces se borra la variable la variable de la memoria como si nunca se hubiera establecido para ocultar detalles que de lo contrario darian error
    //if($response === FALSE) {
        //unset($response);
    //}

    // Si ya se ha hecho una busqueda de disponibilidad entonces se selecciona solo el hotel seleccionado dentro de todos los hoteles de la 
    // respuesta de disponibilidad, con esta variable se trabajara toda esta pagina entera
    if (isset($response)) {
        // Con esta linea de codigo se puede buscar el indice de un valor en un arreglo multidimensional y usarlo para seleccionar un elemento que tenga un 
        // valor igual a lo que especifiquemos, en lugar de recorrer uno a uno los elementos del arreglo preguntando en cada uno por esa condicion (codigo comentado)
        // URL documentacion: https://www.php.net/manual/es/function.array-search.php
        $indiceHotelRespuesta = array_search($codigoHotel, array_column($response["hotels"]["hotels"], "code"));
        $hotelSeleccionado = $response["hotels"]["hotels"][$indiceHotelRespuesta];
        
        // foreach ($response["hotels"]["hotels"] as $hotel) {
        //     if ($hotel["code"] == $codigoHotel  ) {
        //         $hotelSeleccionado = $hotel;
        //         break;
        //     }
        // }
    }
    else{
        unset($response);
    }

    // Lo siguiente es obtener la lista de facilidades, las cuales nos ayudara para formatear de manera mas facil tanto las facilidades del hotel como las 
    // de las habitaciones
    if (true) {
        $listaFacilidades = $controladorHotel->facilidades(true);
        $listaFacilidades = json_decode($listaFacilidades, true);
    }

    $listaFacilidadesOrdenada = [];

    for ($i=0, $l=count($listaFacilidades["id"]); $i < $l; $i++) { 
        $groupCode  = $listaFacilidades["facilityGroupCode"][$i];

        $listaFacilidadesOrdenada[$groupCode]["id"][]                       = $listaFacilidades["id"][$i];
        $listaFacilidadesOrdenada[$groupCode]["code"][]                     = $listaFacilidades["code"][$i];
        $listaFacilidadesOrdenada[$groupCode]["content"][]                  = $listaFacilidades["content"][$i];
        $listaFacilidadesOrdenada[$groupCode]["facilityTypologyCode"][]     = $listaFacilidades["facilityTypologyCode"][$i];
    }

    unset($listaFacilidades);

    // Lo siguiente es hacer una consulta de todos los datos posibles del hotel seleccionado a la base de datos
    if (true) {
        $infoCompleta = $controladorHotel->informacionCompleta($codigoHotel);
        $infoCompleta = json_decode($infoCompleta, true);

        $idHotelBDD = (int)$infoCompleta["id"][0];
    }

    // Aqui se adapta la respuesta de las habitaciones en base a si hay una búsqueda efectuada o simplemente se muestren todos los datos de la BDD
    if (isset($hotelSeleccionado["rooms"])) {
        for ($i=0, $l=count($hotelSeleccionado["rooms"]); $i < $l; $i++) { 
            $codigosHabitacion[] = $hotelSeleccionado["rooms"][$i]["code"];
        }

        $habitaciones = $hotelSeleccionado["rooms"];
    } else {
        $infoHabitaciones = file_get_contents( establecerServidor("admin/privado/api/v1/hotel/habitacion?code=$idHotelBDD") );
        $infoHabitaciones = json_decode($infoHabitaciones, true);

        for ($i=0, $l=count($infoHabitaciones["roomCode"]); $i < $l; $i++) { 
            $codigosHabitacion[] = $infoHabitaciones["roomCode"][$i];
            $habitaciones[$i]["name"]           = $infoHabitaciones["name"][$i];
            $habitaciones[$i]["code"]           = $infoHabitaciones["roomCode"][$i];
        }
    }

    // Lo siguiente es obtener todas las imagenes del hotel
    if (true) {        
        $infoImagenes = $controladorHotel->imagenes($codigoHotel);
        $infoImagenes = json_decode($infoImagenes, true);
    }

    // Lo siguiente es obtener los puntos de interes del hotel
    if (true) {        
        $infoPuntosInteres = $controladorHotel->puntosInteres($codigoHotel);
        $infoPuntosInteres = json_decode($infoPuntosInteres, true);
    }

    if ($infoPuntosInteres["facilityCode"][0] === '') {
        unset($infoPuntosInteres);
    }

    // Lo siguiente es obtener las facilidades del hotel
    if (true) {        
        $infoFacilidades = $controladorHotel->hotelFacilidades($idHotelBDD, null);
        $infoFacilidades = json_decode($infoFacilidades, true);
    }

    // Datos adicionales (Si existen) Año de construccion, remodelación, numero total de habitaciones, etc.
    {
        // Facilidades de UBICACION
        if (in_array("10", $infoFacilidades["facilityGroupCode"])) {
            for ($i=0, $l=count($listaFacilidadesOrdenada["10"]["id"]); $i < $l; $i++) {
                for ($j = 0, $m=count($infoFacilidades["facilityCode"]); $j < $m; $j++) {
                    if (( $infoFacilidades["facilityCode"][$j] == $listaFacilidadesOrdenada["10"]["code"][$i] ) && ( $infoFacilidades["facilityGroupCode"][$j] == "10" )) {
                        $string = $listaFacilidadesOrdenada["10"]["content"][$i];
                        $string = formatearString($string);
                    
                        $facilidades["ubicacion"][$string]["titulo"]    = $listaFacilidadesOrdenada["10"]["content"][$i];
                        $facilidades["ubicacion"][$string]["content"]   = $infoFacilidades["number"][$j];
                    }
                }
            }
        }

        // Facilidades de Pago
        if (in_array("30", $infoFacilidades["facilityGroupCode"])) {
            for ($i=0, $l=count($listaFacilidadesOrdenada["30"]["id"]); $i < $l; $i++) {
                for ($j = 0, $m=count($infoFacilidades["facilityCode"]); $j < $m; $j++) {
                    if (( $infoFacilidades["facilityCode"][$j] == $listaFacilidadesOrdenada["30"]["code"][$i] ) && ( $infoFacilidades["facilityGroupCode"][$j] == "30" )) {
                        $string = $listaFacilidadesOrdenada["30"]["content"][$i];
                        $string = formatearString($string);
                    
                        $facilidades["pago"][$string] = $listaFacilidadesOrdenada["30"]["content"][$i];
                    }
                }
            }
        }

        // Facilidades de DISTANCIA (Metros)
        if (in_array("40", $infoFacilidades["facilityGroupCode"])) {
            for ($i=0, $l=count($listaFacilidadesOrdenada["40"]["id"]); $i < $l; $i++) {
                for ($j = 0, $m=count($infoFacilidades["facilityCode"]); $j < $m; $j++) {
                    if (( $infoFacilidades["facilityCode"][$j] == $listaFacilidadesOrdenada["40"]["code"][$i] ) && ( $infoFacilidades["facilityGroupCode"][$j] == "40" )) {
                        $string = $listaFacilidadesOrdenada["40"]["content"][$i];
                        $string = formatearString($string);
                    
                        $facilidades["distancia"][$string]["titulo"]    = $listaFacilidadesOrdenada["40"]["content"][$i];
                        $facilidades["distancia"][$string]["content"]   = $infoFacilidades["distance"][$j];
                    }
                }
            }
        }

        
        // Grupo de Equipamiento de habitaciones (habitación estándar)
        if (in_array("60", $infoFacilidades["facilityGroupCode"])) {
            for ($i=0, $l=count($listaFacilidadesOrdenada["60"]["id"]); $i < $l; $i++) {
                for ($j = 0, $m=count($infoFacilidades["facilityCode"]); $j < $m; $j++) {
                    if (( $infoFacilidades["facilityCode"][$j] == $listaFacilidadesOrdenada["60"]["code"][$i] ) && ( $infoFacilidades["facilityGroupCode"][$j] == "60" )) {
                        $string = $listaFacilidadesOrdenada["60"]["content"][$i];
                        $string = formatearString($string);
                    
                        $facilidades["equipamiento"][$string]["titulo"] = $listaFacilidadesOrdenada["60"]["content"][$i];
                    }
                }
            }
        }
        
        // Grupo de distribución de la habitacion
        if (in_array("61", $infoFacilidades["facilityGroupCode"])) {
            for ($i=0, $l=count($listaFacilidadesOrdenada["61"]["id"]); $i < $l; $i++) {
                for ($j = 0, $m=count($infoFacilidades["facilityCode"]); $j < $m; $j++) {
                    if (( $infoFacilidades["facilityCode"][$j] == $listaFacilidadesOrdenada["61"]["code"][$i] ) && ( $infoFacilidades["facilityGroupCode"][$j] == "61" )) {
                        $string = $listaFacilidadesOrdenada["61"]["content"][$i];
                        $string = formatearString($string);
                    
                        $facilidades["distribucion"][$string]["titulo"] = $listaFacilidadesOrdenada["61"]["content"][$i];
                    }
                }
            }
        }

        // Facilidades de instalaciones
        if (in_array("70", $infoFacilidades["facilityGroupCode"])) {
            for ($i=0, $l=count($listaFacilidadesOrdenada["70"]["id"]); $i < $l; $i++) {
                for ($j = 0, $m=count($infoFacilidades["facilityCode"]); $j < $m; $j++) {
                    if (( $infoFacilidades["facilityCode"][$j] == $listaFacilidadesOrdenada["70"]["code"][$i] ) && ( $infoFacilidades["facilityGroupCode"][$j] == "70" )) {
                        $string = $listaFacilidadesOrdenada["70"]["content"][$i];
                        $string = formatearString($string);
                    
                        $facilidades["instalaciones"][$string]["titulo"] = $listaFacilidadesOrdenada["70"]["content"][$i];
                    }
                }
            }
        }

        // Facilidades de restaurante
        if (in_array("71", $infoFacilidades["facilityGroupCode"])) {
            for ($i=0, $l=count($listaFacilidadesOrdenada["71"]["id"]); $i < $l; $i++) {
                for ($j = 0, $m=count($infoFacilidades["facilityCode"]); $j < $m; $j++) {
                    if (( $infoFacilidades["facilityCode"][$j] == $listaFacilidadesOrdenada["71"]["code"][$i] ) && ( $infoFacilidades["facilityGroupCode"][$j] == "71" )) {
                        $string = $listaFacilidadesOrdenada["71"]["content"][$i];
                        $string = formatearString($string);
                    
                        $facilidades["restaurante"][$string]["titulo"] = $listaFacilidadesOrdenada["71"]["content"][$i];
                    }
                }
            }
        }

        // Facilidades de negocios
        if (in_array("72", $infoFacilidades["facilityGroupCode"])) {
            for ($i=0, $l=count($listaFacilidadesOrdenada["72"]["id"]); $i < $l; $i++) {
                for ($j = 0, $m=count($infoFacilidades["facilityCode"]); $j < $m; $j++) {
                    if (( $infoFacilidades["facilityCode"][$j] == $listaFacilidadesOrdenada["72"]["code"][$i] ) && ( $infoFacilidades["facilityGroupCode"][$j] == "72" )) {
                        $string = $listaFacilidadesOrdenada["72"]["content"][$i];
                        $string = formatearString($string);
                    
                        $facilidades["negocios"][$string]["titulo"] = $listaFacilidadesOrdenada["72"]["content"][$i];
                    }
                }
            }
        }

        // Facilidades de entretenimientos
        if (in_array("73", $infoFacilidades["facilityGroupCode"])) {
            for ($i=0, $l=count($listaFacilidadesOrdenada["73"]["id"]); $i < $l; $i++) {
                for ($j = 0, $m=count($infoFacilidades["facilityCode"]); $j < $m; $j++) {
                    if (( $infoFacilidades["facilityCode"][$j] == $listaFacilidadesOrdenada["73"]["code"][$i] ) && ( $infoFacilidades["facilityGroupCode"][$j] == "73" )) {
                        $string = $listaFacilidadesOrdenada["73"]["content"][$i];
                        $string = formatearString($string);
                    
                        $facilidades["entretenimiento"][$string]["titulo"] = $listaFacilidadesOrdenada["73"]["content"][$i];
                    }
                }
            }
        }

        // Facilidades de Comidas
        if (in_array("80", $infoFacilidades["facilityGroupCode"])) {
            for ($i=0, $l=count($listaFacilidadesOrdenada["80"]["id"]); $i < $l; $i++) {
                for ($j = 0, $m=count($infoFacilidades["facilityCode"]); $j < $m; $j++) {
                    if (( $infoFacilidades["facilityCode"][$j] == $listaFacilidadesOrdenada["80"]["code"][$i] ) && ( $infoFacilidades["facilityGroupCode"][$j] == "80" )) {
                        $string = $listaFacilidadesOrdenada["80"]["content"][$i];
                        $string = formatearString($string);
                    
                        $facilidades["comida"][$string]["titulo"] = $listaFacilidadesOrdenada["80"]["content"][$i];
                    }
                }
            }
        }

        // Aspectos a tener en cuenta
        if (in_array("85", $infoFacilidades["facilityGroupCode"])) {
            for ($i=0, $l=count($listaFacilidadesOrdenada["85"]["id"]); $i < $l; $i++) {
                for ($j = 0, $m=count($infoFacilidades["facilityCode"]); $j < $m; $j++) {
                    if (( $infoFacilidades["facilityCode"][$j] == $listaFacilidadesOrdenada["85"]["code"][$i] ) && ( $infoFacilidades["facilityGroupCode"][$j] == "85" )) {
                        $string = $listaFacilidadesOrdenada["85"]["content"][$i];
                        $string = formatearString($string);
                    
                        $facilidades["aspectosEnCuenta"][$string]["titulo"] = $listaFacilidadesOrdenada["85"]["content"][$i];
                    }
                }
            }
        }
    }

    // Datos formateados para la ubicacion
    $nombreCiudad = ucwords( strtolower( $infoCompleta["city"][0] ) );
    $nombreHotel        = $infoCompleta["name"][0];
    $direccionHotel     = $infoCompleta["content"][0] . ", " . $infoCompleta["postalCode"][0] . " - " . $nombreCiudad;

    // Distancia del destino al hotel
    $latitudeFrom   = isset($infoSesion["geolocation"]["latitude"])     ? $infoSesion["geolocation"]["latitude"]    : 0;
    $longitudeFrom  = isset($infoSesion["geolocation"]["longitude"])    ? $infoSesion["geolocation"]["longitude"]   : 0;
    $latitudHotel   = $infoCompleta["latitude"][0];
    $longitudHotel  = $infoCompleta["longitude"][0];

    //Calculate distance from latitude and longitude
    $theta  = $longitudeFrom - $longitudHotel;
    $dist   = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudHotel)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudHotel)) * cos(deg2rad($theta));
    $dist   = acos($dist);
    $dist   = rad2deg($dist);
    $miles  = $dist * 60 * 1.1515;

    $txtDistancia = number_format($miles * 1.609344, 1) . ' km';
    $nombreZona = $nombreCiudad;

    $noHabitaciones     = count($codigosHabitacion);

    // TODO: Descomentar cuando se trabaje con respuestas reales

    // Se actualiza la respuesta de disponibilidad en las habitaciones marcadas con RECHECK
    // TODO: Agrupar las llamadas de check rate por grupos de 10
    $arrRecheck = [
        "rateKeyRecheck"    => [],
        "rateKey"           => [],
        "i"                 => [],
        "j"                 => []
    ];

    $contArrRecheck = 0;

    for ($i=0, $l=$noHabitaciones; $i < $l; $i++) {
        if (isset($hotelSeleccionado["rooms"][$i]["rates"])) {
            for ($j=0, $m=count($hotelSeleccionado["rooms"][$i]["rates"]); $j < $m; $j++) {
                if (isset($hotelSeleccionado["rooms"][$i]["rates"][$j]["rateType"])) {
                    if ($hotelSeleccionado["rooms"][$i]["rates"][$j]["rateType"] == "RECHECK") {
                        $rateKeyRecheck = $hotelSeleccionado["rooms"][$i]["rates"][$j]["rateKey"];

                        $arrRecheck["rateKeyRecheck"][$contArrRecheck][] = (object) ["rateKey" => $rateKeyRecheck];
                        $arrRecheck["rateKeyStr"][$contArrRecheck][]        = $rateKeyRecheck;
                        $arrRecheck["i"][$contArrRecheck][]              = $i;
                        $arrRecheck["j"][$contArrRecheck][]              = $j;
                        
                        if ((count($arrRecheck["rateKeyRecheck"][$contArrRecheck]) % 10) === 0) {
                            $contArrRecheck = $contArrRecheck + 1;
                        }
                    }
                }
            }
        }
    }

    for ($i = 0, $l = count($arrRecheck["rateKeyRecheck"]); $i < $l; $i++) {
        $postRecheck = json_encode(["rooms" => $arrRecheck["rateKeyRecheck"][$i], "language" => "CAS"]);
        
        // Your API Key and secret from developer.hotelbeds.com
        $apiKey = HOTELBEDS_API_KEY;  
        $Secret = HOTELBEDS_SECRET;
        
        // Signature is generated by SHA256 (Api-Key + Secret + Timestamp (in seconds))
        $signature = hash("sha256", $apiKey.$Secret.time());
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => HOTELBEDS_API_ENDPOINT . "hotel-api/1.0/checkrates",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postRecheck,
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "accept-encoding: gzip",
                "api-key: ". $apiKey ."",
                "cache-control: no-cache",
                "content-type: application/json",
                "x-signature: ". $signature .""
            ),
        ));

        $responseRecheck = json_decode(curl_exec($curl), true);
        
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            // TODO: Tratar el error para que se trata de actualizar el precio con otra peticion
            echo "cURL Error #:" . $err;
        }
        
        if (isset($responseRecheck["hotel"]["rooms"][0]["rates"])) {
            for ($j = 0, $m = count($responseRecheck["hotel"]["rooms"][0]["rates"]); $j < $m; $j++) {
                $rateKeyActual = $responseRecheck["hotel"]["rooms"][0]["rates"][$j]["rateKey"];
    
                $indexRateKey = array_search($rateKeyActual, $arrRecheck["rateKeyStr"][$i]);
    
                if ($indexRateKey === false) {
                    continue;
                } 
                
                // echo $arrRecheck["rateKeyStr"][$i][$indexRateKey];
                $i_original = $arrRecheck["i"][$i][$indexRateKey];
                $j_original = $arrRecheck["j"][$i][$indexRateKey];
    
                $llavesRecheckRR = array_keys($responseRecheck["hotel"]["rooms"][0]["rates"][$j]);
    
                // Se recorren todas las llaves de la respuesta para actualizar en la respuesta de disponibilidad
                for ($k=0, $n=count($llavesRecheckRR); $k < $n; $k++) { 
                    // TODO: Darle tratamiento a rateComments al momento de mostrar los detalles del hotel
                    $hotelSeleccionado["rooms"][$i_original]["rates"][$j_original][$llavesRecheckRR[$k]] = $responseRecheck["hotel"]["rooms"][0]["rates"][$j][$llavesRecheckRR[$k]];
                }
            }
        }
    }

    // Se actualiza la respuesta de recheck en la respuesta de disponibilidad original que se tiene guardada en la sesion
    if ($contArrRecheck > 0) {
        $response["hotels"]["hotels"][$indiceHotelRespuesta] = $hotelSeleccionado;
    }

    // Finalmente se guardan todos los datos cambiados en la sesion para que no se tenga que volver a mandar la solicitud cada vez que se accede a cierto hotel
    if (isset($response)){
        session_start();
        $_SESSION['Disponibilidad'] = $response;
        session_write_close();
    }
    
    // Estrellas del hotel
    $estrellas = $infoCompleta["categoryCode"][0];

    if ($estrellas == "MINI") {
        $estrellas = 2;
    } else if (($estrellas == "BB") || ($estrellas == "BB3")) {
        $estrellas = 3;
    } else if (($estrellas == "SUP") || ($estrellas == "BOU") || ($estrellas == "BB4")) {
        $estrellas = 4;
    } else if (($estrellas == "HIST") || ($estrellas == "BB5")) { 
        $estrellas = 5;
    } else {
        $matches = [];

        preg_match_all("/\d/", $estrellas, $matches);
        $estrellas = implode(".", $matches[0]);
        $estrellas = round( $estrellas, 0, PHP_ROUND_HALF_UP);
    }                                    

    $estrellas = ($estrellas > 5) ? 5 : $estrellas;

    // Convertidor de divisas
    if (isset($hotelSeleccionado)) {
        require_once('utils/currencyConverter.php');
                        
        $FromCurrency = $hotelSeleccionado["currency"];
        $ToCurrency = 'MXN';
        $amount = 1;

        if ($FromCurrency === "MXN") {
            $valorDivisaHotel = 1;
        } else {
            $converter = new currencyConverter($amount, $ToCurrency, $FromCurrency);
            $valorDivisaHotel =  $converter->getUpdate();
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        if ( $_SERVER["DOCUMENT_ROOT"] == 'C:/xampp/htdocs' ) {
            $servidor = "http://localhost/lexgotravel/";
        } else {
            $servidor = "https://lexgotravel.com/";
        }
    ?>
    <base href="<?php echo $servidor ?>public/" >

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $csrfMeta; ?>

    <title>Detalles | <?php echo $nombreHotel; ?></title>
    <?php $useragent = $_SERVER['HTTP_USER_AGENT'];?>
    
    <?php include_once("links.php") ?>
</head>
<body>
    <!--HEADER-->
    <link rel="stylesheet" href="js/venobox/venobox.css" type="text/css" media="screen" />
    <?php include_once("header.php") ?>
    <!--FIN HEADER-->
    
    <!--BOODY-->

        <!--PASOS-->
        <div class="container bg-white cont-pasos">
            <div class="row w-100 m-0 p-0">
                <div class="col-md-3 text-center p-4">
                    
                    <div class="p-2 d-flex align-items-center  justify-content-center">
                        <div id="pasos-icon-1" class="p-3" style="width: 81px; height: 81px; border-radius: 50px; background: #228ce3; box-shadow: 0px 0px 7px 1px #00000026;""><img src="medios/img/iconoseleccionblanco.png" style="width: 30px; margin: 6px;"></div>
                    </div>
                    
                    <p class="fw-bold text-center mt-3" style="color: #228ce3;">Selección del destino</p>    
                </div>
                <div class="col-md-3 text-center p-4">
                    
                    <div class="p-2 d-flex align-items-center  justify-content-center">
                        <div id="pasos-icon-2" class="p-3" style="width: 81px; height: 81px; border-radius: 50px; background: #228ce3; box-shadow: 0px 0px 7px 1px #00000026;"><img src="medios/img/iconodescripcionblanco.png" style="width: 30px; margin: 10px;"></div>
                    </div>    
                    
                    <p class="fw-bold text-center mt-3" style="color: #228ce3;">Descripción</p>
                </div>
                <div class="col-md-3 text-center p-4">
                    
                    <div class="p-2 d-flex align-items-center  justify-content-center">
                        <div id="pasos-icon-3" class="p-3" style="width: 81px; height: 81px; border-radius: 50px; background: #f8f8f8; box-shadow: 0px 0px 7px 1px #00000026;"><img src="medios/img/Reservacion.png" style="width: 30px; margin: 10px;"></div>
                    </div>
                    
                    <p class="fw-bold text-center mt-3" style="color: #228ce3;">Reservación</p>
                </div>
                <div class="col-md-3 text-center p-4">
                    <div class="p-2 d-flex align-items-center  justify-content-center">
                        <div id="pasos-icon-4" class="p-3" style="width: 81px; height: 81px; border-radius: 50px; background: #f8f8f8; box-shadow: 0px 0px 7px 1px #00000026;"><img src="medios/img/Confirmacion.png" style="width: 30px; margin: 10px;"></div>
                    </div>
                    <p class="fw-bold text-center mt-3" style="color: #228ce3;">Confirmación</p>
                </div>
            </div>
        </div>
        <!--FIN PASOS-->

        <!--FILTRO-->
        <div class="container py-4" id="filtro2">
        <form id="formFiltro" action="">
                <div class="row px-5">
                    
                    <style>
                        .puntero{
                            cursor: pointer;
                        }

                        .ocultar{
                            display: none;
                        }
                        
                        #location::placeholder {
                            color: white;
                        }
                    </style>
                    <div class="col-md-3 opt-filter">
                        <!-- <select id="location">
                            <option value="" selected disabled>Seleccionar destino</option>
                            
                        </select> -->

                        <input id="location" placeholder="Seleccionar destino" style="background-color:transparent; border: none; color: white;"/>
                    </div>
                    <div class="dropdown col-md-3 text-center">
                        <nav role="navigation" class="nav-hab">
                            <div>
                                <ul class="nav-items m-0" style="padding:1px;">
                                    <li class="nav-item dropdown">
                                        <a href="" class="nav-link"><p style="color:White !important">
                                            <div class="mx-auto d-flex align-items-center justify-content-center text-center">
                                                <img class="mx-2" src="medios/img/mdb/iconohabitacionmotor.png" alt="" style="height: 19px;"><p id="cantidadHabitaciones" class="text-white">1</p>
                                                <img class="mx-2" src="medios/img/mdb/iconoadultomotor.png" alt="" style="height: 19px;"><p id="cantidadAdultos" class="text-white">1</p>
                                                <img class="mx-2" src="medios/img/mdb/iconomenormotor.png" alt="" style="height: 19px;"><p id="cantidadMenores" class="text-white">0</p>
                                            </div>
                                        </p></a>
                                        <nav class="submenu">
                                            <div id="submenu-qty" class="submenu-qty">
                                                <ul class="submenu-items clonar">
                                                <li class="submenu-item">
                                                        <a href="#" class="submenu-link">
                                                            <div class="">
                                                                <div class="row col-12 ">
                                                                    <div class="col-sm-5 ">
                                                                        <h3 for="" class="color" style="color:#228ce3 !important;">Adultos</h3>
                                                                        <label for="" class="white" style="font-size: 12px;">Desde los 18 años</label>
                                                                    </div>  
                                                                    <div class="col-sm-3 inputnumber">
                                                                        <div class="number-input">
                                                                            <button class="minus minusAdults"></button>
                                                                            <input class="quantity" min="1" max="8" name="quantity" value="1" type="number">
                                                                            <button class="plus plusAdults"></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li> 
                                                    <li class="submenu-item">
                                                        <a href="#" class="submenu-link">
                                                            <div class="">
                                                                <div class="row col-12">
                                                                    <div class="col-sm-5">
                                                                        <h3 for="" class="color" style="color:#228ce3 !important;">Menores</h3>
                                                                        <label for="" class="white" style="font-size: 12px;">Menores de 17 años</label>
                                                                    </div>
                                                                    <div class="col-sm-3 inputnumber">
                                                                        <div class="number-input">
                                                                            <button class="minus minusMinors"></button>
                                                                            <input class="quantity" min="0" name="quantity" value="0" type="number">
                                                                            <button class="plus plusMinors"></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="d-flex">
                                                <button class="btn btn-primary" id="agregar">Agregar campo +</button>
                                            </div>
                                        </nav>
                                    </li> 
                                </ul> 
                            </div>
                            
                        </nav>
                    </div>
                    <div class="col-md-3 text-center">
                        <input id="datefilter" class="form-control op-fil" type="text" placeholder="Check in - Check out" aria-label="Search" name="datefilter" required autocomplete="off">
                    </div>
        
                        <div class="col-md-3 text-center d-grid gap-2">
                            <input id="btn-filtro" type="submit" class="btn text-light rounded-0 boton-busc" value="Actualizar">
                        </div>
                                        
                </div>
            </form>
        </div>
        <!--FIN FILTRO-->
        
        <!--INFO HOTELES-->
        <div class="container p-2 mb-2">
            <div class="row contenedorPrincipalDetalles" style="box-shadow: 2px 4px 5px 1px #0000003d;">
                
                <!--FOTOS-->
                <div class="p-0 m-0 fotos-hoteles" style="background:#f8f8f8;">
                    <div class="row w-100 m-0 p-0" data-bs-toggle="modal" data-bs-target="#modalFotosHotel" style="border-bottom: 5px solid #228ce3;">
                        <div class="col-6 p-5 id-hotel-img" class="btn btn-primary" style="background-image: url(<?php echo "https://photos.hotelbeds.com/giata/bigger/" . $infoImagenes["path"][0]?>); background-position: center; background-size: cover;"></div>
                        <div class="col-6 p-0">
                            <div class="id-hotel-img" class="p-5" style="background-image: url(<?php echo "https://photos.hotelbeds.com/giata/bigger/" . $infoImagenes["path"][1]?>); height: 145px; background-position: center; background-size: cover;"></div>
                            <div class="id-hotel-img" class="p-5" style="background-image: url(<?php echo "https://photos.hotelbeds.com/giata/bigger/" . $infoImagenes["path"][2]?>); height: 145px; background-position: center; background-size: cover;"></div>
                            <div class="id-hotel-img" class="p-5" style="background-image: url(<?php echo "https://photos.hotelbeds.com/giata/bigger/" . $infoImagenes["path"][3]?>); height: 145px; background-position: center; background-size: cover;"></div>
                        </div>
                    </div>
                </div>  
                <!--FIN FOTOS-->
                
                <!--FOTOS MOVIL-->
                <div class="p-0 m-0 fotos-hoteles-movil" style="background:#f8f8f8; display:none">
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active p-5" style="background-image: url(<?php echo "https://photos.hotelbeds.com/giata/bigger/" . $infoImagenes["path"][1]?>); height: 239px; background-position: center; background-size: cover;">
                            </div>
                            <div class="carousel-item p-5" style="background-image: url(<?php echo "https://photos.hotelbeds.com/giata/bigger/" . $infoImagenes["path"][2]?>); height: 239px; background-position: center; background-size: cover;">
                            </div>
                            <div class="carousel-item p-5" style="background-image: url(<?php echo "https://photos.hotelbeds.com/giata/bigger/" . $infoImagenes["path"][3]?>); height: 239px; background-position: center; background-size: cover;">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>  
                <!--FIN FOTOS MOVIL-->

                <div class="col-md-8 m-0 p-0" >
                    <div style="background:#f8f8f8;">
                        <div class="w-100 p-3" style="background:#f8f8f8;">
                            <h2 class="tit1 text-center p-3"><?php echo $nombreHotel ?></h2>
                            <?php 
                                if ($infoCompleta["chainCode"][0] != "") {
                                    $cadenaHotelera = $controladorHotel->leerCadenas("description", "code,{$infoCompleta["chainCode"][0]}");
                                    $cadenaHotelera = json_decode($cadenaHotelera, true);
                                    
                                    $templateCadena = "<div class='d-flex justify-content-center flex-wrap'>
                                                        <div class='alert alert-secondary' role='alert' style='width: 100%;'>
                                                            <p class='text-center'><strong>Cadena Hotelera: {$cadenaHotelera['description'][0]}</strong></p>
                                                        </div>
                                                    </div>";
    
                                    echo $templateCadena;
                                }
                            ?>
                            <div class="d-flex justify-content-center flex-wrap">
                                <p class=" mx-1" style="color: #228ce3;"><?php echo $direccionHotel ?> | </p>
                                
                                <?php
                                    // TODO: Poner el metodo que reemplaza las & por AND
                                    echo "<p class='text-decoration-underline mx-1' style='color: #228ce3 !important;'><a data-gall='iframe' class='venoboxframe' data-vbtype='iframe' href='https://www.google.com/maps/embed/v1/search?key=AIzaSyCS7kK67VZxjgmzINsI1_C4zamwkNaUaD4&q=$nombreHotel+$direccionHotel&center=$latitudHotel,$longitudHotel&zoom=18'> Mostrar en el mapa</a> | </p>";

                                    echo "<p class='mx-1' style='color: #228ce3;'>a $txtDistancia de $nombreZona</p>";
                                ?>  
                            </div>
                        </div>

                        <?php
                            if (isset($facilidades["ubicacion"])) {
                                $llaves = array_keys($facilidades["ubicacion"]);
                                $infoAdicional = '';

                                for ($i=0, $l=count($facilidades["ubicacion"]); $i < $l; $i++) { 
                                    $titulo     = $facilidades["ubicacion"][$llaves[$i]]["titulo"];
                                    $content    = $facilidades["ubicacion"][$llaves[$i]]["content"];
                                    $infoAdicional .= "<div class='alert alert-primary' role='alert' style='width: fit-content; margin-inline: 1rem;'>
                                                            <p class='text-center'><strong>$titulo: $content</strong></p>
                                                        </div>";
                                }

                                $template = "<div class='d-flex justify-content-center flex-wrap'>
                                                $infoAdicional
                                            </div>";

                                echo $template;
                            }
                        ?>

                        <div class="row w-100 m-0 p-0">
                            <div class="m-0 p-0">
                                <div  class="row text-center p-2 m-0 w-100 text-white" style="background:#228ce3; border:2px solid #228ce3;">
                                    <div class="m-0 p-0 d-flex align-items-center  justify-content-center">
                                        <h3 class="text-center p-2 text-white" style="font-size: 24px;">Puntuación general</h3> 
                                        
                                        <div class="d-flex justify-content-center">
                                            <div class="mx-2 text-center">
                                                <?php
                                                    // Actualizar la condicion de las estrellas con el metodo que se encuentra en 'motordebusqueda'
                                                    for ($i=0; $i < $estrellas; $i++) { 
                                                        echo "<img class='estrella-1' src='medios/img/Estrellablanca.png' alt=''>";
                                                    }
                                                ?>
                                                <img class="estrella-1" src="medios/img/Manolike.png" alt="">
                                            </div>
                                            <h4 class="text-white my-0">9.8</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-5">
                            <h4 class="text-center" style="color:#228ce3 !important;">Descripción y características</h4>
                            <br>
                                                
                            <p style="text-align: justify;"><?php echo $infoCompleta["description"][0]?></p><br>
                        </div>
                    </div> 

                    <!--"CUENTA CON"-->
                    <div class="row w-100 m-0 p-0 secc-det-hotel">
                        <div class="m-0 p-0">
                            <div  class="row text-center p-2 m-0 w-100 text-white" data-bs-toggle="modal" data-bs-target="#exampleModal3" style="background:#228ce3; border:2px solid #228ce3; cursor: pointer;">
                                <div class="m-0 p-0 d-flex align-items-center  justify-content-center">
                                    <h3 class="text-center p-2 text-white text-decoration-underline" style="font-size: 24px;">Cuenta con:</h3> 

                                    <?php
                                        $txtServicios   = "";
                                        $arrayServicios = [];
                                        
                                        $llaves = array_keys($facilidades["equipamiento"]);
                                        for ($i=0, $l=count($facilidades["equipamiento"]); $i < $l; $i++) { 
                                            if ($i > 4) { 
                                                break;
                                            }

                                            $arrayServicios[] = (strlen($facilidades["equipamiento"][$llaves[$i]]["titulo"]) > 15) ? substr($facilidades["equipamiento"][$llaves[$i]]["titulo"], 0, 15) . "..." : $facilidades["equipamiento"][$llaves[$i]]["titulo"];
                                        }
                                        
                                        /*
                                        $llaves = array_keys($facilidades["equipamiento"]);
                                        while (count($arrayServicios) < 5) {
                                            $indice = rand(0, count($llaves) - 1);

                                            if (strlen( $facilidades["equipamiento"][$llaves[$indice]]["titulo"] ) < 20) {
                                                if ( !in_array($facilidades["equipamiento"][$llaves[$indice]]["titulo"], $arrayServicios) ) {
                                                    $arrayServicios[] = $facilidades["equipamiento"][$llaves[$indice]]["titulo"];
                                                }
                                            }
                                        }
                                        */

                                        for ($i=0, $l=count($arrayServicios); $i < $l; $i++) { 
                                            $txtServicios .= "<img class='estrella-1 me-2' src='medios/img/Isotipohaciadelante.png' alt=''><p class='me-3'>$arrayServicios[$i]</p>";
                                        }
                                    ?>
                                    
                                    <div class="d-flex justify-content-center">
                                        <div class="mx-2 text-center d-flex">
                                            <?php echo $txtServicios; ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--FIN "CUENTA CON"-->

                    <!--"CUENTA CON" MOVIL-->
                    <div class="row w-100 m-0 p-0 secc-det-hotel-movil" style="display:none;">
                        <div class="m-0 p-0">
                            <div  class="row text-center p-2 m-0 w-100 text-white" data-bs-toggle="modal" data-bs-target="#exampleModal3" style="background:#228ce3; border:2px solid #228ce3; cursor: pointer;">
                                <div class="m-0 p-0  ">
                                    <h3 class="text-center p-2 text-white text-decoration-underline" style="font-size: 24px;">Cuenta con:</h3> 

                                    <?php
                                        $txtServicios   = "";
                                        $arrayServicios = [];
                                        
                                        $llaves = array_keys($facilidades["equipamiento"]);
                                        for ($i=0, $l=count($facilidades["equipamiento"]); $i < $l; $i++) { 
                                            if ($i > 4) { 
                                                break;
                                            }

                                            $arrayServicios[] = (strlen($facilidades["equipamiento"][$llaves[$i]]["titulo"]) > 15) ? substr($facilidades["equipamiento"][$llaves[$i]]["titulo"], 0, 15) . "..." : $facilidades["equipamiento"][$llaves[$i]]["titulo"];
                                        }

                                        for ($i=0, $l=count($arrayServicios); $i < $l; $i++) { 
                                            $txtServicios .= "
                                            <div class='row'>
                                                <div class='col-2'>
                                                    <img class='estrella-1 me-2' src='medios/img/Isotipohaciadelante.png' alt=''>
                                                </div>
                                                <div class='col-10'>
                                                    <p class='me-3'>$arrayServicios[$i]</p>
                                                </div>
                                            
                                            </div>";
                                        }
                                    ?>
                                    
                                    <div class="">
                                        <div class="mx-2 ">
                                            <?php echo $txtServicios; ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--FIN "CUENTA CON" MOVIL-->

                    <div class="row w-100 m-0 p-2">
                        <div class="col-md-6 p-0">
                            <div class="p-3">
                                <h3 class="text-center" style="color:#228ce3 !important;">Condiciones</h3><br>
                                <p>Si vas a hacer una reserva a partir del 6 de abril, te recomendamos tener en cuenta las 
                                restricciones por coronavirus (COVID-19) y las medidas del Gobierno responsable. Si no 
                                reservas una tarifa flexible, quizá no puedas recibir un reembolso. Las peticiones de 
                                cancelación las gestiona el alojamiento según las condiciones de la reserva y los 
                                derechos del consumidor correspondientes, en caso de que sean aplicables. En 
                                momentos de incertidumbre, te recomendamos reservar una opción con cancelación 
                                gratis. Así, si cambias de planes, podrás cancelar la reserva dentro del plazo establecido 
                                sin ningún coste
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 p-0">
                            <div class="p-3">  
                                <h3 class="text-center" style="color:#228ce3 !important;">Salud y seguridad</h3><br>
                                <p>El hotel ofrecerá prueba gratuita de antígenos COVID-19 a aquellos huéspedes que 
                                regresen a su país de residencia, la cual podrán realizarse en la propiedad. Igualmente 
                                el hotel ofrecerá en alianza con Europ Assistance, de manera gratuita con todas sus 
                                reservas, una póliza de seguro, que contará con una cobertura que incluye, entre otras 
                                cosas, asistencia ante posibles casos positivos en COVID-19 durante la estancia. Para 
                                más información, contactar directamente a Lex Go Travel. Aplican restricciones.<br>
                                Cargos a pagar en el alojamiento:<br>
                                Tasa eco-ambiental: MXN$ 26 (Peso mexicano) Por habitación por día.
                                </p>
                            </div>
                            <br>
                        </div>
                    </div>

                    <div class="w-100 mb-1">
                        <div class="row text-center p-2 m-0 w-100 text-white" style="background:#228ce3; border:2px solid #228ce3;">
                            <div class="m-0 p-0">
                                <h3 class="text-center p-2 text-white" style="font-size: 24px;">Opciones de habitación</h3> 
                            </div>
                        </div>
                        
                       
                    </div>

                    <?php
                        

                        // Ahora se obtienen las tipologias de las facilidades para automatizar tambien que campos leer de cada uno (numero, fechas, booleanos, etc.)
                        if (true) {
                            $listaTipologias = $controladorHotel->facilidadesTipologias(true);
                            $listaTipologias = json_decode($listaTipologias, true);
                        }

                        // Lo siguiente es hacer una consulta de las habitaciones del hotel en la BDD
                        if (true) {    
                            $infoHabitaciones = $controladorHotel->leerHabitaciones($idHotelBDD);
                            $infoHabitaciones = json_decode($infoHabitaciones, true);
                        }

                        // Ahora sacamos las capacidades de cada habitacion
                        for ($i=0, $l=count($habitaciones); $i < $l; $i++) { 
                            // Con esta linea buscamos el indice de la habitacion en el arreglo que contiene todas las habitaciones, no solo las que estan disponibles,
                            // y en base a si se ha hecho una busqueda o no se utilizaran todas las habitaciones o solo las disponibles
                            $indice = array_search($habitaciones[$i]["code"], $infoHabitaciones["roomCode"]);

                            $habitaciones[$i]["maxPax"]         = $infoHabitaciones["maxPax"][$indice];
                            $habitaciones[$i]["maxAdults"]      = $infoHabitaciones["maxAdults"][$indice];
                            $habitaciones[$i]["maxChildren"]    = $infoHabitaciones["maxChildren"][$indice];
                        }

                        // Ahora se obtienen las facilidades de la habitacion (En la mayoria de los casos solo se devuelve la superficie en m2 y no de habitaciones)
                        for ($i=0, $l=count($infoHabitaciones["id"]); $i < $l; $i++) {
                            $codigoHabitacion = $infoHabitaciones["roomCode"][$i];
                            
                            if (in_array($codigoHabitacion, $codigosHabitacion)) {
                                $idHabitacionBDD = $infoHabitaciones["id"][$i];

                                $facilidadesHabitacion = $controladorHotel->facilidadesHabitacion($idHabitacionBDD);
                                $infoHabitacionesFacilidades[] = json_decode($facilidadesHabitacion, true);
                            }
                        }

                        if (isset($infoHabitacionesFacilidades)) {
                            // Aqui se crea la informacion de las habitaciones que se mostrara en la parte de caracteristicas
                            for ($i=0, $l=count($infoHabitacionesFacilidades); $i < $l; $i++) { 
                                if ($infoHabitacionesFacilidades[$i] != NULL) {
                                    for ($j=0, $m=count($infoHabitacionesFacilidades[$i]["facilityGroupCode"]); $j < $m; $j++) { 
                                        $facilityCodeTemp       = $infoHabitacionesFacilidades[$i]["facilityCode"][$j];
                                        $facilityGroupCodeTemp  = $infoHabitacionesFacilidades[$i]["facilityGroupCode"][$j];
                        
                                        $indiceTemp = array_search($facilityCodeTemp, $listaFacilidadesOrdenada[$facilityGroupCodeTemp]["code"]);
                        
                                        $typologyCode = $listaFacilidadesOrdenada[$facilityGroupCodeTemp]["facilityTypologyCode"][$indiceTemp];
                        
                                        if ($listaTipologias["numberFlag"][array_search($typologyCode, $listaTipologias["code"])]) {
                                            $string = $listaFacilidadesOrdenada[$facilityGroupCodeTemp]["content"][$indiceTemp];
                                            $string = formatearString($string);
                        
                                            $facilidades["habitacion"][$i][$string]["titulo"]     = $listaFacilidadesOrdenada[$facilityGroupCodeTemp]["content"][$indiceTemp];
                                            $facilidades["habitacion"][$i][$string]["content"]    = $infoHabitacionesFacilidades[$i]["number"][$j];
                                        }
                                    }    
                                }
                            }
                        }

                        if (isset($idHabitacionBDD)) {
                            // Lo siguiente es obtener los tipos de servicio de comida ofrecidos por el hotel
                            if (true) {        
                                $infoServicios = $controladorHotel->leerServicios($idHabitacionBDD);    
                                $infoServicios = json_decode($infoServicios, true);
                        
                                if ($infoServicios != NULL) {
                                    for ($i=0, $l=count($infoServicios["description"]); $i < $l; $i++) { 
                                        $infoServicios["description"][$i] = ucwords(mb_strtolower($infoServicios["description"][$i]));
                                    }
                                }
                            }
                        }
                    ?>

                    <?php
                    
                        for ($i=0, $l=$noHabitaciones; $i < $l; $i++) {
                            $nombreHabitacion          = $habitaciones[$i]["name"];
                            $codigoHabitacion          = $habitaciones[$i]["code"];

                            $tarjetasAceptadasEncode = "";
                            $tarjetasAceptadas = [];

                            if (isset($facilidades["pago"])) {
                                for ($j=0, $m=count($facilidades["pago"]); $j < $m; $j++) { 
                                    $tarjetasAceptadas[] = $facilidades["pago"][array_keys($facilidades["pago"])[$j]];
                                }

                                $tarjetasAceptadasEncode = base64_encode( json_encode($tarjetasAceptadas) );
                            }
                            
                            for ($j=0, $m = count($infoImagenes["roomCode"]); $j < $m; $j++) {   
                                if ($infoImagenes["roomCode"][$j] == $codigoHabitacion) {
                                    $imgHabitacion[$codigoHabitacion][] = "https://photos.hotelbeds.com/giata/bigger/" . $infoImagenes["path"][$j];
                                }
                            }

                            $urlImg = isset($imgHabitacion[$codigoHabitacion][0]) ? $imgHabitacion[$codigoHabitacion][0] : "medios/img/not-found.png";
                            $idModal = str_replace(".", "_", $codigoHabitacion);

                            $template = "<div class='row w-100 m-0 p-0 tarjetaDetalleHabitacion' style='border: 2px solid #f8f8f8;'>
                                            <div class='col-md-6 p-5' style='background:#f8f8f8 !important;'>
                                                <h4 class='text-center'>$nombreHabitacion</h4>
                                                <div class='bg-red' data-bs-toggle='modal' data-bs-target='#modal$idModal'>
                                                    <p class='text-center text-white p-1' style='background: #228ce3; cursor: pointer;'>Ver detalle de habitación</p>
                                                </div>
                                            </div>
                                            <div data-bs-toggle='modal' data-bs-target='#modal$idModal' class='col-md-6 p-5 m-0 id-hotel-img' style='background-image: url($urlImg); background-size: cover; background-position: center;'>
                                                
                                            </div>";
                            
                            $areaHabitacion = "N/A";

                            if (isset($hotelSeleccionado)) {
                                if (isset($infoHabitacionesFacilidades)) {
                                    if (isset($infoHabitacionesFacilidades[$i]["facilityCode"])) {
                                        for ($j=0, $m=count($infoHabitacionesFacilidades[$i]["facilityCode"]); $j < $m; $j++) {
                                            if ($infoHabitacionesFacilidades[$i]["facilityCode"][$j] == "295") {
                                                $areaHabitacion     = $infoHabitacionesFacilidades[$i]["number"][$j] . " M2";
                                                break;
                                            }
                                        }
                                    }
                                }

                                // En esta parte se rellenan las facilidades ofrecidas por la habitación
                                $txtEquipamiento = "<ul class='list-info-hotel p-4' style='list-style-image: url(medios/img/isotipobiñetalextravel1.png) !important;'>";

                                if (isset($facilidades["habitacion"][$i])) {
                                    $llaves = array_keys($facilidades["habitacion"][$i]);
                                    for ($j=0, $m=count($llaves); $j < $m; $j++) { 
                                        if (isset($facilidades["habitacion"][$j][$llaves[$j]]["content"])) {
                                            $titulo = $facilidades["habitacion"][$i][$llaves[$j]]["titulo"] . ": " . $facilidades["habitacion"][$i][$llaves[$j]]["content"];
                                            $txtEquipamiento .= "<li class='listdetalles'>$titulo</li>";
                                        } else {
                                            $titulo = $facilidades["habitacion"][$i][$llaves[$j]]["titulo"];
                                            $txtEquipamiento .= "<li class='listdetalles'>$titulo</li>";
                                        }
                                    }
                                }

                                $llaves = array_keys($facilidades["equipamiento"]);
                                for ($j=0, $m=count($llaves); $j < $m; $j++) {
                                    if ($j > 6) {
                                        break;
                                    }
                                    
                                    $titulo = $facilidades["equipamiento"][$llaves[$j]]["titulo"];
                                    $txtEquipamiento .= "<li class='listdetalles'>$titulo</li>";
                                }
                
                                $txtEquipamiento .= "</ul>";

                                // Se lee el numero maximo de huespedes por habitacion (Esta informacion se formatea al inicio del script pero siempre se encuentra en $habitaciones) 
                                $maximoPersonas = $habitaciones[$i]["maxPax"];
                                $maximoAdultos  = $habitaciones[$i]["maxAdults"];
                                $maximoMenores  = $habitaciones[$i]["maxChildren"];
                                
                                // Empieza el ciclo para mostrar diferentes precios, politicas de cancelacion, etc. en base al tipo de servicio de la habitacion
                                $contentPopOver                 = [];
                                $contentPopOverConsideraciones  = [];
                                $tipoServicio                   = [];
                                $precioHabitacionOriginal       = [];
                                $precioHabitacionDescuento      = [];
                                $precioPorPersona               = [];
                                $txtNumeroHabitaciones          = [];

                                $nombreHabitacionEncode         = base64_encode($nombreHabitacion);

                                
                                if (isset($hotelSeleccionado["rooms"][$i]["rates"])) {
                                    $templateRatesBoards = "";
                                    
                                    for ($j=0, $m=count($hotelSeleccionado["rooms"][$i]["rates"]); $j < $m; $j++) {
                                        if (isset($hotelSeleccionado["rooms"][$i]["rates"][$j]["promotions"])) {
                                            $txtPromociones[$j] = "<div class='alert alert-warning d-flex align-items-center justify-content-center' role='alert' style='padding: 0; margin: 0 -1rem -1rem -1rem;'>
                                                                        <div>
                                                                            <p class='text-center'>{$hotelSeleccionado['rooms'][$i]['rates'][$j]['promotions'][0]['name']}</p>
                                                                        </div>
                                                                    </div>";
                                        } else {
                                            $txtPromociones[$j] = "";
                                        }

                                        if ($j > 0) {
                                            if ($hotelSeleccionado["rooms"][$i]["rates"][$j]["rateKey"] == $hotelSeleccionado["rooms"][$i]["rates"][$j - 1]["rateKey"]) {
                                                continue;
                                            }
                                        }
                                        // Pricing Model: In this model, the prices provided are net.
                                        $precioHabitacion   = ceil($hotelSeleccionado["rooms"][$i]["rates"][$j]["net"] * $valorDivisaHotel);

                                        // If the hotelMandatory value is true, you must respect the value on of the sellingRate attribute.
                                        // Otherwise you can add your own markup.
                                        if (isset($hotelSeleccionado["rooms"][$i]["rates"][$j]["sellingRate"])) {
                                            if (isset($hotelSeleccionado["rooms"][$i]["rates"][$j]["sellingRate"])) {
                                                if ($hotelSeleccionado["rooms"][$i]["rates"][$j]["hotelMandatory"] == true) {
                                                    $precioHabitacion   = ceil($hotelSeleccionado["rooms"][$i]["rates"][$j]["sellingRate"] * $valorDivisaHotel);
                                                }
                                            }
                                        }
                                
                                        // TODO: Comprobar si vienen diferentes tipos de divisas en el hotel y convertir todo a sus respectivos valores
                                        $precioHabitacionOriginal[$j]   = "MXN$" . number_format($precioHabitacion * 1.075, 0);
                                        $precioHabitacionDescuento[$j]  = "MXN$" . number_format($precioHabitacion, 0);
                                        $precioPorPersona[$j]           = "MXN$" . number_format(($precioHabitacion / $totalPersonas), 0);

                                        // TODO: Trabajar con un ejemplo de precios que tengan el campo 'Commissionable'

                                        $habitacionesDisponibles[$j]   = isset($hotelSeleccionado["rooms"][$i]["rates"][$j]["allotment"]) ? $hotelSeleccionado["rooms"][$i]["rates"][$j]["allotment"] :  0;
                                        $rateKeyHabitacionEncode[$j]   = isset($hotelSeleccionado["rooms"][$i]["rates"][$j]["rateKey"])   ? base64_encode($hotelSeleccionado["rooms"][$i]["rates"][$j]["rateKey"])   : "";

                                        // Numero de habitaciones disponibles por cada tipo de habitación (Single, doble, triple, etc.)
                                        $txtNumeroHabitaciones[$j] = "";

                                        for ($k=1, $n=$habitacionesDisponibles[$j] + 1; $k < $n; $k++) { 
                                            if ($k > 10 ) {
                                                break;
                                            }

                                            $precioPorNoHabitacion = number_format( $k * $precioHabitacion, 0);
                                            
                                            $txtNumeroHabitaciones[$j] .= "<li><p><a class='dropdown-item' style='display: flex' href='#'>$k <span style='margin-left: auto'>($" . $precioPorNoHabitacion . ")</span></a></p></li>";
                                        }

                                        // Politicas de cancelación
                                        $contentPopOver[$j] =  "";
                                        $txtCancelaciones   = [];

                                        for ($k=0, $n=count($hotelSeleccionado["rooms"][$i]["rates"][$j]["cancellationPolicies"]); $k < $n; $k++) { 
                                            $precioCancelacion = number_format($hotelSeleccionado["rooms"][$i]["rates"][$j]["cancellationPolicies"][$k]["amount"] * $valorDivisaHotel, 2);
                                            $txtFecha = $hotelSeleccionado["rooms"][$i]["rates"][$j]["cancellationPolicies"][$k]["from"];
                                            $txtFecha = explode("T", $txtFecha)[0];
                                            $fecha = DateTime::createFromFormat('Y-m-d', $txtFecha);
                                            $fecha = $fecha->format('Y-m-d');

                                            $contentPopOver[$j] .= "Desde el $fecha: $precioCancelacion MXN<br>";
                                            $txtCancelaciones[] = (object) ["desde" => $fecha, "cantidad" => $precioCancelacion];
                                        }

                                        $txtCancelacionesEncode[$j] = base64_encode( json_encode( $txtCancelaciones ) );

                                        // Tipo de servicio
                                        if(isset($hotelSeleccionado["rooms"][$i]["rates"][$j]["boardName"])) {
                                            $tipoServicio[$j] = $hotelSeleccionado["rooms"][$i]["rates"][$j]["boardName"];
                                            $tipoServicioEncode[$j] = base64_encode($tipoServicio[$j]);
                                        }

                                        $txtDetallesPrecio[$j] = "<a tabindex='0' class='btn btn-sm btn-link' role='button' data-bs-toggle='popover' data-bs-trigger='focus' title='Precios de cancelación' data-bs-content='{$contentPopOver[$j]}' data-bs-html='true'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-info-circle' viewBox='0 0 16 16'>
                                                        <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'/>
                                                        <path d='m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z'/>
                                                    </svg> Cancelaciónes</a>";

                                        // RateComments
                                        $contentPopOverConsideraciones[$j] =  "";

                                        if (isset($hotelSeleccionado["rooms"][$i]["rates"][$j]["rateComments"])) {
                                            $contentPopOverConsideraciones[$j] .= $hotelSeleccionado["rooms"][$i]["rates"][$j]["rateComments"];
                                        }

                                        $txtDetallesConsideraciones[$j] = "<a tabindex='0' class='btn btn-sm btn-link' role='button' data-bs-toggle='popover' data-bs-trigger='focus' title='Comentarios Hotel' data-bs-content='{$contentPopOverConsideraciones[$j]}' data-bs-html='true'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-info-circle' viewBox='0 0 16 16'>
                                                        <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'/>
                                                        <path d='m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z'/>
                                                    </svg> Consideraciones</a>";


                                        if ($j > 0) {
                                            $templateRatesBoards .= "<div class='col-md-9 m-0 p-0'></div>
                                                                    <div class='col-md-3 m-0 p-0'>
                                                                        <div class='p-4 d-flex align-items-center  justify-content-center' style='height: 293px;'>
                                                                            <div class='p-2'>
                                                                                <div class='alert alert-success p-0' role='alert' style='text-align: center; font-size: 70%;'>{$tipoServicio[$j]}$txtPromociones[$j]</div>
                                                                                
                                                                                <p class='text-center precio-cancelado'>{$precioHabitacionOriginal[$j]}</p>
                                                                                <h2 class='text-center fw-bold' style='color:#e3223e !important;'>{$precioHabitacionDescuento[$j]}</h2>
                                                                                <p class='text-center'>Por persona</p>
                                                                                <p class='text-center fw-bold'>{$precioPorPersona[$j]}</p>
                                                                                <p class='text-center'>Impuestos incluidos</p>
                                                                                <div class='dropdown d-flex justify-content-center mb-3 mt-3'>
                                                                                    <button class='btn btn-danger dropdown-toggle' type='button' id='dropdownMenuButton2'
                                                                                        data-bs-toggle='dropdown' aria-expanded='false'>
                                                                                        Seleccionar
                                                                                    </button>
                                                                                    <ul class='dropdown-menu dropdown_numeroHabitaciones' cancelaciones='{$txtCancelacionesEncode[$j]}' namehabitacion='{$nombreHabitacionEncode}' bookingratekey='{$rateKeyHabitacionEncode[$j]}' tiposervicio='{$tipoServicioEncode[$j]}' tarjetashotel='$tarjetasAceptadasEncode' aria-labelledby='dropdownMenuButton2' style='position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(1001px, 2816px, 0px);' data-popper-placement='bottom-start'>
                                                                                        <li><p>Numero de Habitaciones</p></li>
                                                                                        <li><hr class='dropdown-divider'></li>
                                                                                        $txtNumeroHabitaciones[$j]
                                                                                    </ul>
                                                                                </div>
                                                                                <div class='d-flex align-items-center  justify-content-center p-2'>
                                                                                $txtDetallesPrecio[$j]
                                                                                $txtDetallesConsideraciones[$j]
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>";
                                        }
                                    }
                                }
                                
                                $template_extra = " <div class='col-md-4 m-0 p-0'>
                                                        <div class='text-centerp-3 p-3 pe-0'><h4 class='text-center' style='color:#228ce3 !important;'>Características</h4></div>      
                                                        
                                                        <div class='p-3 pe-0' style='background:#f8f8f8; height: 293px;'>
                                                            <div class='bg-red'>
                                                                <p class='ms-auto text-white w-75 p-1' style='background: #228ce3;'>All inclusive</p>
                                                            </div>
                                                            $txtEquipamiento
                                                            
                                                        </div>    
                                                    </div>
                                                    
                                                    <div class='col-md-2 m-0 p-0 maxPersonasHabitacionContenedor'>
                                                        <div class='text-centerp-3 p-3 '><h4 class='text-center' style='color:#228ce3 !important;'>Capacidad</h4></div>       
                                                        <div class='d-flex align-items-center  justify-content-center' style='height: 293px;'>
                                                            <p class='text-center'>$maximoPersonas Personas<br>Maximo adultos: $maximoAdultos<br>Maximo niños: $maximoMenores</p>
                                                        </div>                  
                                                    </div>                    
                                                    <div class='col-md-3 m-0 p-0 areaHabitacionContenedor'> 
                                                        <div class='text-centerp-3 p-3 '><h4 class='text-center' style='color:#228ce3 !important;'>Dimensiones</h4></div> 
                                                        <div class='d-flex align-items-center  justify-content-center' style='background:#f8f8f8;  height: 293px;'>
                                                            <h3 class='text-center'>$areaHabitacion</h3>
                                                        </div>
                                                    </div>                    
                                                    <div class='col-md-3 m-0 p-0'>
                                                        <div class='text-centerp-3 p-3 '><h4 class='text-center' style='color:#228ce3 !important;'>Precio</h4></div>       
                                                        <div class='p-4 d-flex align-items-center  justify-content-center' style='height: 293px;'>
                                                            <!--ul class='list-info-hotel m-0' style='list-style-image: url(medios/img/isotipobiñetalextravel1.png) !important;'>
                                                                <li class='listdetalles'>Pagas por adelantado</li>
                                                                <li class='listdetalles'>Parcialmente reembolsable</li>
                                                                <li class='listdetalles'>20% aplicado</li>
                                                            </ul-->
                                                            <div class='p-2'>
                                                                <div class='alert alert-success p-0' role='alert' style='text-align: center; font-size: 70%';>{$tipoServicio[0]}{$txtPromociones[0]}</div>
                                                                <p class='text-center precio-cancelado'>{$precioHabitacionOriginal[0]}</p>
                                                                <h2 class='text-center fw-bold' style='color:#e3223e !important;'>{$precioHabitacionDescuento[0]}</h2>
                                                                <p class='text-center'>Por persona</p>
                                                                <p class='text-center fw-bold'>{$precioPorPersona[0]}</p>
                                                                <p class='text-center'>Impuestos incluidos</p>
                                                                <div class='dropdown d-flex justify-content-center mb-3 mt-3'>
                                                                    <button class='btn btn-danger dropdown-toggle' type='button' id='dropdownMenuButton2' data-bs-toggle='dropdown' aria-expanded='false'>
                                                                        Seleccionar
                                                                    </button>
                                                                    <ul class='dropdown-menu dropdown_numeroHabitaciones' cancelaciones={$txtCancelacionesEncode[0]} namehabitacion=$nombreHabitacionEncode bookingratekey={$rateKeyHabitacionEncode[0]} tiposervicio='{$tipoServicioEncode[0]}' tarjetashotel='$tarjetasAceptadasEncode' aria-labelledby='dropdownMenuButton2' data-popper-placement='bottom-start' style='position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);'>
                                                                        <li><p>Numero de Habitaciones</p></li>
                                                                        <li><hr class='dropdown-divider'></li>
                                                                        {$txtNumeroHabitaciones[0]}
                                                                    </ul>
                                                                    </div>
                                                                <div class='d-flex align-items-center  justify-content-center p-2'>
                                                                $txtDetallesPrecio[0]
                                                                $txtDetallesConsideraciones[0]
                                                                </div>
                                                            </div> 
                                                        </div>
                                                                        
                                                    </div>
                                                    
                                                    $templateRatesBoards";

                                $template .= $template_extra;
                            }
                            
                            $template .= "</div><br>";

                            echo $template;
                        }
                    ?>
                </div>

                <div class="col-md-4 p-0 m-0" >
                    <div  class="w-100 " style="background:#f8f8f8; position: -webkit-sticky; position: sticky; top: 158px;" >
                        <div class="p-3 bg-white w-100" style="box-shadow: 4px 3px 3px #00000070;">
                            <h3 class="tit1 text-center">Detalle de tu reservación</h3>
                        </div>
                        <div class="bg-red" >
                            <p id="drs-descuento" class="text-center ms-auto text-white w-50 p-1" style="background: #e3223e; margin-top: -10px;">-MXN$0 - descuento aplicado</p>
                        </div>
                        <div class="p-5">
                            <p id="drs-precio-persona" class="fw-bold" style="color: #228ce3;">MXN$0</p>
                            <p style="color: #228ce3;">Precio por persona</p>
                            <h2 id="drs-precio-total" class="tit1 mt-3">MXN$0</h2>
                            <p style="color: #228ce3;">Precio final total</p>
                            <p class="fw-bold" style="color: #228ce3;">Impuestos incluidos</p>
                            <h5 class="tit1 mt-3">Paga con tarjeta y en meses</h5>
                            <!-- Visa, Mastercard, American Express -->
                            <?php
                            $txtFormasPago = "";
                            
                            if (isset($facilidades["pago"])) {
                                if (array_search("Visa", $facilidades["pago"])) {
                                    $txtFormasPago .= '<div class="tarjetaAceptada"><img src="medios/img/formas-pago/american-express.png" alt="American Express" title="American Express"></div>';
                                }

                                if (array_search("MasterCard", $facilidades["pago"])) {
                                    $txtFormasPago .= '<div class="tarjetaAceptada"><img src="medios/img/formas-pago/mastercard.png" alt="MasterCard" title="MasterCard"></div>';
                                }

                                if (array_search("American Express", $facilidades["pago"])) {
                                    $txtFormasPago .= '<div class="tarjetaAceptada"><img src="medios/img/formas-pago/visa.png" alt="Visa" title="Visa"></div>';
                                }
                            }                            
                            ?>

                            <style>
                                .cont-formas-pago {
                                    display: flex;
                                    flex-direction: row;
                                    gap: 0.5rem;
                                    flex-wrap: wrap;
                                }

                                .cont-formas-pago > * {
                                    width: 2.25rem;
                                }

                                .cont-formas-pago > * > img {
                                    width: 100%;
                                    object-fit: cover;
                                }
                            </style>

                            <div class="cont-formas-pago mt-3">
                                <?php echo $txtFormasPago; ?>
                            </div>
                            <p class="fw-bold" style="color: #228ce3;">Tarjetas aceptadas &crarr;</p>
                            <p class="fw-bold mt-3" style="color: #228ce3;">Información de tu reserva</p>
                            <div id="drs-info-reserva" class="row">
                                <div id="drs-lista-personas">
                                    
                                </div>

                                <div id="drs-lista-habitaciones">

                                </div>
                            </div>
                        </div>
                        
                        <div class="px-5">
                            
                            <a href="./../datosdecompra" class="nav-link link-light bg-black px-1 mx-5 m-2 text-center" style="background: #e3223e !important;">Siguiente</a>
                            <p class="fw-bold text-center p-2" style="color: #228ce3;">Impuestos incluidos</p>
                            
                        </div>
                    </div>
                </div>
                
            </div>

        </div>


        <div class="container">
            <div id="hotel-info-pub" class="row w-100 p-5 ps-0 m-0" style="padding-right: 0px !important;">
                    
                <div id="hotel-info-pub" class="row w-100 py-5 ps-0 m-0" style="padding-right: 0px !important;">
                    <div class="d-flex align-items-center justify-content-end m-0 p-0" style="box-shadow: 2px 4px 5px 1px #0000003d; padding-top: 98px !important; background-size:cover; background-image:url('medios/img/mdb/imagenllamanos.jpg'); border-radius: 20px 0px 0px 20px;">
                        <div class="w-50 p-5 mt-5" style="background: #1d6cad; border-radius: 20px 0px 0px 20px;padding-right: 0px !important; padding-bottom: 0px !important;">
                            <h3 class="text-white text-center mb-4">Solicita más información <br>con nuestros especialistas</h3>
                            <div class="row w-100 m-0 p-0">
                                <div class="col-4"></div>
                                <div class="col-8 p-3 bg-white" style="border-radius: 10px 0px 0px 10px;">
                                    <h4 class="text-black">Llámanos al <b>999 399 0612</b></h4>
                                </div>

                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!--FIN INFO HOTELES-->

        <!--FIN BOODY-->
        
        <!--FOOTER-->
        <?php $detallesHotelFinalizado = true; ?>
        <?php include ("modal.php") ?>
        <?php include("footer.php") ?>
    <!--FIN FOOTER-->
    
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
    <script type="text/javascript" src="js/venobox/venobox.min.js"></script>

    <script src="js/places/places.min.js"></script>
    <script src="js/filtro.js"></script>
    <script src="js/detalleshotel.js"></script>
</body>
</html>