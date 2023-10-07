<?php
    include_once "vendor/autoload.php";

    use MVC\Core\Middleware\VerifyCsrfToken;
    use Classes\typesHotel;
    use Classes\funciones;

    $type = new typesHotel();
    $fn = new funciones();

    $Paises = $type->getMejoresPaises();
    $cPaises = $fn->cuenta($Paises);

    session_start();
    
    $csrfClass = new VerifyCsrfToken();

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
    <base href="<?php echo $servidor ?>public/"  >
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $csrfMeta; ?>
    
    <title>Lex Go Travel</title>
    
    <link rel="stylesheet" href="js/venobox/venobox.min.css" type="text/css" media="screen" />
    <?php include_once("links.php") ?>
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <!-- <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WF9C9G6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> -->
    <!-- End Google Tag Manager (noscript) -->   
    
    <!--HEADER-->
     <?php include_once ("header.php") ?>
    <!--FIN HEADER-->

    <!--BODY-->
    <div class="px-4 py-5 text-center " id="portada" STYLE="height: 590px;margin-top: -3.1rem;max-width: 100%;">
        <div class="col-md-12 col-12 mbottom2 hero">
            <iframe class="hero--video" allow="autoplay;" frameborder="0" src="https://www.youtube.com/embed/Hbrwoo0IPx0?autoplay=1&controls=1&showinfo=0&autohide=1&mute=1&loop=1&version=3&playlist=n-QZLAnhqL0"></iframe>
        </div>
    </div>
    <div class="px-4 py-5 text-center " id="portada-movil" STYLE="height: 590px;margin-top: -3.1rem;max-width: 100%; display:none; background-image: url(medios/img/Imagen-pincipal.png) !important; background-size: cover;">
        
    </div>
    <!--FILTRO-->
    <div class="container py-4" id="filtro">
        <form id="formFiltro" action="../motordebusqueda" method="POST">
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

                    <input id="location" placeholder="Seleccionar destino" style="background-color:transparent; border: none; color: white;" required/>
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
        <h1 class="my-5 text-center fw-bold">¡Aprovecha ya!</h1>

        <!---<div id="hoteles">
        </div>--->
        <h3 class="mt-5 text-center fw-bold">¡Los mejores hoteles de España!</h3>
        <div id="hotelesEspania" class="p-5">
            <div class="row d-flex flex-wrap justify-content-center">
                
            </div>
        </div>
        
        <h3 class="mt-5 text-center fw-bold">¡Los mejores hoteles de México!</h3>
        <div id="hotelesMexico" class="p-5">
            <div class="row d-flex flex-wrap justify-content-center">
                
                
            </div>
        </div>

        <h3 class="mt-5 text-center fw-bold">¡Los mejores hoteles de Estados Unidos!</h3>            
        <div id="hotelesUSA" class="p-5">
            
            <div class="row d-flex flex-wrap justify-content-center">
                
                
            </div>
        </div>
        <!--FIN SECCIONES HOTELES POPULARES-->

        <!--SECCION DE INFORMACIÓN-->
        <div id="INFO" class="py-5 section-info-index" style="background-image: url('medios/img/Parallax1.png');background-attachment: fixed; background-position: center; background-repeat: no-repeat; background-size: cover;">
                <div class="p-5"></div>
                    <div class="row m-0 w-100" style="background:#f5f5f5; box-shadow: 0px 0px 11px 1px #00000082; background-repeat: no-repeat !important; background-size:cover">
                        <div class="col-md-3 m-0 p-0">
                            <div style="background:#228ce3;" class="m-0 p-3"><h4 class="fw-bold text-white text-center">Compra segura</h4></div>
                            <div style="background:#f5f5f5;" class="p-5 m-0 row sect-info-index">
                                <div class="p-5"></div>
                                <div class="col-4"><img class="float-end" src="medios/img/iconocomprasegura.png" alt=""></div>
                                <div class="col-8 d-flex align-items-center" ><p style="font-size: 14px !important; color: #228ce3; text-align: justify;">Tu viaje es asegurado con los indices más altos de calidad y seguridad avalado por SECTUR.</p></div>
                                <div class="p-5"></div>
                            </div>
                        </div>
                        <div class="col-md-3 m-0 p-0">
                            <div style="background:#228ce3;" class="m-0 p-3"><h4 class="fw-bold text-white text-center">Sin preocupaciones</h4></div>
                            <div style="background:#f5f5f5;" class="p-5 m-0 row sect-info-index">
                                <div class="p-5"></div>
                                <div class="col-4"><img class="float-end" src="medios/img/iconosinpreocupaciones.png" alt=""></div>
                                <div class="col-8 d-flex align-items-center" ><p style="font-size: 14px !important; color: #228ce3; text-align: justify;">Si la calidad de nuestros servicios y/o promociones no son lo que se estipula te reembolsamos tu dinero.</p></div>
                                <div class="p-5"></div>
                            </div>
                        </div>
                        <div class="col-md-3 m-0 p-0">
                            <div style="background:#228ce3;" class="m-0 p-3"><h4 class="fw-bold text-white text-center">A los mejores destinos</h4></div>
                            <div style="background:#f5f5f5;" class="p-5 m-0 row sect-info-index">
                                <div class="p-5"></div>
                                <div class="col-4"><img class="float-end" src="medios/img/iconoalosmejoresdestinos.png" alt=""></div>
                                <div class="col-8 d-flex align-items-center" ><p style="font-size: 14px !important; color: #228ce3; text-align: justify;">Para nosotros no sólo es elegir un destino; se trata de encontrar lo que buscas, al mejor precio, con la mejor atención y a tú medida.</p></div>
                                <div class="p-5"></div>
                            </div>
                        </div>
                        <div class="col-md-3 m-0 p-0">
                            <div style="background:#228ce3;" class="m-0 p-3"><h4 class="fw-bold text-white text-center">Al mejor precio</h4></div>
                            <div style="background:#f5f5f5;" class="p-5 m-0 row sect-info-index">
                                <div class="p-5"></div>
                                <div class="col-4"><img class="float-end" src="medios/img/iconoalmejorprecio.png" alt=""></div>
                                <div class="col-8 d-flex align-items-center" ><p style="font-size: 14px !important; color: #228ce3; text-align: justify;">Toma en cuenta que, detrás de todo precio, existe un estudio buscando siempre los costos más accesibles para que tú, sólo llegues a disfrutar.</p> </div>
                                <div class="p-5"></div>
                            </div>
                        </div>
                    </div>
                <div class="p-5"></div>
        </div>
        <!--FIN SECCION DE INFORMACIÓN-->

        <!--SECCION DE DESTINOS Y SUSCRIPCIÓN-->
        <div id="destinos" style="background-image: url(medios/img/Pattern.png) !important; background-attachment: fixed; background-size: contain;">
            <h1 class="py-5 text-center fw-bold">Conoce el mundo</h1>
            <div class="row p-5 w-100">
                <?php for($i=0;$i<$cPaises;$i++){?>
                <div class="col-md-3">
                    
                    <div id="img-cont-hotel" class="" style="width: 100%; height: 331px;background-size:cover; background-image:url('<?php echo $Paises['imgweb'][$i]; ?>');">
                        <img id="franja-decoration1" class="float-start" src="medios/img/Franjasuperior1.png" alt="">
                        <img class="mt-3 me-3 float-end" src="medios/img/lexgotravelblancochico.png" alt="">
                        <img id="franja-decoration2-2" class="float-end" src="medios/img/Franjalateralconoceelmundo.png" alt="">
                    </div>
                    <div><h6 class="mt-3 fw-bold text-center">Los mejores hoteles en:</h6></div>
                    <div><h3 class="mt-3 fw-bold text-center"><?php echo utf8_encode($Paises['description'][$i]) ?></h3></div>
                    <a href="./../lista-hoteles?countrycode=<?php echo $Paises['code'][$i] ?>" class="btn w-100 rounded-0 text-white mb-3 fw-bold" style="background: #e3223e;">Ver hoteles</a>
                
                </div>
                <?php } ?>

                
            </div>
            <div class="w-100" id="CONTACTO">
                <div style="height: 281px;">
                    <div class="row p-5 w-100" style="position: absolute;">
                    <div class="col-md-3"></div>
                    <div id="contact1" class="col-md-6 d-flex justify-content-center" style="box-shadow: 0px 0px 11px 1px #00000082;">
                        <form id="formContacto" class="px-5 for-cont-index" action="">
                            <h5 class="text-center fw-bold">Mantente al tanto<BR>de las mejores ofertas</h5>
                            <input type="text" class="form-control text-center mb-4 w-100 border-0 mt-5" id="form-contacto" name="nombre" placeholder="Nombre:">
                            <input type="email" class="form-control text-center mb-4 w-100 border-0" id="form-contacto" name="correo" placeholder="Correo Electrónico:">
                            <button type="button" class="btn w-100 fw-bold rounded-0 text-white" style="background: #e3223e;" onclick="enviarCorreo()">Suscribirse</button>
                        </form>
                    </div>

                    <div class="col-md-3">

                    </div>
                    
                    </div>
                </div>
                
            </div>
        </div>
        <!--FIN SECCION DE DESTINOS-->

        

        <!--SECCION DE TESTIMONIOS-->
        <div id="testimonios " class="pt-5" style="background:#05365e;">
            <div class="pt-4 tit-nues-clientes"><h4 class="text-white text-center mt-5">Nuestros clientes</h4></div>
            <div class="tripadvisor">
                <div class="row">
                    <div class="elfsight-app-180455a8-9df6-4608-b8d7-3fb770017334"></div>            
                </div>
                <div class="row">
                    <div class="elfsight-app-6b631750-b7b0-47f0-9f3c-a62257c808d3"></div>          
                </div> 
            </div>
            <div class="d-flex justify-content-center">
                <div class="mx-2">
                    <img class="" src="medios/img/Estrellarecomendacionesgrande.png" alt="">
                    <img class="" src="medios/img/Estrellarecomendacionesgrande.png" alt="">
                    <img class="" src="medios/img/Estrellarecomendacionesgrande.png" alt="">
                    <img class="" src="medios/img/Estrellarecomendacionesgrande.png" alt="">
                    <img class="" src="medios/img/Estrellarecomendacionesgrande.png" alt="">
                </div>
                <h4 class="text-white my-0">5.8</h4>
                
            </div>
            <a href="#"><h3 class="text-center text-white p-5 m-0">Todas las reseñas</h3></a>
            <div class="p-5"></div>
        </div>
        <!-- FIN SECCION DE TESTIMONIOS-->

        <!-- SECCION DE MAPA-->
        <div id="MAPA" class="m-0" style="height: 450px;">
            <div class="position-absolute w-100"> 
                <h3 id="titulo-mapa" class="text-white text-center">Cerca de ti</h3>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.564841352278!2d-89.62110568466804!3d21.01007369379092!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f56778330413d1d%3A0x9a9fe61e1910ccf2!2sLex%20Go%20Tours!5e0!3m2!1ses!2smx!4v1629905404330!5m2!1ses!2smx" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <!--FIN SECCION DE MAPA-->

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
        <?php include_once("footer.php") ?>
    <!--FIN FOOTER-->
    
    <script src="js/places/places.min.js"></script>
    
    <script>
        function enviarCorreo(){
            $.ajax({
                type: "post",
                url: "./../templates/ofertas.php",
                data: $("#formContacto").serialize(),
                success: function(response){
                    const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    iconColor: '#228ce3',
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                    })

                    Toast.fire({
                    icon: 'Excelente',
                    title: 'Su mensaje fue enviado correctamente!'
                    })
                }
            })
        }
    </script>

<script type="text/javascript" src="js/venobox/venobox.min.js"></script>
<script src="js/filtro.js"></script>
<script src="js/main.js"></script>

</body>

</html>
