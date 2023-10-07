
    <?php 
        // Modal FOTOS DEL HOTEL
        if (isset($detallesHotelFinalizado)) {
            $urlImg = "https://photos.hotelbeds.com/giata/original/" . $infoImagenes["path"][0];
            $strImgArreglo = "";

            for ($i=1, $l=count($infoImagenes['path']); $i < $l; $i++) { 
                $urlImgArreglo = 'https://photos.hotelbeds.com/giata/original/' . $infoImagenes['path'][$i];
                $strImgArreglo .= "<div class='carousel-item' style='width: 100%; height: 521px; background-image: url($urlImgArreglo); background-size: cover; background-position: center;'></div>";
            }

            $template = "<!-- Modal FOTOS DEL HOTEL -->
                        <div class='modal fade' id='modalFotosHotel' tabindex='-1' aria-labelledby='modalFotosHotelLabel' aria-hidden='true'>
                            <div class='modal-dialog modal-xl modal-dialog-centered'>
                                <div class='modal-content fotosDetallesHabitacion'>
                                    <div>
                                        <div id='carouselFotosHotel' class='carousel slide carousel-fade' data-bs-ride='carousel'>
                                            <div class='carousel-inner'>
                                                
                                                    <div class='carousel-item active' style='width: 100%; height: 521px; background-image: url($urlImg); background-size: cover; background-position: center;'></div>
                                                    $strImgArreglo
                                                
                                            </div>
                                            <button class='carousel-control-prev' type='button' data-bs-target='#carouselFotosHotel' data-bs-slide='prev'>
                                                <img src='medios/img/Isotipohaciatras.png' width='36px' alt='...'>   
                                            </button>
                                            <button class='carousel-control-next' type='button' data-bs-target='#carouselFotosHotel' data-bs-slide='next'>
                                                <img src='medios/img/Isotipohaciadelante.png' width='36px' alt='...'>  
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class='w-100 p-3' style='background:#f8f8f8;'>
                                        <h2 class='tit1 text-center m-3'>$nombreHotel</h2>
                                        <div class='d-flex justify-content-center flex-wrap'>
                                            <p class=' mx-1' style='color: #228ce3;'>$direccionHotel | </p>
                                            
                                                <p class='text-decoration-underline mx-1' style='color: #228ce3 !important;'><a data-gall='iframe' class='venoboxframe' data-vbtype='iframe' href='https://www.google.com/maps/embed/v1/search?key=AIzaSyCS7kK67VZxjgmzINsI1_C4zamwkNaUaD4&q=$nombreHotel+$direccionHotel&center=$latitudHotel,$longitudHotel&zoom=18'> Mostrar en el mapa</a> | </p>
                                            
                                                <p class='mx-1' style='color: #228ce3;'>a $txtDistancia de $nombreZona </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>";

            echo $template;
        }
    ?>
    

    <?php
    
        // Modal fotos de la habitacion
        if (isset($detallesHotelFinalizado)) {
            for ($i=0, $l=$noHabitaciones; $i < $l; $i++) {
                $nombreHabitacion   = $habitaciones[$i]["name"];
                $codigoHabitacion   = $habitaciones[$i]["code"];
                
                if (isset($imgHabitacion[$codigoHabitacion])) {                                        
                    $urlImg = "";
                    
                    for ($k=0, $n = count($imgHabitacion[$codigoHabitacion]); $k < $n; $k++) { 
                        $pathImg = isset($imgHabitacion[$codigoHabitacion][$k]) ? str_replace('https://photos.hotelbeds.com/giata/bigger/', 'https://photos.hotelbeds.com/giata/original/', $imgHabitacion[$codigoHabitacion][$k]) : "medios/img/not-found.png";                        
                        
                        if ($k == 0) {
                            $urlImg .= "<div class='carousel-item active carr-img-infohot position-relative' style='width: 100%; height: 521px !important; background-image: url($pathImg); background-size: cover; background-position: center;'> <button type='button' class='btn position-absolute top-0 end-0 fw-bold' data-bs-dismiss='modal' style='background: #f8f8f847; border-radius: 22px; color: #228ce3; '>X</button></div>"; 
                        } else {
                            $urlImg .= "<div class='carousel-item' style='width: 100%; height: 521px !important; background-image: url($pathImg); background-size: cover; background-position: center;'></div>"; 
                        }
                    }
                } else {
                    $pathImg = "medios/img/not-found.png";
                    $urlImg = "<div class='carousel-item active' style='width: 100%; height: 521px !important; background-image: url($pathImg); background-size: cover; background-position: center;'></div>"; 
                }

                $urlImg .= '<button type="button" class="btn position-absolute top-0 end-0 fw-bold" data-bs-dismiss="modal" style="background: #f8f8f847;border-radius: 22px;color: #228ce3;z-index: 1000;">X</button>';
                
                $idModal = str_replace(".", "_", $codigoHabitacion);

                $template = "<!-- Modal Fotos de la habitación -->
                                <div class='modal fade' id='modal$idModal' tabindex='-1' aria-labelledby='label$idModal' aria-hidden='true'>
                                    <div class='modal-dialog modal-xl'>
                                        <div class='modal-content'>
                                            <div>
                                                <div id='carousel$idModal' class='carousel slide carousel-fade' data-bs-ride='carousel'>
                                                    <div class='carousel-inner'>
                                                        $urlImg
                                                    </div>
                                                    <button class='carousel-control-prev my-5' type='button' data-bs-target='#carousel$idModal' data-bs-slide='prev'>
                                                        <img src='medios/img/Isotipohaciatras.png' width='36px' alt='...'>   
                                                    </button>
                                                    <button class='carousel-control-next my-5' type='button' data-bs-target='#carousel$idModal' data-bs-slide='next'>
                                                        <img src='medios/img/Isotipohaciadelante.png' width='36px' alt='...'>  
                                                    </button>
                                                </div>
                                            </div>
                                            <h3 class='text-center mt-4'>Información de la habitación</h3>";

                $templateInfoReservacion = "";

                if (isset($hotelSeleccionado)) {
                    // Calcula el numero de dias que estará en el hotel
                    $checkIn    = new DateTime($response["hotels"]["checkIn"]);
                    $checkOut   = new DateTime($response["hotels"]["checkOut"]);

                    $diasEstancia   = $checkIn->diff($checkOut);
                    $nochesEstancia = $diasEstancia->days - 1;


                    if (isset($hotelSeleccionado["rooms"][$i]["rates"])) {
                        $precioHabitacion   = $hotelSeleccionado["rooms"][$i]["rates"][0]["net"] * $valorDivisaHotel;

                        $txtFecha               = $hotelSeleccionado["rooms"][$i]["rates"][0]["cancellationPolicies"][0]["from"];
                        $txtFecha               = explode("T", $txtFecha)[0];
                        $formato                = 'Y-m-d';
                        $fechaCancelacionGratis = date($formato, strtotime($txtFecha."- 1 days"));

                        $txtCancelacionGratis   = "Puedes cancelar GRATIS hasta el $fechaCancelacionGratis a las 23:59";
                    }
                                    
                    $precioHabitacionOriginal   = "MXN$" . number_format($precioHabitacion * 1.075, 0);
                    $precioHabitacionDescuento  = "MXN$" . number_format($precioHabitacion, 0);
                    $precioPorPersona           = "MXN$" . number_format(($precioHabitacion / $totalPersonas), 0);

                    $templateInfoReservacion = "<div class='row m-5 info-hotel-1' style='border: 2px solid #ebebeb;'>
                                                    <div class='col-md-6 p-3'>
                                                        <p class='fw-bold text-decoration-line-through'>$precioHabitacionOriginal</p>
                                                        <p>Precio sin descuento</p>
                                                        <br>
                                                        <h6 class='m-0 fw-bold' style='color:#e3223e !important;'>$precioHabitacionDescuento</h6>
                                                        <p>Precio final</p>
                                                        <p class='fw-bold'>Impuestos incluidos</p>
                                                    </div>
                                                    <div class='col-md-6 mt-2 p-0'>
                                                        <div class='bg-red'>
                                                            <p class='text-center ms-auto text-white w-75 p-1' style='background: #228ce3; margin-top: 0px;'>$txtCancelacionGratis</p>
                                                        </div>
                                                    </div>
                                                    <h3 class='m-0 fw-bold' style='color:#e3223e !important;'>Paga con tarjeta y a meses</h3>
                                                    <div class='col-md-6 p-3'>
                                                        
                                                        <p class='fw-bold'>Información de tu reserva</p>
                                                        <p>- $diasEstancia->days dias y $nochesEstancia noches</p>
                                                        <p>- $totalAdultos Adulto(s) | $totalMenores Menor(es)</p>
                                                    </div>                                  
                                                </div>";

                    $template .= $templateInfoReservacion;
                }

                $areaHabitacion = "N/A";
                if (isset($infoHabitacionesFacilidades)) {
                    if (isset($infoHabitacionesFacilidades[$i]["facilityCode"])) {
                        for ($j=0, $m=count($infoHabitacionesFacilidades[$i]["facilityCode"]); $j < $m; $j++) {
                            if ($infoHabitacionesFacilidades[$i]["facilityCode"][$j] == "295") {
                                $areaHabitacion     = $infoHabitacionesFacilidades[$i]["number"][$j] . " metros cuadrados";
                                break;
                            }
                        }
                    }
                }

                $maximoPersonas = $habitaciones[$i]["maxPax"];
                $maximoAdultos  = $habitaciones[$i]["maxAdults"];
                $maximoMenores  = $habitaciones[$i]["maxChildren"];

                $txtTiposDeServicio = "";
                if(isset($infoServicios)) {
                    $txtTiposDeServicio = "<table class='table-striped'>
                                                <thead>
                                                    <tr>
                                                        <th><h6 class='fw-bold'>Opciones de tipo de servicio</h6></th>
                                                    </tr>
                                                </thead>
                                                <tbody>";

                    for ($j=0, $m=count($infoServicios["description"]); $j < $m; $j++) { 
                        if ($infoServicios['description'][$j] != '') {
                            $txtTiposDeServicio .= "<tr>
                                <td><p>- {$infoServicios['description'][$j]}</p></td>
                            </tr>";
                        }
                    }

                    $txtTiposDeServicio .= "</tbody>
                                        </table>
                                        <br>";
                }

                if (isset($facilidades)) {
                    $txtEquipamiento = "";
                    if (isset($facilidades["equipamiento"])) {
                        $txtEquipamiento = "<h6 class='fw-bold'>Habitación</h6>";
    
                        $llaves = array_keys($facilidades["equipamiento"]);
    
                        for ($j=0, $m=count($llaves); $j < $m; $j++) {
                            $titulo = $facilidades["equipamiento"][$llaves[$j]]["titulo"];
                            $txtEquipamiento .= "<p>- $titulo</p>";
                        }

                        $txtEquipamiento .= "<br>";
                    }

                    $txtExtras = "";
                    if (isset($facilidades["entretenimiento"])) {
                        $txtExtras = "<h6 class='fw-bold'>Entretenimiento</h6>";
    
                        $llaves = array_keys($facilidades["entretenimiento"]);
    
                        for ($j=0, $m=count($llaves); $j < $m; $j++) {
                            $titulo = $facilidades["entretenimiento"][$llaves[$j]]["titulo"];
                            $txtExtras .= "<p>- $titulo</p>";
                        }

                        $txtExtras .= "<br>";
                    }
                }
                
                $nombreHabitacion = ucfirst(mb_strtolower($nombreHabitacion));
                $template .= "<div class='p-5 hotel-info-2' style='background:#f8f8f8;'>
                                <h4 class='fw-bold'>$nombreHabitacion, hasta $maximoMenores niños.</h4>
                                <br>
                                <p>- Area de la habitación: $areaHabitacion</p>
                                <p>- $maximoPersonas personas</p>
                                <br>
                                $txtTiposDeServicio
                                <h4 class='fw-bold'>Comodidades Generales</h4><br>
                                $txtEquipamiento
                                $txtExtras
            
                            </div>
                        </div>
                    </div>
                </div>";

                echo $template;
            } 
        } 
        
    ?>

    <?php
        //Modal info hotel
        if (isset($detallesHotelFinalizado)) {
            $htmlPuntosInteres = "";

            if (isset($infoPuntosInteres)) {
                $htmlPuntosInteres = "<div class='col-md-6'>
                                        <h4>Alrededores</h4>
                                        <ul class='list-info-hotel' style='list-style-image: url(medios/img/isotipobiñetalextravel1.png) !important;'>";
    
                for ($i=0, $l=count($infoPuntosInteres["poiName"]); $i < $l; $i++) {
                    $puntoInteres = $infoPuntosInteres["poiName"][$i];
                    $htmlPuntosInteres .= "<li class='listdetalles'>$puntoInteres</li>";
                }

                $htmlPuntosInteres .= "</ul>
                                    </div>";
            }

            if (isset($facilidades)) {
                $txtEquipamiento = "";
                if (isset($facilidades["equipamiento"])) {
                    $txtEquipamiento = "<div class='col-md-6'>
                                            <h4>El alojamiento incluye</h4>
                                            <ul class='list-info-hotel' style='list-style-image: url(medios/img/isotipobiñetalextravel1.png) !important;'>";

                    $llaves = array_keys($facilidades["equipamiento"]);

                    for ($i=0, $l=count($llaves); $i < $l; $i++) {
                        $titulo = $facilidades["equipamiento"][$llaves[$i]]["titulo"];
                        $txtEquipamiento .= "<li class='listdetalles'>$titulo</li>";
                    }

                    $txtEquipamiento .= "</ul>
                                    </div>";
                }

                $txtInstalaciones = "";
                if (isset($facilidades["instalaciones"])) {
                    $txtInstalaciones = "<div class='col-md-6'>
                                            <h4>Servicios</h4>
                                            <ul class='list-info-hotel' style='list-style-image: url(medios/img/isotipobiñetalextravel1.png) !important;'>";
                                            
                    $llaves = array_keys($facilidades["instalaciones"]);

                    for ($i=0, $l=count($llaves); $i < $l; $i++) {
                        $titulo = $facilidades["instalaciones"][$llaves[$i]]["titulo"];
                        $txtInstalaciones .= "<li class='listdetalles'>$titulo</li>";
                    }

                    $txtInstalaciones .= "</ul>
                                    </div>";
                }

                $txtExtras = "";
                if (isset($facilidades["restaurante"]) || isset($facilidades["negocios"]) || isset($facilidades["entretenimiento"])) {
                    $txtExtras = "<div class='col-md-6'>
                                    <h4>Servicios extra</h4>
                                    <ul class='list-info-hotel' style='list-style-image: url(medios/img/isotipobiñetalextravel1.png) !important;'>";



                    if (isset($facilidades["restaurante"])) {
                        $llaves = array_keys($facilidades["restaurante"]);

                        for ($i=0, $l=count($llaves); $i < $l; $i++) {
                            $titulo = $facilidades["restaurante"][$llaves[$i]]["titulo"];
                            $txtExtras .= "<li class='listdetalles'>$titulo</li>";
                        }
                    }
                    
                    if (isset($facilidades["negocios"])) {
                        $llaves = array_keys($facilidades["negocios"]);

                        for ($i=0, $l=count($llaves); $i < $l; $i++) {
                            $titulo = $facilidades["negocios"][$llaves[$i]]["titulo"];
                            $txtExtras .= "<li class='listdetalles'>$titulo</li>";
                        }
                    }

                    if (isset($facilidades["entretenimiento"])) {
                        $llaves = array_keys($facilidades["entretenimiento"]);

                        for ($i=0, $l=count($llaves); $i < $l; $i++) {
                            $titulo = $facilidades["entretenimiento"][$llaves[$i]]["titulo"];
                            $txtExtras .= "<li class='listdetalles'>$titulo</li>";
                        }
                    }
                    
                    $txtExtras .= "</ul>
                                </div>";
                }
            }

            $template = "<!-- Modal Detalles de habitación -->
                        <div class='modal fade' id='exampleModal3' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                            <div class='modal-dialog modal-dialog-centered modal-xl'>
                                <div class='modal-content'>
                                    <div class='row m-4'>
                                        <h2 class='tit1 p-3 mb-3'>$nombreHotel</h2>
                                        $txtEquipamiento
                                        $txtInstalaciones
                                    </div>
                                    <div class='row m-4'>
                                        $txtExtras
                                        <div class='col-md-6'>
                                        $htmlPuntosInteres                                            
                                    </div>
                                </div>
                            </div>
                        </div>";

            echo $template;
        }
    ?>
