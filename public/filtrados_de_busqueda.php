<?php
    // error_reporting(E_ALL);
    include("./../vendor/autoload.php");

    use Controlador\controladorHotel;

    use Classes\temporaryClass;
    use Classes\funciones;

    $fn                     = new funciones();
    $temp                   = new temporaryClass();
    
    session_start();

    if(isset($_SESSION["geolocation"])) {
        $GeolocalitationSession = $_SESSION['geolocation'];
    }

    if(isset($_SESSION["Disponibilidad"])) {
        $jsonRespuesta = $_SESSION['Disponibilidad'];
    }

    if(isset($_SESSION["occupancies"])) {
        $ocupantes = $_SESSION['occupancies'];
    }

    if (isset($_SESSION["stay"])) {
        $estancia = $_SESSION["stay"];
    }

    session_write_close();

    $totalHabitaciones  = 0;
    $totalPersonas      = 0;
    $totalAdultos       = 0;
    $totalMenores       = 0;

    $checkIn    = new DateTime($estancia["checkIn"]);
    $checkOut   = new DateTime($estancia["checkOut"]);

    $nochesEstancia = $checkIn->diff($checkOut);

    //Calcula el total de personas (Por medio del filtro)
    if (isset($ocupantes)) {
        for ($i=0, $l=count($ocupantes); $i < $l; $i++) { 
            if (isset($ocupantes[$i]["rooms"])) {
                $totalHabitaciones += $ocupantes[$i]["rooms"];
            }

            if (isset($ocupantes[$i]["adults"])) {
                $totalAdultos += $ocupantes[$i]["adults"];
            }
        
            if (isset($ocupantes[$i]["children"])) {
                $totalMenores += $ocupantes[$i]["children"];
            }
        }
        
        $totalPersonas = $totalAdultos + $totalMenores;
    }

    $totalPersonas = ($totalPersonas > 0) ? $totalPersonas : 1;
    
    $str                                = rand();
    $Codigo                             = md5($str);

    $temp->CreateTemporaryTable($jsonRespuesta,$Codigo);

    $categoria      = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_NUMBER_INT) ?? '';
    $tipCama        = filter_input(INPUT_POST, 'tipCama',   FILTER_SANITIZE_STRING)     ?? 'Preferencia de camas';
    $tipPlanes      = filter_input(INPUT_POST, 'tipPlanes', FILTER_SANITIZE_STRING)     ?? 'Tipos de planes';
    $preMin         = filter_input(INPUT_POST, 'precioMin', FILTER_SANITIZE_NUMBER_INT) ?? '';

    // La suma de 500 es para evitar que el precio maximo nunca salga en los filtros a causa de perdida de decimales en el calculo o cambio en el valor de la moneda
    $preMax         = filter_input(INPUT_POST, 'precioMax', FILTER_SANITIZE_NUMBER_INT) + 400  ?? '';

    ///////////////////////////////////
    // Convertidor de divisas
    require_once('utils/currencyConverter.php');
    
    function masRepetido($n)
    {
        $valores = array_count_values($n);
        arsort($valores);
        return count($valores) > 0 ? array_key_first($valores)  : "";
    }

    foreach ($jsonRespuesta["hotels"]["hotels"] as $hotelClave => $hotelValor) {
        $currency[] = $hotelValor["currency"];
    }

    // Arreglo de divisas que manejan los hoteles
    $currency = array_unique($currency, SORT_STRING );
    $divisaMasRepetida = masRepetido($currency);

    $FromCurrency   = 'MXN';
    $ToCurrency     = $divisaMasRepetida;

    $amount         = 1;

    $converter      = new currencyConverter($amount, $ToCurrency, $FromCurrency);
    $valorDivisaMasRepetida = $converter->getUpdate();

    ///////////////////////////////////
    // Fin convertidor de divisas
                        
    $preMinConvert  = $preMin * $valorDivisaMasRepetida;
    
    $converter      = new currencyConverter($preMax, $ToCurrency, $FromCurrency);
    $preMaxConvert  = str_replace(",", "", $converter->getUpdate());

    // echo "<p>$preMax</p>";
    // echo "<p>$preMaxConvert</p>";
    // echo "<p>$valorDivisaMasRepetida</p>";

    $HotelPorPagina = 25;
    $pagina         = 1;

    if(isset($_POST['pagina'])){
        $pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
    }

    $anterior   = $pagina - 1;
    $siguiente  = $pagina + 1;

    $limit      = $HotelPorPagina;
    $offset     = ($pagina - 1) * $limit;

    //Contador de hoteles filtrados
    $ContadorFiltros    = $temp->CountFiltros($categoria,$tipCama,$tipPlanes,$Codigo,$preMinConvert,$preMaxConvert);
    $cContadorFiltros   = $fn->cuenta($ContadorFiltros);

    //Informacion de hoteles por filtrar
    $respuestaFiltros   = $temp->Filtros($categoria,$tipCama,$tipPlanes,$Codigo,$limit,$offset,$preMinConvert,$preMaxConvert);
    $crespuestaFiltros  = $fn->cuenta($respuestaFiltros);

    $paginas = ceil($cContadorFiltros / $HotelPorPagina);

    if($respuestaFiltros != NULL){
?>
    <div>
        <nav>
            <div class="row w-100">
                <div class="col-xs-12 col-sm-6">
                    <h4 class="pag-num-hot fw-bold">Mostrando <?php echo ($cContadorFiltros < $HotelPorPagina) ? $cContadorFiltros : $HotelPorPagina; ?> de <?php echo $cContadorFiltros; ?> Hoteles disponibles</h4>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <p class="pag-num-pag">Página <?php echo $pagina ?> de <?php echo $paginas ?> </p>
                </div>
            </div>
            <ul class="pagination">
                <!-- Si la página actual es mayor a uno, mostramos el botón para ir una página atrás -->
                <?php if ($pagina > 1) { ?>
                    <li class="paginacion">
                        <a href="javascript:;" onclick="PaginacionAjax('<?php echo $res=1; ?>','<?php echo $categoria; ?>','<?php echo $tipCama; ?>','<?php echo $tipPlanes; ?>','<?php echo $preMin; ?>','<?php echo $preMax; ?>','<?php echo $anterior; ?>','<?php echo $Codigo; ?>')">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php } ?>

                <!-- Mostramos enlaces para ir a todas las páginas. Es un simple ciclo for-->
                <?php for ($x = 1; $x <= $paginas; $x++) { ?>
                    <li class="<?php if ($x == $pagina) echo "active" ?> paginacion">
                        <a class="pag-act fw-bold" href="javascript:;" onclick="PaginacionAjax('<?php echo $res=1; ?>','<?php echo $categoria; ?>','<?php echo $tipCama; ?>','<?php echo $tipPlanes; ?>','<?php echo $preMin; ?>','<?php echo $preMax; ?>','<?php echo $x; ?>','<?php echo $Codigo; ?>')">
                            <?php echo $x ?></a>
                    </li>
                <?php } ?>
                <!-- Si la página actual es menor al total de páginas, mostramos un botón para ir una página adelante -->
                <?php if ($pagina < $paginas) { ?>
                    <li>
                        <a href="javascript:;" onclick="PaginacionAjax('<?php echo $res=1; ?>','<?php echo $categoria; ?>','<?php echo $tipCama; ?>','<?php echo $tipPlanes; ?>','<?php echo $preMin; ?>','<?php echo $preMax; ?>','<?php echo $siguiente; ?>','<?php echo $Codigo; ?>')">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>

<?php
    // Se obtiene la descripcion y la imagen del hotel desde la BDD local
    $txt = $respuestaFiltros['code'][0];
                    
    for($i=1; $i<$crespuestaFiltros; $i++){
        $txt .= "," . $respuestaFiltros['code'][$i];
    }

    $datos = (new controladorHotel())->leerHoteles($txt);
    $datos = json_decode($datos, true);

    $txt = "";
    for($i=0; $i < $crespuestaFiltros; $i++){
        $indiceConsulta = array_search($respuestaFiltros['code'][$i], $datos["code"]);

        if (is_int($indiceConsulta)) {
            if ($i > 0) {
                $txt .= "," . $datos['id'][$indiceConsulta];
            } else {
                $txt .= $datos['id'][$indiceConsulta];
            }
        }
    }

    $facilidades = (new controladorHotel())->hotelFacilidades($txt, "facilityCode,facilityGroupCode,id_hotel_description");
    $facilidades = json_decode($facilidades, true);

    function filtrado($a, $code) {
        $arrFiltrado = [];
        
        for ($i = 0, $l=count($a["id_hotel_description"]); $i < $l; $i++) {
            if ($a["id_hotel_description"][$i] == $code) {
                $arrFiltrado["facilityCode"][]              = $a["facilityCode"][$i];
                $arrFiltrado["facilityGroupCode"][]         = $a["facilityGroupCode"][$i];
                $arrFiltrado["id_hotel_description"][]      = $a["id_hotel_description"][$i];
            }
        }
        
        return $arrFiltrado;
    };

    for ($i=0; $i < $crespuestaFiltros; $i++) {
        $indiceConsulta = array_search($respuestaFiltros['code'][$i], $datos["code"]);
        $facilidadesActual = filtrado($facilidades, $datos['id'][$indiceConsulta]);

        $indiceJsonRes = array_search($respuestaFiltros['code'][$i], array_column($jsonRespuesta["hotels"]["hotels"], "code"));

        $txtFacilidades = "";

        if (isset($facilidadesActual["facilityCode"])) {
            if (array_search('550', $facilidadesActual["facilityCode"]) && array_search('70', $facilidadesActual["facilityGroupCode"])) {
                $txtFacilidades .= '<span class="d-inline-block mx-1" tabindex="0" data-bs-toggle="tooltip" title="Ofrece WiFi"><img class="star-hotel mt-1" style="height: 16px;" src="medios/img/mdb/iconowifilista.png" alt=""></a></span>';
            }

            if ( (array_search('470', $facilidadesActual["facilityCode"]) && array_search('70', $facilidadesActual["facilityGroupCode"])) || (array_search('308', $facilidadesActual["facilityCode"]) && array_search('60', $facilidadesActual["facilityGroupCode"]))) {
                $txtFacilidades .= '<span class="d-inline-block mx-1" tabindex="0" data-bs-toggle="tooltip" title="Cuenta con Gimnasio"><img class="star-hotel mt-1" style="height: 16px;" src="medios/img/mdb/iconogymlista.png" alt=""></a></span>';
            }

            if (array_search('360', $facilidadesActual["facilityCode"]) && array_search('73', $facilidadesActual["facilityGroupCode"])) {
                $txtFacilidades .= '<span class="d-inline-block mx-1" tabindex="0" data-bs-toggle="tooltip" title="Alberca"><img class="star-hotel mt-1" style="height: 16px;" src="medios/img/mdb/iconoalbercalista.png" alt=""></a></span>';
            }

            if ( (array_search('201', $facilidadesActual["facilityCode"]) && array_search('288', $facilidadesActual["facilityGroupCode"])) || (array_search('202', $facilidadesActual["facilityCode"]) && array_search('288', $facilidadesActual["facilityGroupCode"])) || (array_search('55', $facilidadesActual["facilityCode"]) && array_search('60', $facilidadesActual["facilityGroupCode"])) ) {
                $txtFacilidades .= '<span class="d-inline-block mx-1" tabindex="0" data-bs-toggle="tooltip" title="Televisión"><img class="star-hotel mt-1" style="height: 16px;" src="medios/img/mdb/iconotvlista.png" alt=""></a></span>';
            }

            if ( (array_search('200', $facilidadesActual["facilityCode"]) && array_search('71', $facilidadesActual["facilityGroupCode"])) || (array_search('560', $facilidadesActual["facilityCode"]) && array_search('71', $facilidadesActual["facilityGroupCode"]))) {
                $txtFacilidades .= '<span class="d-inline-block mx-1" tabindex="0" data-bs-toggle="tooltip" title="Restaurante"><img class="star-hotel mt-1" style="height: 16px;" src="medios/img/mdb/iconocomidalista.png" alt=""></a></span>';
            }

            if ( (array_search('320', $facilidadesActual["facilityCode"]) && array_search('70', $facilidadesActual["facilityGroupCode"])) || (array_search('330', $facilidadesActual["facilityCode"]) && array_search('70', $facilidadesActual["facilityGroupCode"]))) {
                $txtFacilidades .= '<span class="d-inline-block mx-1" tabindex="0" data-bs-toggle="tooltip" title="Servicio de estacionamiento"><img class="star-hotel mt-1" style="height: 16px;" src="medios/img/mdb/iconoestacionamientolista.png" alt=""></a></span>';
            }
        }
    
        $descripcion = is_int($indiceConsulta) ? $datos["description"][$indiceConsulta] : "";

        if(strlen($descripcion) > 768){
            // Entonces corta la cadena y ponle el sufijo
            $descripcion = substr($descripcion, 0, 768) . "...";
        }

        if (isset($datos["path"][$indiceConsulta])) {
            $img = is_int($indiceConsulta) ? "https://photos.hotelbeds.com/giata/bigger/" . $datos["path"][$indiceConsulta] : "medios/img/not-found.png";
        } else {
            $img = "medios/img/not-found.png";
        }
        
        // Estrellas del hotel
        $estrellas = $respuestaFiltros["categoryCode"][$i];

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
    
        $hotelEstrellas     = $estrellas;
        $htmlEstrellas      = "";
        $noHabitaciones     = count($jsonRespuesta["hotels"]["hotels"][$indiceJsonRes]["rooms"]);
        $noHabPrecioBajo    = $jsonRespuesta["hotels"]["hotels"][$indiceJsonRes]["rooms"][0]["rates"][0]["allotment"];

        $txtAlertNoHab = "";

        if ($noHabPrecioBajo < 5) {
            $txtAlertNoHab = "<p class='text-center mx-5 my-2 mb-0 p-1' style='border: 2px solid red;'>Solo quedan $noHabPrecioBajo habitaciones a este precio</p>";
        }

        $popoverTiposHabitacion = "";
        for ($j=0; $j < $noHabitaciones; $j++) { 
            $popoverTiposHabitacion .= ucfirst(mb_strtolower($jsonRespuesta["hotels"]["hotels"][$indiceJsonRes]["rooms"][$j]["name"] . "<br>"));
        }
        $txtDestino = $respuestaFiltros["destinationName"][$i];
                            
        // Distancia del destino al hotel
        //Get latitude and longitude from geo data                    
        $latitudeFrom       = $GeolocalitationSession["latitude"];
        $longitudeFrom      = $GeolocalitationSession["longitude"];
        $latitudeTo         = $respuestaFiltros['latitude'][$i];
        $longitudeTo        = $respuestaFiltros["longitude"][$i];
    
        //Calculate distance from latitude and longitude
        $theta  = $longitudeFrom - $longitudeTo;
        $dist   = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist   = acos($dist);
        $dist   = rad2deg($dist);
        $miles  = $dist * 60 * 1.1515;
    
        $txtDistancia = number_format($miles * 1.609344, 1) . ' km';

        // Politicas de cancelación
        if (isset($jsonRespuesta['hotels']['hotels'][$indiceJsonRes]['rooms'])) {
            $infoLimiteCancelacionGratis = '';

            if (isset($jsonRespuesta['hotels']['hotels'][$indiceJsonRes]['rooms'][0]['rates'][0]['cancellationPolicies'])) {
                $infoLimiteCancelacionGratis = $jsonRespuesta['hotels']['hotels'][$indiceJsonRes]['rooms'][0]['rates'][0]['cancellationPolicies'][0];

                $txtFecha = $infoLimiteCancelacionGratis['from'];
                $txtFecha = explode("T", $txtFecha)[0];
                $formato = 'Y-m-d';
                $fecha = DateTime::createFromFormat($formato, $txtFecha);
                $fecha = $fecha->format('Y-m-d');

                $precioCancelacion = number_format($infoLimiteCancelacionGratis['amount'] / $valorDivisaMasRepetida, 2);
                $contentPopOver = "Puedes cancelar totalmente gratis hasta el $fecha, fecha a partir de la cual el precio de cancelacion es $precioCancelacion MXN. Mas informacion de los precios de cancelación en los detalles del hotel...";
            }
        }

        $txtPoliticasCancelacion = "<a class='d-inline-block text-decoration-underline m-0 text-center' tabindex='0' style='font-size: 11px !important;' title='' data-bs-placement='right' data-bs-toggle='popover' data-bs-trigger='hover focus' data-bs-content='$contentPopOver' data-bs-original-title='Información cancelación'>
                                        Políticas de cancelación
                                        <svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='currentColor' class='bi bi-info-circle' style='margin: 0 0 0.2rem 0.25rem' viewBox='0 0 16 16'>
                                            <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'></path>
                                            <path d='m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z'></path>
                                        </svg>
                                    </a>";

        $txtAdultosMenoresHabitaciones = ($totalAdultos === 1) ? "$totalAdultos Adulto" : "$totalAdultos Adultos";

        if ($totalMenores > 0) {
            $txtAdultosMenoresHabitaciones .= ($totalMenores === 1) ? " + $totalMenores Menor" : " + $totalMenores Menores";
        }

        $txtAdultosMenoresHabitaciones .= ($totalHabitaciones === 1) ? " / $totalHabitaciones Habitación" : " / $totalHabitaciones Habitaciónes";
        
            
        $template = '
        <div id="hotel-info" class="row w-100 p-5 m-0" style="padding-right: 0px !important;">
            <div class="col-md-8 p-0 h-100"  >
                <div class="w-100 d-flex justify-content-end"><img class="" src="medios/img/mdb/allinclusivehoteleslista.png" alt=""></div> 
                <div class="w-100 p-4" style="background: white !important; border-radius: 20px 0px 0px 20px;">
                    <h3 class="tit2">$nombreHotel</h3>
                    <p class="mb-3">$destinationName | <a data-gall="iframe" class="venoboxframe" data-vbtype="iframe" href="$ubicacionMapa" style="font-weight: 700;">Mostrar en el mapa</a> |a $distancia de $destino</p>
                    <div class="nav align-middle">
                        $estrellas
                    </div>
                    <div class="row w-100">
                        <div class="col-md-6">
                            <div class="row w-100 mb-2">
                                <div class="col-md-6">
                                    
                                    <p class="m-0">Servicios incluidos</p>
                                    <div class="nav align-middle">
                                        $txtFacilidades
                                    </div>
                                </div>
                                <div class="col-md-6 p-0">
                                    <a class="btn btn-sm btn-link" role="button" data-bs-toggle="popover" tabindex="0" title="" data-bs-placement="auto" data-bs-trigger="hover focus" data-bs-html="true" data-bs-content="$popoverTiposHabitacion" data-bs-original-title="Tipos de habitación"><p class="text-white text-center m-0 p-1" style="background: #228ce3;">$noHabitaciones Tipos de habitacion disp.</p></a>
                                </div>

                            </div>
                            <p class="text-center">$txtAdultosMenoresHabitaciones</p>
                            <p class="text-center m-0">$nochesEstancia</p>
                            <h3 class="text-center m-0 fw-bold" style="color:#e3223e;">$$precio</h3>
                            <p class="text-center m-0">Impuestos y cargos incluidos</p>
                            $txtAlertNoHab
                            <p class="text-center mx-5 my-2 mb-0 p-1" style="border: 2px solid red; margin: 6px 33% !important;">100% Reembolsable</p>
                            <div class="row w-100 m-0 mb-2">
                                
                                <div class="m-0 p-0 d-flex justify-content-center">
                                    $txtPoliticasCancelacion
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-3">$description</p>
                            <div class="row w-100 m-0 p-0 mb-3">
                                
                                <div class="m-0 p-0 d-flex justify-content-center">
                                <a class="btn rounded-0 text-white p-1 btnDetallesHotel" href="./../detalleshotel?code=$codigoHotel" style="background: #e3223e; font-size: 14px; width: 160px;">Ver Habitaciones</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="destino-img" class="col-md-4" style="background-size:cover; background-image:url($img);">
                
            </div>
        </div>
        ';
    
        for ($a=0; $a < $hotelEstrellas; $a++) { 
            $htmlEstrellas .= '<img class="star-hotel mt-1" style="height: 17px;" src="medios/img/Estrellarecomendacionesgrande.png" alt="">';
        }
    
        $precioMXN = $respuestaFiltros["minRate"][$i] / $valorDivisaMasRepetida;
        $precioMXN = number_format($precioMXN, 2);
    
            $vars = array(
                '$nombreHotel'                      => $respuestaFiltros["nombreHotel"][$i],
                '$destinationName'                  => $respuestaFiltros["destinationName"][$i],
                '$description'                      => $descripcion,
                '$ubicacionMapa'                    => "https://www.google.com/maps/embed/v1/search?key=AIzaSyCS7kK67VZxjgmzINsI1_C4zamwkNaUaD4&q= " . str_replace("&", "and", $respuestaFiltros['nombreHotel'][$i]) . "+{$respuestaFiltros['destinationName'][$i]}&center={$respuestaFiltros['latitude'][$i]},{$respuestaFiltros['longitude'][$i]}&zoom=18",
                '$distancia'                        => $txtDistancia,
                '$destino'                          => $txtDestino,
                '$estrellas'                        => $htmlEstrellas,
                '$noHabitaciones'                   => $noHabitaciones,
                '$precio'                           => $precioMXN,
                '$img'                              => $img,
                '$pagina'                           => $pagina,
                '$codigoHotel'                      => $respuestaFiltros['code'][$i],
                '$txtAdultosMenoresHabitaciones'    => $txtAdultosMenoresHabitaciones,
                '$nochesEstancia'                   => ($nochesEstancia->days == 1) ? '1 Noche' : $nochesEstancia->days . ' Noches',
                '$txtFacilidades'                   => $txtFacilidades,
                '$txtPoliticasCancelacion'          => $txtPoliticasCancelacion,
                '$popoverTiposHabitacion'           => $popoverTiposHabitacion,
                '$txtAlertNoHab'                    => $txtAlertNoHab,
            );
    
        echo strtr($template, $vars);
    }
?>

<div>
    <nav>
        <div class="row w-100">
            <div class="col-xs-12 col-sm-6">
                <h4 class="pag-num-hot fw-bold">Mostrando <?php echo ($cContadorFiltros < $HotelPorPagina) ? $cContadorFiltros : $HotelPorPagina; ?> de <?php echo $cContadorFiltros; ?> Hoteles disponibles</h4>
            </div>
            <div class="col-xs-12 col-sm-6">
                <p class="pag-num-pag">Página <?php echo $pagina ?> de <?php echo $paginas ?> </p>
            </div>
        </div>
        <ul class="pagination">
            <!-- Si la página actual es mayor a uno, mostramos el botón para ir una página atrás -->
            <?php if ($pagina > 1) { ?>
                <li class="paginacion">
                    <a href="javascript:;" onclick="PaginacionAjax('<?php echo $res=1; ?>','<?php echo $categoria; ?>','<?php echo $tipCama; ?>','<?php echo $tipPlanes; ?>','<?php echo $preMin; ?>','<?php echo $preMax; ?>','<?php echo $anterior; ?>','<?php echo $Codigo; ?>')">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php } ?>

            <!-- Mostramos enlaces para ir a todas las páginas. Es un simple ciclo for-->
            <?php for ($x = 1; $x <= $paginas; $x++) { ?>
                <li class="<?php if ($x == $pagina) echo "active" ?> paginacion">
                    <a class="pag-act fw-bold" href="javascript:;" onclick="PaginacionAjax('<?php echo $res=1; ?>','<?php echo $categoria; ?>','<?php echo $tipCama; ?>','<?php echo $tipPlanes; ?>','<?php echo $preMin; ?>','<?php echo $preMax; ?>','<?php echo $x; ?>','<?php echo $Codigo; ?>')">
                        <?php echo $x ?></a>
                </li>
            <?php } ?>
            <!-- Si la página actual es menor al total de páginas, mostramos un botón para ir una página adelante -->
            <?php if ($pagina < $paginas) { ?>
                <li>
                    <a href="javascript:;" onclick="PaginacionAjax('<?php echo $res=1; ?>','<?php echo $categoria; ?>','<?php echo $tipCama; ?>','<?php echo $tipPlanes; ?>','<?php echo $preMin; ?>','<?php echo $preMax; ?>','<?php echo $siguiente; ?>','<?php echo $Codigo; ?>')">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>
</div>
<?php }else{?>
    <h1>NO HAY RESULTADOS EN SU FILTRADO</h1>
<?php } ?>