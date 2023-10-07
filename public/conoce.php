<?php
    include_once "vendor/autoload.php";

    use Classes\typesHotel;
    use Classes\funciones;

    $type = new typesHotel();
    $fn = new funciones();

    $nameState = filter_input(INPUT_GET, 'namestate', FILTER_SANITIZE_STRING);
    $countryCode = filter_input(INPUT_GET, 'countrycode', FILTER_SANITIZE_STRING);
    $statecode = filter_input(INPUT_GET, 'statecode', FILTER_SANITIZE_STRING);
    $pagina = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_NUMBER_INT);
    
    if ( $_SERVER["DOCUMENT_ROOT"] == 'C:/xampp/htdocs' ) {
        $url= "http://localhost/lexgotravel/admin/privado/api/v1/hotel/destino";
    } else {
        $url= "https://lexgotravel.com/admin/privado/api/v1/hotel/destino";
    }

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $destinos = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        $destinos = "cURL Error #:" . $err;
    }

    $json_decode = json_decode($destinos, true);

    $HotelPorPagina = 8;
    


    if(isset($_GET['pagina'])){
        $pagina = $_GET['pagina'];
    }

    $inicio = 1;

    if($pagina > 0){
        $inicio = $pagina;
    }else{
        $pagina = 1;
    }

    $fin = $inicio + $HotelPorPagina;

    $limit = $HotelPorPagina;
    $offset = ($pagina - 1) * $limit;

    $contador = $type->CountGetHotelesEstados($countryCode,$nameState,$statecode);
    $total = count($contador['name']);

    $hoteles = $type->GetHotelesEstados($countryCode,$nameState,$statecode,$limit,$offset);
    $choteles = count($hoteles['hotel']);

    $paginas = ceil($total / $HotelPorPagina);

    if($fin > $paginas){
        $fin = $paginas;
        $inicio = $fin;
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
    <base href="<?php echo $servidor ?>public/"  >

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conoce <?php echo ucfirst(strtolower($nameState)) ?> | Lex Go Travel</title>

    <?php include ("links.php") ?>    
</head>
<body>
    
    <!--HEADER-->
    <link rel="stylesheet" href="js/venobox/venobox.min.css" type="text/css" media="screen" />
     <?php include ("header.php") ?>
    <!--FIN HEADER-->
    
    <div class="" id="portada-nosotros" style="height: 335px !important; background-image: url(medios/img/conoce.jpg) !important; background-attachment: fixed; background-position: bottom; background-repeat: no-repeat; background-size: cover; border-bottom: 10px solid #228ce3;">
        <div class="px-4 py-5 text-center pt-0 d-flex align-items-center justify-content-center" id="pantalla" style="height: 100%; background: #0000006b;">
            
            <h1 class="text-center text-white">Conoce <?php echo ucfirst(strtolower($nameState)) ?></h1>
        </div>
        
    </div>
    <!---
        </div>
        <!--FILTRO-->
        <div class="container py-4" id="filtro">
            <form id="formFiltro" action="motordebusqueda" method="POST">
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
                            <option value="" selected disabled>Seleccionar destino</opti on>
                            
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

                                            <div class="d-flex btn-add-habit">
                                                <button class="btn btn-primary" id="agregar">Agregar habitación +</button>
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
                            <input id="btn-filtro" type="submit" class="btn text-light rounded-0 boton-busc" value="Buscar">
                        </div>
                                        
                </div>
            </form>

        </div>
        <!--FIN FILTRO-->
        <div id="cont-principal"> 
            <!--SECCIONES HOTELES POPULARES-->

            <!---<div id="hoteles">
            </div>--->
            <div id="hoteles" class="p-5">
                <div class="row">
                    <h1 class="my-5 text-center fw-bold">¡Los mejores hoteles de <?php echo ucfirst(strtolower($nameState)); ?>!</h1>
                    <?php for($i=0;$i<$choteles;$i++){ 
                        $hotelEstrellas = $hoteles["categoryCode"][$i];


                        if ($hotelEstrellas == "MINI") {
                            $hotelEstrellas = 2;
                        } else if (($hotelEstrellas == "BB") || ($hotelEstrellas == "BB3")) {
                            $hotelEstrellas = 3;
                        } else if (($hotelEstrellas == "SUP") || ($hotelEstrellas == "BOU") || ($hotelEstrellas == "BB4")) {
                            $hotelEstrellas = 4;
                        } else if (($hotelEstrellas == "HIST") || ($hotelEstrellas == "BB5")) { 
                            $hotelEstrellas = 5;
                        } else {
                            $matches = [];
    
                            preg_match_all("/\d/", $hotelEstrellas, $matches);
                            $hotelEstrellas = implode(".", $matches[0]);
                            $hotelEstrellas = round( $hotelEstrellas, 0, PHP_ROUND_HALF_UP);
                        }                                    
    
                        $hotelEstrellas = ($hotelEstrellas > 5) ? 5 : $hotelEstrellas;

                        $ubicacion = "https://www.google.com/maps/embed/v1/search?key=AIzaSyCS7kK67VZxjgmzINsI1_C4zamwkNaUaD4&q= " . str_replace("&", "and", $hoteles['hotel'][$i] . "+" .$hoteles['city'][$i]) . "&center={$hoteles['latitude'][$i]},{$hoteles['longitude'][$i]}&zoom=18";
                        
                        if($hoteles['imgweb'][$i] !== NULL){
                            if (!$data = @file_get_contents($hoteles['imgweb'][$i])) {
                                $img = "medios/img/not-found.png";
                            } else {
                                $img = $hoteles['imgweb'][$i];
                            }
                        }?>

                    <div class="col-md-3 mb-3">
                        <div class="tar-hotel">
                            <div id="img-cont-hotel" class="" style="width: 100%; height: 363px;background-size:cover; background-image:url('<?php echo $img; ?>');">
                                <img id="franja-decoration1" class="float-start" src="medios/img/Franjasuperior1.png" alt="">
                                <img class="mt-3 me-3 float-end" src="medios/img/lexgotravelblancochico.png" alt="">
                                <img id="allinclusive-decoration" class="mt-3 me-3 float-start" src="medios/img/allinclusive2.png" alt="">
                                <img id="franja-decoration2" class="float-end" src="medios/img/Franjalateralconoceelmundo.png" alt="">
                            </div>
                            <div class="cont-tit-hot p-1"><h2 class="mt-3 fw-bold"><?php echo $hoteles['hotel'][$i]; ?></h2></div>
                            <div class="row p-3">
                                <div class="col-md-7 ">
                                <p class="mb-2 border-4 fw-bold" style="font-size:14px; border-bottom: solid #228ce3; color:#228ce3;"><?php echo $hoteles['city'][$i]; ?>·<a data-gall="iframe" class="venoboxframe" data-vbtype="iframe" class="text-decoration-underline" href="<?php echo $ubicacion; ?>">Mostrar en mapa</a></p>
                                
                                    <div class="nav align-middle">
                                        <h5><?php echo $hoteles['tipo'][$i]; ?></h5>
                                        <?php for($e=0;$e<$hotelEstrellas;$e++){?>
                                            <img class="star-hotel mt-1" style="height: 17px;" src="medios/img/Estrellarecomendacionesgrande.png" alt="">
                                        <?php } ?>
                                    </div>
                                
                                </div>
                                <div class="col-md-5 text-center">
                                    <a href='./../detalleshotel?code=<?php echo $hoteles['code'][$i]; ?>';" class="btn w-100 rounded-0 text-white fw-bold" style="background: #e3223e;">Ver más</a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <?php } ?>
                    <div>
                <nav>
                    <div class="row w-100">
                        <div class="col-xs-12 col-sm-6">
                            <h4 class="pag-num-hot fw-bold">Mostrando <?php echo $HotelPorPagina; ?> de <?php echo $total ?> Hoteles disponibles</h4>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <p class="pag-num-pag"><b>Página</b> <?php echo $pagina ?> de <?php echo $paginas ?> </p>
                        </div>
                    </div>
                    <ul class="pagination">
                        <!-- Si la página actual es mayor a uno, mostramos el botón para ir una página atrás -->
                        <?php if ($pagina > 1) { ?>
                            <li class="paginacion">
                                <a href="./../conoce.php?pagina=<?php echo 1 ?>&statecode=<?php echo $statecode ?>&countrycode=<?php echo $countryCode ?>&namestate=<?php echo $nameState ?>">
                                    <span aria-hidden="true">&#x022D8;</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($pagina > 1) { ?>
                            <li class="paginacion">
                                <a href="./../conoce.php?pagina=<?php echo $pagina - 1 ?>&statecode=<?php echo $statecode ?>&countrycode=<?php echo $countryCode ?>&namestate=<?php echo $nameState ?>">
                                    <span aria-hidden="true">&Lt;</span>
                                </a>
                            </li>
                        <?php } ?>

                        <!-- Mostramos enlaces para ir a todas las páginas. Es un simple ciclo for-->
                        <?php for ($x = $inicio; $x <= $fin; $x++) { ?>
                            <li class="<?php if ($x == $pagina) echo "active" ?> paginacion">
                                <a class="pag-act fw-bold" href="./../conoce.php?pagina=<?php echo $x ?>&statecode=<?php echo $statecode ?>&countrycode=<?php echo $countryCode ?>&namestate=<?php echo $nameState ?>">
                                    <?php echo $x ?></a>
                            </li>
                        <?php } ?>
                        <!-- Si la página actual es menor al total de páginas, mostramos un botón para ir una página adelante -->
                        <?php if ($pagina < $paginas) { ?>
                            <li class="paginacion">
                                <a href="./../conoce.php?pagina=<?php echo $inicio + 1 ?>&statecode=<?php echo $statecode ?>&countrycode=<?php echo $countryCode ?>&namestate=<?php echo $nameState ?>">
                                    <span aria-hidden="true">&Gt;</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($pagina < $paginas) { ?>
                            <li class="paginacion">
                                <a href="./../conoce.php?pagina=<?php echo $paginas ?>&statecode=<?php echo $statecode ?>&countrycode=<?php echo $countryCode ?>&namestate=<?php echo $nameState ?>">
                                    <span aria-hidden="true">&ggg;</span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
                </div>
            </div>
            <!--FIN SECCIONES HOTELES POPULARES-->

            <!-- SECCION DE VIAJEROS-->
            <div id="conecta-nuestros-viajeros" class="row m-0 p-0">
                <div id="secc-nuest-viaj" class="col-md-6 py-5 px-0 m-0" style="background-image: url('medios/img/imageninferiorizquierda.jpg') !important; background-size:cover; background-position: center;">
                    <h5 class="text-white w-50 text-center p-3" style="background: #228ce3;">Conecta con nuestros viajeros</h5>
                </div>
                <div id="secc-nuest-viaj" class="col-md-3 bg-white p-5 m-0">
                    <div class="text-center py-5 my-5">
                        <img class="mb-2" src="medios/img/Logolexgotoursinferior.png" alt="">
                        <h6 style="color:#e3223e;">Conoce nuestro México <br>con nuestros tours guiados</h6>
                    </div>
                    
                </div>
                <div id="secc-nuest-viaj" class="col-md-3 p-5 m-0" style="background:#ff7d00;">
                    <div class="text-center py-5 my-5">
                        <img class="mb-2" src="medios/img/Logolextransferinferior.png" alt="">
                        <h6 class="text-white text-uppercase">Nosotros te llevamos<br>en el mejor transporte</h6>
                    </div>
                    
                </div>
            </div>
            <!--FIN SECCION DE VIAJEROS-->
        </div>

    <!--FIN BODY-->

    <!--FOOTER-->
        <?php include ("footer.php") ?>
    <!--FIN FOOTER-->
    <script src="js/filtro.js"></script>

    <script>
        $( document ).ready(() => {
            $('.venoboxframe').venobox();
        });
    </script>
</body>

</html>

   