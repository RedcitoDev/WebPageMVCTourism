<?php
include_once "vendor/autoload.php";

use Classes\typesHotel;
use Classes\funciones;

$type = new typesHotel();
$fn = new funciones();

$estados = $type->getMejoresEstados();
$hotelesNacionales = $type->getHotelesNacionales();
$hotelesInternacionales = $type->getHotelesInternacionales();
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
    <title>Destinos | Lex Go Travel</title>
    <?php include ("links.php") ?>
</head>
<body>
    <!--HEADER-->
    <?php include ("header.php") ?>
    <!--FIN HEADER-->


    <!--PORTADA-->
    
    <div class="w-100 d-flex justify-content-center">
        <!--FILTRO-->
        <div class="container w-100 py-4" id="filtro-destino">
            <form id="formFiltro" action="motordebusqueda" method="POST">
                <div class="row px-5">
                    
                    <style>
                        .puntero{
                            cursor: pointer;
                        }

                        .ocultar{
                            display: none;
                        }
                    </style>
                    <div class="col-md-3 text-center opt-filter">
                        <!-- <select id="location">
                            <option value="" selected disabled>Seleccionar destino</option>
                            
                        </select> --->

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
        <div class="row w-100 m-0 p-0 port-destinos">
            <div class="col-md-6 d-flex align-items-center m-0 p-0 justify-content-center" style="background-image: url(medios/img/imagenencabezado2.jpg) !important; background-position: center; background-repeat: no-repeat; background-size: cover;">
                <div class="w-100 p-3 m-0" style="background: #228ce3 !important">
                    <h2 class="text-center text-white">Mi estilo es viajar</h2>
                </div>
            </div>
            <div class="col-md-6 row m-0 p-0">
                <?php for($i=0;$i<4; $i++){
                if($estados['imgweb'][$i] == NULL){$img = "medios/img/not-found.png";}else{$img = $estados['imgweb'][$i];}?>
                <div class="col-md-6 d-flex align-items-center m-0 p-0 justify-content-center" style="background-image: url('<?php echo $img ?>') !important; background-position: center; background-repeat: no-repeat; background-size: cover; height: 406px;">
                    <div id="sect-btn" class="ms-auto" style="top: -298px;">
                        <a type="button" class="btn w-100 rounded-0 text-white fw-bold" href='./../conoce.php?statecode=<?php echo $estados['code'][$i]; ?>&countrycode=<?php echo $estados['countryCode'][$i]; ?>&namestate=<?php echo $estados['name'][$i]; ?>' style="background: #e3223e;">Conoce <?php echo ucfirst(strtolower($estados['name'][$i]));?></a>
                        <p class="text-white text-center mx-5 p-1" style="background: #05365e;"><?php echo number_format($estados['total'][$i],0); ?> hoteles</p>
                    </div>
                </div>
                <?php } ?>    
            </div>
        </div>
    </div>

    <!--FIN PORTADA-->

    <!--DESTINOS-->
    <!-- TODO: Corregir imagenes que no cargan bien -->
    <div class="row w-100 m-0 p-0">
        <div class="col-md-3 p-5 d-flex align-items-center justify-content-center">
            <a href="./../conoce.php?statecode=<?php echo $hotelesNacionales['code'][0]; ?>&countrycode=<?php echo $hotelesNacionales['countryCode'][0]; ?>&namestate=<?php echo $hotelesNacionales['name'][0];?>">
            <div style="width: 329px; height: 329px;">
                <div style="width: 329px; height: 329px;"><img id="img-ejec" class="" src="<?php if($hotelesNacionales['imgweb'][0] == NULL){echo "medios/img/not-found.png";}else{echo $hotelesNacionales['imgweb'][0];} ?>" alt="<?php echo ucfirst(strtolower($hotelesNacionales['name'][0])); ?>" style="height: 100%; width: 100%; object-fit: cover;"></div>
                <div class="p-3" style="background: #228ce3;"><p class="text-center text-white"><?php echo ucfirst(strtolower($hotelesNacionales['name'][0])).", ".$hotelesNacionales['countryCode'][0]; ?></p></div>
            </div>
            </a>
            
        </div>
        <div class="col-md-6">
            <div class="p-3 m-0 w-100" style="background: #228ce3;"><h2 class="text-center text-white">Hoteles nacionales</h2></div>
            <div class="p-0 m-0 w-100"><img class="w-100" src="<?php if($hotelesNacionales['imgweb'][1] == NULL){echo "medios/img/not-found.png";}else{echo $hotelesNacionales['imgweb'][1];} ?>" alt="<?php echo ucfirst(strtolower($hotelesNacionales['name'][1])); ?>" style="width: 931px;height:323px; object-fit: cover;"></div>
            <div class="p-3 m-0 w-100" style="background: #f8f8f8; border-bottom: 1px solid #05365e;"><h2 class="text-center m-0">Escápate a <?php echo ucfirst(strtolower($hotelesNacionales['name'][1])); ?> </h2><p class="text-center">Si aún no tienes un plan, ésta puede ser tu mejor opción</p></div>

        </div>
        <div class="col-md-3 p-5 d-flex align-items-center justify-content-center">
            <a href="./../conoce.php?statecode=<?php echo $hotelesNacionales['code'][2]; ?>&countrycode=<?php echo $hotelesNacionales['countryCode'][2]; ?>&namestate=<?php echo $hotelesNacionales['name'][2];?>">
            <div>
                <div style="width: 329px; height: 329px;"><img id="img-ejec" class="" src="<?php if($hotelesNacionales['imgweb'][2] == NULL){echo "medios/img/not-found.png";}else{echo $hotelesNacionales['imgweb'][2];} ?>" alt="<?php echo ucfirst(strtolower($hotelesNacionales['name'][2])); ?>" style="height: 100%; width: 100%; object-fit: cover;"></div>
                <div class="p-3" style="background: #228ce3;"><p class="text-center text-white"><?php echo ucfirst(strtolower($hotelesNacionales['name'][2])).", ".$hotelesNacionales['countryCode'][2]; ?></p></div>
            </div>
            </a>
        </div>

    </div>

    <div class="row w-100 m-0 p-0">
        <?php for($i=3;$i<7;$i++){ ?>
        <div class="col-md-3 p-5 d-flex align-items-center justify-content-center">
            <a href="./../conoce.php?statecode=<?php echo $hotelesNacionales['code'][$i]; ?>&countrycode=<?php echo $hotelesNacionales['countryCode'][$i]; ?>&namestate=<?php echo $hotelesNacionales['name'][$i];?>">
            <div>
                <div style="width: 329px; height: 329px;"><img id="img-ejec" class="" src="<?php if($hotelesNacionales['imgweb'][$i] == NULL){echo "medios/img/not-found.png";}else{echo $hotelesNacionales['imgweb'][$i];} ?>" alt="<?php echo ucfirst(strtolower($hotelesNacionales['name'][$i])); ?>" style="height: 100%; width: 100%; object-fit: cover;"></div>
                <div class="p-3" style="background: #228ce3;"><p class="text-center text-white"><?php echo ucfirst(strtolower($hotelesNacionales['name'][$i])).", ".$hotelesNacionales['countryCode'][$i]; ?></p></div>
            </div>
            </a>
        </div>
        <?php } ?>
    </div>    
    
    <div class="row w-100 m-0 p-0">
    <?php for($i=8;$i<12;$i++){ ?>
        <div class="col-md-3 p-5 d-flex align-items-center justify-content-center">
            <a href="./../conoce.php?statecode=<?php echo $hotelesNacionales['code'][$i]; ?>&countrycode=<?php echo $hotelesNacionales['countryCode'][$i]; ?>&namestate=<?php echo $hotelesNacionales['name'][$i];?>">
            <div>
                <div style="width: 329px; height: 329px;"><img id="img-ejec" class="" src="<?php if($hotelesNacionales['imgweb'][$i] == NULL){echo "medios/img/not-found.png";}else{echo $hotelesNacionales['imgweb'][$i];} ?>" alt="<?php echo ucfirst(strtolower($hotelesNacionales['name'][$i])); ?>" style="height: 100%; width: 100%; object-fit: cover;"></div>
                <div class="p-3" style="background: #228ce3;"><p class="text-center text-white"><?php echo ucfirst(strtolower($hotelesNacionales['name'][$i])).", ".$hotelesNacionales['countryCode'][$i]; ?></p></div>
            </div>
            </a>
        </div>
        <?php } ?>

    </div>

    <div class="w-100 p-5 text-center mb-5 cont-destinos" style="background-image: url(medios/img/Pattern.png) !important; background-attachment: fixed; background-size: contain;">
        <div class="row p-5 m-0 w-100 for-cont-destinos">
            <div class="col-md-3"></div>
                <div id="contact1" class="col-md-6 d-flex justify-content-center" style="box-shadow: 0px 0px 11px 1px #00000082;">
                    <div class="px-5" action="">
                        <h5 class="text-center fw-bold">Mantente al tanto<br>de las mejores ofertas</h5>
                        <form class="px-5" action="">
                            <input type="text" class="form-control text-center mb-4 w-100 border-0 mt-5" id="form-contacto" placeholder="Nombre:">
                            <input type="email" class="form-control text-center mb-4 w-100 border-0" id="form-contacto" placeholder="Correo Electrónico:">
                            <button type="button" class="btn w-100 fw-bold rounded-0 text-white" style="background: #e3223e;">Suscribirme</button>
                        </form>
                        
                    </div>
                    
                </div>
            <div class="col-md-3"></div>
                
        </div>
    </div>

    <div class="row w-100 m-0 p-0">
        <div class="col-md-3 p-5 d-flex align-items-center justify-content-center">
            <a href="conoce.php?statecode=<?php echo $hotelesInternacionales['code'][0]; ?>&countrycode=<?php echo $hotelesInternacionales['countryCode'][0]; ?>&namestate=<?php echo $hotelesInternacionales['name'][0];?>">
            <div>
                <div style="width: 329px; height: 329px;"><img id="img-ejec" class="" src="<?php if($hotelesInternacionales['imgweb'][0] == NULL){echo "medios/img/not-found.png";}else{echo $hotelesInternacionales['imgweb'][0];} ?>" alt="<?php echo ucfirst(strtolower($hotelesNacionales['name'][0])); ?>" style="height: 100%; width: 100%; object-fit: cover;"></div>
                <div class="p-3" style="background: #228ce3;"><p class="text-center text-white"><?php echo ucfirst(strtolower($hotelesInternacionales['name'][0])).", ".$hotelesInternacionales['countryCode'][0]; ?></p></div>
            </div>
            </a>
            
        </div>
        <div class="col-md-6">
            <div class="p-3 m-0 w-100" style="background: #228ce3;"><h2 class="text-center text-white">Hoteles internacionales</h2></div>
            <div class="p-0 m-0 w-100"><img class="w-100" src="medios/img/Imagenhotelesinternacionales.jpg" alt=""></div>
            <div class="p-3 m-0 w-100" style="background: #f8f8f8; border-bottom: 1px solid #05365e;"><h2 class="text-center m-0">Disfruta de una noche en la ciudad que nunca duerme</h2><p class="text-center">Nueva York te espera con sus calles iluminadas</p></div>

        </div>
        <div class="col-md-3 p-5 d-flex align-items-center justify-content-center">
            <a href="conoce.php?statecode=<?php echo $hotelesInternacionales['code'][1]; ?>&countrycode=<?php echo $hotelesInternacionales['countryCode'][1]; ?>&namestate=<?php echo $hotelesInternacionales['name'][1];?>">
            <div>
                <div style="width: 329px; height: 329px;"><img id="img-ejec" class="" src="<?php if($hotelesInternacionales['imgweb'][1] == NULL){echo "medios/img/not-found.png";}else{echo $hotelesInternacionales['imgweb'][1];} ?>" alt="<?php echo ucfirst(strtolower($hotelesNacionales['name'][1])); ?>" style="height: 100%; width: 100%; object-fit: cover;"></div>
                <div class="p-3" style="background: #228ce3;"><p class="text-center text-white"><?php echo ucfirst(strtolower($hotelesInternacionales['name'][1])).", ".$hotelesInternacionales['countryCode'][1]; ?></p></div>
            </div>
            </a>
        </div>

    </div>

    <div class="row w-100 m-0 p-0">
        <?php for($i=3;$i<7;$i++){ ?>
        <div class="col-md-3 p-5 d-flex align-items-center justify-content-center">
            <a href="conoce.php?statecode=<?php echo $hotelesInternacionales['code'][$i]; ?>&countrycode=<?php echo $hotelesInternacionales['countryCode'][$i]; ?>&namestate=<?php echo $hotelesInternacionales['name'][$i];?>">
            <div>
                <div style="width: 329px; height: 329px;"><img id="img-ejec" class="" src="<?php if($hotelesInternacionales['imgweb'][$i] == NULL){echo "medios/img/not-found.png";}else{echo $hotelesInternacionales['imgweb'][$i];} ?>" alt="<?php echo ucfirst(strtolower($hotelesNacionales['name'][$i])); ?>" style="height: 100%; width: 100%; object-fit: cover;"></div>
                <div class="p-3" style="background: #228ce3;"><p class="text-center text-white"><?php echo ucfirst(strtolower($hotelesInternacionales['name'][$i])).", ".$hotelesInternacionales['countryCode'][$i]; ?></p></div>
            </div>
            </a>
        </div>
        <?php }?>
    </div>

    <div class="row w-100 m-0 p-0">
        <?php for($i=8;$i<12;$i++){ ?>
        <div class="col-md-3 p-5 d-flex align-items-center justify-content-center">
            <a href="conoce.php?statecode=<?php echo $hotelesInternacionales['code'][$i]; ?>&countrycode=<?php echo $hotelesInternacionales['countryCode'][$i]; ?>&namestate=<?php echo $hotelesInternacionales['name'][$i];?>">
            <div>
                <div style="width: 329px; height: 329px;"><img id="img-ejec" class="" src="<?php if($hotelesInternacionales['imgweb'][$i] == NULL){echo "medios/img/not-found.png";}else{echo $hotelesInternacionales['imgweb'][$i];} ?>" alt="<?php echo ucfirst(strtolower($hotelesNacionales['name'][$i])); ?>" style="height: 100%; width: 100%; object-fit: cover;"></div>
                <div class="p-3" style="background: #228ce3;"><p class="text-center text-white"><?php echo ucfirst(strtolower($hotelesInternacionales['name'][$i])).", ".$hotelesInternacionales['countryCode'][$i]; ?></p></div>
            </div>
            </a>
        </div>
        <?php }?>
    </div>
    
    <div class="w-100 p-3 text-center" style="background-image: url(medios/img/Pattern.png) !important; background-attachment: fixed; background-size: contain;">
        <div class="p-0">
            <h2>Lex Go Travel te lleva por el mundo</h2>
        </div>
        
    </div>
    <!--img class="w-100 p-0 m-0" src="medios/img/Cuadrovideo.png" alt="">
    <!--FIN DESTINOS-->

    <!--FOOTER-->
        <?php include ("footer.php") ?>
    <!--FIN FOOTER-->
    <script src="js/filtro.js"></script>
</body>
</html>