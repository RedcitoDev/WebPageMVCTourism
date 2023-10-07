<?php
    include_once "vendor/autoload.php";

    use MVC\Core\Middleware\VerifyCsrfToken;
    
    // Primero que nada se obtienen la información de la sesion para poder recuperar datos en caso de que existan
    session_start();
    $infoSesion = $_SESSION;
    
    $csrfClass = new VerifyCsrfToken();
    
    ob_start();
    
    $csrfClass->inputTokenCsrf();
    $csrfInput = ob_get_clean();

    ob_start();
    
    $csrfClass->metaTokenCsrf();
    $csrfMeta = ob_get_clean();

    session_write_close();
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
        <title>Datos de compra</title>

        <?php include ("links.php") ?>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    
    </head>

    <body>
        <!--HEADER-->
        <link rel="stylesheet" href="js/venobox/venobox.css" type="text/css" media="screen" />
        <?php include ("header.php") ?>
        <!--FIN HEADER-->

        <?php
            function establecerServidor($direccion) {
                $url= ($_SERVER["DOCUMENT_ROOT"] == 'C:/xampp/htdocs') ? "http://localhost/lexgotravel/" . $direccion : "https://lexgotravel.com/" . $direccion;

                return $url;
            }

            $totalPersonas  = 0;
            $totalAdultos   = 0;
            $totalMenores   = 0;

            //Calcula el total de personas (Por medio del filtro)
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
            $rateCount          = count($infoSesion['rateKey']['habitaciones']);
            $totalHabitaciones  = 0;
            $rateKey            = [];
            
            for($r=0; $r < $rateCount; $r++){
                $totalHabitaciones = $totalHabitaciones + $infoSesion['rateKey']['habitaciones'][$r]['numeroHabitaciones'];
                $rateKey[]         = $infoSesion['rateKey']['habitaciones'][$r]['rateKey'];
            }

            for ($i=0, $l=count($rateKey); $i < $l; $i++) { 
                $rooms[] = (object) ["rateKey" => $rateKey[$i]];
            }

            $datosCheckRate["rooms"] = $rooms;

            for ($i=0 ; $i < $totalHabitaciones; $i++) { 
                $n = $i+1;
            }

            $codigoHotel = $_SESSION['rateKey']['hotelCode'];
            $infoCompleta = file_get_contents(establecerServidor("admin/privado/api/v1/hotel/detalle?code=$codigoHotel"));;
            $infoCompleta = json_decode($infoCompleta, true);

            $idHotelBDD = (int)$infoCompleta["id"][0];
        ?>

        <?php if (isset($infoSesion['rateKey']['habitaciones'])) { ?>

        <!--PASOS-->
        <div class="container bg-white cont-pasos">
            <div class="row w-100 m-0 p-0">
                <div class="col-md-3 text-center p-4">
                    
                    <div class="p-2 d-flex align-items-center  justify-content-center">
                        <div id="pasos-icon-1-ce" class="p-3" style="width: 81px; height: 81px; border-radius: 50px; background: #228ce3; box-shadow: 0px 0px 7px 1px #00000026;""><img src="medios/img/iconoseleccionblanco.png" style="width: 30px; margin: 6px;"></div>
                    </div>
                    
                    <p class="fw-bold text-center mt-3" style="color: #228ce3;">Selección del destino</p>    
                </div>
                <div class="col-md-3 text-center p-4">
                    
                    <div class="p-2 d-flex align-items-center  justify-content-center">
                        <div id="pasos-icon-22-ce" class="p-3" style="width: 81px; height: 81px; border-radius: 50px; background: #228ce3; box-shadow: 0px 0px 7px 1px #00000026;"><img src="medios/img/iconodescripcionblanco.png" style="width: 30px; margin: 10px;"></div>
                    </div>    
                    
                    <p class="fw-bold text-center mt-3" style="color: #228ce3;">Descripción</p>
                </div>
                <div class="col-md-3 text-center p-4">
                    
                    <div class="p-2 d-flex align-items-center  justify-content-center">
                        <div id="pasos-icon-3-ce" class="p-3" style="width: 81px; height: 81px; border-radius: 50px; background: #228ce3; box-shadow: 0px 0px 7px 1px #00000026;"><img src="medios/img/iconoreservacionblanco.png" style="width: 30px; margin: 10px;"></div>
                    </div>
                    
                    <p class="fw-bold text-center mt-3" style="color: #228ce3;">Reservación</p>
                </div>
                <div class="col-md-3 text-center p-4">
                    <div class="p-2 d-flex align-items-center  justify-content-center">
                        <div id="pasos-icon-4-ce" class="p-3" style="width: 81px; height: 81px; border-radius: 50px; background: #f8f8f8; box-shadow: 0px 0px 7px 1px #00000026;"><img src="medios/img/Confirmacion.png" style="width: 30px; margin: 10px;"></div>
                    </div>
                    <p class="fw-bold text-center mt-3" style="color: #228ce3;">Confirmación</p>
                </div>
            </div>
        </div>
        <!--FIN PASOS-->

        <!--INFO HOTELES-->

        <div class="w-100 p-3" style="background:#f8f8f8;">
            <h4 class="text-center">¡Felicitaciones!, seleccionaste la mejor opción. ¡No te la pierdas!</h4>
        </div>    
        <div class="w-100 p-3" style="background:#228ce3;">
            <h4 class="text-white text-center">¡Ya falta poco!, completa tus datos y finaliza tu compra.</h4>
        </div>

        <div class="container">
            <div class="row w-100 m-3" style="background:#f8f8f8; box-shadow: 2px 4px 5px 1px #0000003d;">
                
                <div class="col-md-8 p-0 m-0" style="height: 342px; background-image: url('https://photos.hotelbeds.com/giata/bigger/<?php echo $infoCompleta['path'][0]; ?>'); background-size: cover;"></div> 

                <div class="col-md-4 m-0 p-0" style="background-image: url(medios/img/Pattern.png) !important; background-attachment: fixed; background-size: contain;">
                    <div class="row m-0 p-5 w-100">
                        <h3><?php echo $infoCompleta['name'][0]; ?></h3>
                        <a data-gall='iframe' class='venoboxframe' data-vbtype='iframe' href="https://www.google.com/maps/embed/v1/search?key=AIzaSyCS7kK67VZxjgmzINsI1_C4zamwkNaUaD4&q=<?php echo $infoCompleta['name'][0]?>+<?php echo $infoCompleta['city'][0];?>&center=<?php echo $infoCompleta['latitude'][0]; ?>,<?php echo $infoCompleta['longitude'][0];?>&zoom=18"><p class="my-3 text-decoration-underline" >Mostrar en el mapa</p></a>
                        <div class="d-flex">
                            <img class="estrella-1" src="medios/img/Estrellarecomendcioneschica.png" alt="">
                            <img class="estrella-1" src="medios/img/Estrellarecomendcioneschica.png" alt="">
                            <img class="estrella-1" src="medios/img/Estrellarecomendcioneschica.png" alt="">
                            <img class="estrella-1" src="medios/img/Estrellarecomendcioneschica.png" alt="">
                            <img class="estrella-1" src="medios/img/Estrellarecomendcioneschica.png" alt="">
                            <img class="estrella-1" src="medios/img/iconovaloraciondehotelenlista.png" alt="">
                        </div>
                        <br>
                        <p>Check-In &nbsp;&nbsp;&nbsp;| <?php echo date('d M Y',strtotime($infoSesion['stay']['checkIn'])); ?></p>
                        <p>Check-out | <?php echo date('d M Y',strtotime($infoSesion['stay']['checkOut'])); ?></p>
                        <br>
                        <div class="col-md-6">
                            <?php 
                                $totalDescuento = (int)"";
                                $totalOriginal = (int)"";
                                for($r=0;$r<count($infoSesion['rateKey']['habitaciones']);$r++){
                                    $totalDescuento = $totalDescuento + (int)$infoSesion['rateKey']['habitaciones'][$r]['precioDescuento'];
                                    $totalOriginal  = $totalOriginal + (int)$infoSesion['rateKey']['habitaciones'][$r]['precioOriginal'];
                                }
                            ?>
                            <p class="fw-bold text-decoration-line-through">MXN$<?php echo number_format($totalOriginal,2,'.',','); ?></p>
                            <p>Precio sin descuento</p><br>
                        </div>
                        <div class="col-md-6">
                            <h6 class="m-0 fw-bold" style="color:#e3223e !important;">MXN$<?php echo number_format($totalDescuento,2,'.',','); ?></h6>
                            <p>Precio por persona</p>
                            <p class="fw-bold">Impuestos incluidos</p>
                        </div>
                    </div>

                </div>            
                
            </div>
            
        </div>

        <div class="container">
            <form method="POST" action="./../ajax-data/datos-reserva.php" id="formDatosReservacion">
                <div class="row w-100 m-3" style="background:#f8f8f8; box-shadow: 2px 4px 5px 1px #0000003d;">
                    

                    <div class="col-md-8 m-0 p-0" >
                        <div>
                            
                            <div class="row w-100 bg-white m-0 p-3">
                                <div class="col-md-6  d-flex align-items-center">
                                    <div>
                                        <p class="fw-bold">Este es un sitio seguro</p>
                                        <p>Utilizamos conexiones seguras para proteger tu información.</p>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6 me-auto ">
                                    <img src="medios/img/ssl.png" class="rounded float-end" style="height: 60px;" alt="icono ssl">
                                    
                                    
                                </div>
                            </div>


                            <div class="w-100 p-3" style="background:#228ce3;">
                                <h4 class="text-white text-center">Titular de la reservación</h4>
                            </div>

                            <!-- INPUT TYPE TEXT PARA NOMBRE(S) Y APELLIDOS DEL TITULAR-->
                            <div class="p-5">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nombreTitular" class="form-label" style="font-size: 12px;">Nombre</label>
                                            <input type="text" name="nombreTitular" value="" class="form-control rounded-0 border-0" placeholder="Ingresa tu nombre" id="nombreTitular" required>
                                        </div>
                                    </div>                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="apellidoTitular" class="form-label" style="font-size: 12px;">Apellido</label>
                                            <input type="text" name="apellidoTitular" value="" class="form-control rounded-0 border-0" placeholder="Ingresa tu apellido" id="apellidoTitular" required>
                                        </div>   
                                    </div>
                                </div>
                                
                                <div class="row ">                                        
                                        <div class="col-md-12">
                                            <h6 class="mb-3">¿A dónde enviamos tu voucher y reservación?</h6>

                                            <!-- INPUT TYPE EMAIL PARA DIRECCIÓN DE CORREO -->
                                            <div class="mb-3">
                                                <label for="correoTitular" class="form-label" style="font-size: 12px;">E-mail donde recibirás tu voucher y reservación:</label>
                                                <input type="email" name="correoTitular" value="" class="form-control rounded-0 " placeholder="Ingresa el e-mail" id="correoTitular" required>
                                            </div>

                                            <!-- INPUT TYPE EMAIL PARA LA CONFIRMACION DE LA DIRECCIÓN DE CORREO -->
                                            <div class="mb-3">
                                                <label for="correoTitularConfirm" class="form-label" style="font-size: 12px;">Confirma tu e-mail:</label>
                                                <input type="email" name="correoTitularConfirm" value="" class="form-control rounded-0 " placeholder="Rectifica tu e-mail" id="correoTitularConfirm" required>
                                            </div>
                                        </div>                                    
                                </div>

                                <style>
                                    .iti {
                                        width: 100%;
                                    }
                                </style>

                                <!-- INPUT TYPE TEL PARA NUMERO DE TELEFONO -->
                                <div class="row mb-3">
                                    <h6 class="mt-3">¿A qué número podemos localizarte?</h6>
                                    <p class="form-p" style="font-size: 12px;">Este dato nos servirá para contactarte en caso de algún dato o necesidad durante la reservación u hospedaje.</p>
                                    <div class="row">
                                        <div class="w-100">
                                            <input type="tel" name="telefono" class="form-control rounded-0" id="telefono" required>
                                        </div>
                                        <p class="form-label form-text">Campo obligatorio</p>
                                    </div>
                                </div>

                                <!-- INPUT TYPE RADIO PARA VIAJE DE TRABAJO -->
                                <div class="row">
                                    <h6 class="mb-2">¿Viaje de trabajo?</h6>
                                </div>

                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="viajeTrabajo" value="1">
                                        Si, viajo por trabajo
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="viajeTrabajo" value="0" checked>
                                        No, no es viaje de trabajo
                                    </label>
                                </div>
                            </div>

                            <div id="divHabitaciones">
                            <?php 
                                $template = '<div class="w-100 p-3" style="background:#228ce3;">
                                                <h4 class="text-white text-center">$habitacion ($tipoServicio)</h4>
                                            </div>
                                            <div class="p-5 habitaciones" style="background:white;">
                                                <h6>Titular de la habitacion</h6>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="mb-3">
                                                            <label class="form-label" style="font-size: 12px;">
                                                                <input type="text" name="titularHabitacion[]" class="form-control rounded-0 nombreHuespedTitular" placeholder="Nombre, Apellido" required>
                                                            Nombre completo del huesped</label>
                                                        </div>
                                                    </div>                                    
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label" style="font-size: 12px;">
                                                                <input type="email" name="correoTitular[]" class="form-control rounded-0 correoHuespedTitular" placeholder="ejemplo@ejemplo.com" required>
                                                            Correo</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-1 mt-1 mb-4">
                                                        <span class="d-inline-block" tabindex="0" title="" data-bs-placement="right" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Necesario para enviar datos de la reservación" data-bs-original-title="Para que es este dato?">
                                                            <img src="medios/img/iconinfo.png" style="width: 21px; margin-right: 7px;" alt="">
                                                        </span>
                                                    </div>
                                                </div>
                                                <p>
                                                    <a class="btn btn-light w-100" data-bs-toggle="collapse" href=#collapse$i role="button" aria-expanded="false" aria-controls="collapseExample">
                                                        Ver las politicas de cancelación
                                                    </a>
                                                </p>
                                                <div class="collapse" id=collapse$i>
                                                <div class="card card-body mt-2">
                                                    $txtPoliticasCancelacion
                                                </div>
                                                </div>

                                                $children
                                            </div>';

                                for ($i = 0; $i < $totalHabitaciones; $i++) {
                                    $children       = [];
                                    $habitaciones   = [];
                                    $tipoServ       = [];

                                    //Conteo de pasajeros menores para cada habitación
                                    $countInfantes = isset($infoSesion['occupancies'][$i]['paxes']) ? count($infoSesion['occupancies'][$i]['paxes']) : 0;
                                    
                                    // Corregir que en lugar de pedir el nombre del niño se debe pedir la edad
                                    $infantes = "";

                                    for($ch=0; $ch < $countInfantes; $ch++){
                                        $infantes .= "<input type='hidden' class='edadInfante' value=" . $infoSesion['occupancies'][$i]['paxes'][$ch]["age"] . ">";
                                    }

                                    //Conteo de habitaciones para colocar nombre 
                                    $countHab = isset($infoSesion['rateKey']['habitaciones']) ? count($infoSesion['rateKey']['habitaciones']) : 0;
                                    
                                    for($h = 0; $h < $countHab; $h++){
                                        $countHabInd = isset($infoSesion['rateKey']['habitaciones'][$h]['numeroHabitaciones']) ? $infoSesion['rateKey']['habitaciones'][$h]['numeroHabitaciones'] : 0;
                                        
                                        $txtCancelaciones   = "";
                                        if (isset($infoSesion['rateKey']['habitaciones'][$h]['politicasCancelacion'])) {
                                            for ($k=0, $n=count($infoSesion['rateKey']['habitaciones'][$h]['politicasCancelacion']); $k < $n; $k++) { 
                                                $precioCancelacion = $infoSesion['rateKey']['habitaciones'][$h]['politicasCancelacion'][$k]["cantidad"] ;
                                                $txtFecha = $infoSesion['rateKey']['habitaciones'][$h]['politicasCancelacion'][$k]["desde"];
                                                $txtFecha = explode("T", $txtFecha)[0];
                                                $fecha = DateTime::createFromFormat('Y-m-d', $txtFecha);
                                                $fecha = $fecha->format('Y-m-d');
    
                                                $txtCancelaciones .= "Podras cancelar esta habitacion antes del $fecha a un precio de $precioCancelacion MXN<br>";
                                            }
                                        }

                                        for($in=0;$in<$countHabInd;$in++){
                                            $habitaciones[] = $infoSesion['rateKey']['habitaciones'][$h]['nombreHabitacion'];
                                            $tipoServ[] = $infoSesion['rateKey']['habitaciones'][$h]['tipoServicio'];
                                        }
                                    }
                                    
                                    $hab  = isset($habitaciones[$i]) ? $habitaciones[$i] : "";
                                    $serv = isset($tipoServ[$i])     ? $tipoServ[$i]     : "";

                                    $vars = array(
                                        '$i'                        => $i,
                                        '$habitacion'               => $hab,
                                        '$tipoServicio'             => $serv,
                                        '$children'                 => $infantes,
                                        '$txtPoliticasCancelacion'  => $txtCancelaciones
                                    );
                            
                                    echo strtr($template, $vars);
                                }
                            ?>
                            </div>


                            <div class="w-100 p-3" style="background:#228ce3;">
                                <h4 class="text-white text-center">¡Quiero recibir las mejores ofertas!</h4>
                            </div>

                            <!-- INPUT TYPE CHECKBOX PARA NOTIFICACIONES POR EMAIL -->
                            <div class="form-check p-5">
                                <div class="m-5">
                                    <input class="form-check-input" type="checkbox" id="ofertasCorreo" name="ofertasCorreo">
                                    <label class="form-check-label fw-bold" for="ofertasCorreo">
                                        Recibir ofertas a mi correo electrónico
                                    </label>
                                    <p>Recibirás e-mails con las mejores promociones para tu viaje. Si no lo deseas, ignora este mensaje.</p><br>
                                    <p>Para más información consulta las políticas de privacidad.</p>
                                </div>
                                
                            </div>

                            <div class="w-100 p-3" style="background:#228ce3;">
                                <h4 class="text-white text-center">¿Algun pedido especial?</h4>
                            </div>

                            <!-- TEXTAREA PARA PEDIDOS ESPECIALES (SI LOS HAY) -->
                            <div class="form-check p-5">
                                <div class="m-5">
                                    <p class="mb-3">Esto puede generar un cargo extra y está sujeto a la disponibilidad del alojamiento</p>
                                    <textarea name="pedidoEspecial" style="width: 100%; height: 113px;" id="pedidoEspecial" cols="30" rows="10"></textarea>
                                </div>
                            </div>


                            <div class="w-100 p-3" style="background:#228ce3;">
                                <h4 class="text-white text-center">Cupón de descuento</h4>
                            </div>

                            <!-- INPUT TYPE TEXT PARA CUPONES DE DESCUENTO -->
                            <div class="m-3  p-5">
                                <div class=" row mb-3">
                                    <div class="col-md-12 p-3">
                                        <p for="cuponDescuento" class="form-label">Código</p>
                                        <input type="text" class="form-control rounded-0" placeholder="Ingresa tu código" name="cuponDescuento" id="cuponDescuento">
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-center m-5">
                                    <button type="button" class="btn w-25 rounded-0 text-white fw-bold" style="background: #228ce3; margin: 0px 97px;">Validar cupón</button>
                                </div>
                                <p class="" style="text-align: justify;">El cupón descontará primero la tarifa base y luego los impuestos y tasas correspondientes. En caso de que el valor del descuento no llegue a cubrir el total de los impuestos y tasas, los mismos no serán descontados, ya que no es posible su pago parcial con esta modalidad</p>
                            </div>
                        </div>
                    </div>  
                    
                    <!-- RESUMEN STICKY -->
                    <div class="col-md-4 p-0 m-0" >
                        <div style="position: -webkit-sticky; position: sticky; top: 158px;">
                            <div class="w-100 bg-white" >
                                <div class="p-4 bg-white w-100" style="background-image: url(medios/img/Pattern.png) !important; background-attachment: fixed; background-size: contain;"">
                                    <h3 class="text-center">Detalles de compra</h3>
                                </div>
                            
                                <div class="row m-0 p-5 w-100" style="border-bottom: 1px solid #05365e;">
                                    <?php 
                                        $checkIn    = new DateTime($infoSesion["stay"]["checkIn"]);
                                        $checkOut   = new DateTime($infoSesion["stay"]["checkOut"]);
                    
                                        $diasEstancia   = $checkIn->diff($checkOut);
                                        $nochesEstancia = $diasEstancia->days;

                                        if(isset($infoSesion['rateKey'])){
                                            $total = (int)"";

                                            $total = $infoSesion['rateKey']['precioTotal'];
                                            $totalAdultos = (int)"";
                                            $totalMenores = (int)"";

                                            for($o=0; $o<count($infoSesion['occupancies']); $o++){
                                                $totalAdultos = $totalAdultos + (int)$infoSesion['occupancies'][$o]['adults'];
                                                $totalMenores = $totalMenores + (int)$infoSesion['occupancies'][$o]['children'];
                                            }
                                        
                                    ?>

                                    <p class="fw-bold">Información de tu reserva</p>
                                    <p>- <?php echo $nochesEstancia; ?> Noches</p>
                                    <p>- <?php echo $totalAdultos; ?> Adultos | <?php echo $totalMenores; ?> Menores</p>

                                    <?php for($c=0;$c<count($infoSesion['rateKey']['habitaciones']);$c++){ 
                                        $numHabitaciones = $infoSesion['rateKey']['habitaciones'][$c]['numeroHabitaciones'];
                                        $nomHabitaciones = $infoSesion['rateKey']['habitaciones'][$c]['nombreHabitacion'];

                                        if ($numHabitaciones > 1) {
                                            $numHabitacionesShow = $numHabitaciones." habitaciones";
                                        } else {
                                            $numHabitacionesShow = $numHabitaciones." habitación";
                                        }
                                    ?>
                                    <p>- <?php echo $numHabitacionesShow; ?> <?php echo $nomHabitaciones; ?></p>
                                    <?php } ?>

                                    <div class="col-md-4">
                                        <h3 class="m-0 fw-bold" style="color:#e3223e !important;">Total</h3>
                                    </div>

                                    <div class="col-md-8">
                                        <h2 class="m-0 fw-bold" style="color:#e3223e !important;">MXN$<?php echo number_format($total, 2); ?></h2>
                                    </div>
                                
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <div class="w-100 p-3" style="background:#228ce3;">
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INPUT TYPE CHECKBOX (DEBE SER MARCADO) PARA TERMINOS Y CONDICIONES -->
                <div class="container m-3" style="background:#f8f8f8; box-shadow: 2px 4px 5px 1px #0000003d;">
                    <div class="p-5 w-100">
                        <div class="form-check mb-5 d-flex  justify-content-center">
                            <input class="form-check-input" type="checkbox" name="aceptaTerminos" id="aceptaTerminos" style="border-radius: 17px;" required>
                            <label class="form-check-label" for="aceptaTerminos">
                            Leí y acepto los <a class="term-cond"href="https://www.google.com" target="_blank">terminos y condiciones</a> de compra, políticas de privacidad<br>y políticas de cambios y cancelaciones.
                            </label>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <input id="btnRealizarCompra" type="submit" class="btn w-25 rounded-0 text-white fw-bold" style="background: #e3223e; margin: 0px 97px;" value="Realizar compra"></input>
                        </div>
                    </div>
                </div>

                <?php 
                    echo $csrfInput;
                ?>
            </form>
        </div>

        <?php } else { ?>

            <div class="container mt-5 mb-5 d-flex justify-content-center flex-column align-items-center">
                <div class="d-flex justify-content-center gap-3 mt-5">
                    <img src="./medios/img/iconset/code.png" alt="Code" title="Icono Programación">
                </div>

                <div class="mt-4 w-75 text-center mb-4">
                    <h2 class="fw-bold text-uppercase">La sesión ha expirado o no hay suficiente información</h2>

                    <p class="text-center mt-4" style="font-size: 1.2rem !important;">
                        Esta pagina no se muestra correctamente debido a que no hay suficiente información para mostrar o bien su sesión ha caducado, para solucionar este inconveniente efectué una búsqueda y haga una reservación.
                    </p>
                </div>

                <div class="d-flex justify-content-center gap-3 mb-5 flex-wrap">
                    <a class="btn btn-primary fw-bold"  href="./../">Ir a la pagina principal</a>
                    <a class="btn btn-secondary fw-bold" href="./../destinos">Ver Destinos</a>
                </div>

                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <img style="width: 7rem;" src="./medios/img/iconset/Ticket.png" alt="Ticket" title="Icono Ticket">
                    <img style="width: 7rem;" src="./medios/img/iconset/Earth.png" alt="Earth" title="Icono Mundo">
                    <img style="width: 7rem;" src="./medios/img/iconset/Hotel4.png" alt="Hotel" title="Icono Hotel">
                    <img style="width: 7rem;" src="./medios/img/iconset/Plane2.png" alt="Plane" title="Icono Avion">
                </div>

            </div>

        <?php }?>

        <!--FIN INFO HOTELES-->
        <div id="MAPA" class="m-0" style="height: 450px; border-top: 10px solid #05365e;">
            <div class="position-absolute w-100"> 
                <h3 id="titulo-mapa" class="text-white text-center">Cerca de ti</h3>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.564841352278!2d-89.62110568466804!3d21.01007369379092!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f56778330413d1d%3A0x9a9fe61e1910ccf2!2sLex%20Go%20Tours!5e0!3m2!1ses!2smx!4v1629905404330!5m2!1ses!2smx" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>

        <!--FOOTER-->
        <?php include ("footer.php") ?>
        <!--FIN FOOTER-->
        <script type="text/javascript" src="js/venobox/venobox.min.js"></script>

        <script>
            $(document).ready(function(){
                $('.venoboxframe').venobox(); 
            });
        </script>

        <script src="js/datosdecompra.js"></script>
    </body>
</html>