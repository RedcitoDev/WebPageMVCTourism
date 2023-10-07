<?php
include_once "vendor/autoload.php";

use Classes\typesHotel;
use Classes\funciones;

$type = new typesHotel();
$fn = new funciones();

$estados = $type->getMejoresEstados();
$cestados = $fn->cuenta($estados);
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
    <title>Nosotros | Lex Go Travel</title>
    <?php include ("links.php") ?>

</head>
<body>
    
    <!--HEADER-->
        <?php include ("header.php") ?>
    <!--FIN HEADER-->

    <!--BODY-->

    <!--PORTADA-->
    <div class="" id="portada-nosotros1" STYLE="height: 346px !important; background-image: url(medios/img/imagenencabezado2.jpg) !important; background-attachment: fixed; background-position: bottom; background-repeat: no-repeat; background-size: cover; border-bottom: 10px solid #228ce3;">
        <div class="px-4 py-5 text-center pt-0 d-flex align-items-center justify-content-center"  id="pantalla" style="height: 100%; background: #0000006b;">
            
            <h1 class="text-center text-white">Vive, sonríe y aventúrate</h1>
        </div>
        
    </div>
    <!--FIN PORTADA-->


    <div class="cont-filtro">
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
    </div>
        

    <!--EL VIAJE DE TU VIDA-->
    <div class="w-100">
        <div class="position-absolute w-100"> 
            <h4 id="titulo-nosotros1" class="text-white text-center">El viaje de tu vida</h4>
        </div>
        <div class="cont-nos-info row w-100">
            <div class="col-md-8 p-5 image-info-1" style="background-image: url('medios/img/parejasupizq.jpg') !important; background-size:cover; background-position: center;     height: 100%;">
                <img class="star-hotel " style="margin-right: -189px; position: absolute; right: 597px; top: 639px;" src="medios/img/maletasup.png" alt="">
            </div>
            <div class="col-md-4 p-5 info-info-1 d-flex align-items-center justify-content-center">
                <p class="p-5 info-info-2" style="text-align: justify; color: #228ce3;">Quienes conocen LexGo Travel, saben que no
                    sólo se trata de un portal dónde encontrar hoteles
                    en distintos puntos de la república y del mundo;
                    sino un sitio dónde se desencadenan grandes
                    experiencias a través de una marca que gracias a
                    su pronto crecimiento, hoy te lleva de la mano a
                    hospedarte con los mejores.
                </p>
            </div>
        </div>
    </div>
    
    <!--FIN EL VIAJE DE TU VIDA-->

    <!--SECCION DE INFORMACIÓN-->
    <div id="INFO" class="py-5" style="background-image: url('medios/img/Parallax1.png');background-attachment: fixed; background-position: center; background-repeat: no-repeat; background-size: cover;">
        <div class="p-5"></div>
            <div class="row m-0 w-100" style="background:#f5f5f5; box-shadow: 0px 0px 11px 1px #00000082; background-repeat: no-repeat !important; background-size:cover">
                <div class=" m-0 p-0">
                    <div style="background:#228ce3;" class="m-0 p-3"><h4 class="fw-bold text-white text-center">Tomando con suma importancia</h4></div>
                    <div style="background-image: url(medios/img/Pattern.png) !important; background-attachment: fixed; background-size: contain;" class="d-flex p-5 m-0 row sec-nos-info">
                        <div class="p-2"></div>
                        <div class="row sec-nos-info-info">
                            <div id="secc-inf-nosotros" class="col-md-3 p-3 text-center" style="font-size: 12px; color: #228ce3;"><h6 class="fw-bold">Tu confianza en nosotros.</h6></div>
                            <div id="secc-inf-nosotros" class="col-md-3 p-3 text-center" style="font-size: 12px; color: #228ce3;"><h6 class="fw-bold">Nuestro compromiso contigo.</h6></div>
                            <div id="secc-inf-nosotros" class="col-md-3 p-3 text-center" style="font-size: 12px; color: #228ce3;"><h6 class="fw-bold">Tu seguridad total.</h6></div>
                            <div id="" class="col-md-3 p-3 text-center" style="font-size: 12px; color: #228ce3;"><h6 class="fw-bold">Al mejor precio del mercado.</h6></div>
                        </div>
                        <div class="p-2"></div>
                    </div>
                </div>
                
            </div>
        <div class="p-5"></div>
    </div>
    <!--FIN SECCION DE INFORMACIÓN-->
        
    <!--DESDE 2020-->
    <div>
        
        <div class="row w-100 m-0" style="height: 484px;">
            
            <div class="col-md-4 p-5 d-flex align-items-center justify-content-center">
                <div>
                    <h6 class="text-center fw-bold" style="color: #228ce3; margin-bottom: 17px;">Desde el 2020</h6>
                    <p style="text-align: justify; color: #228ce3;">LexGo  inicia este proyecto en el 2020
                        pensando en viajeros que como tú, se encuentran
                        en la constante búsqueda de destinos donde el
                        precio, la calidad y por supuesto, la seguridad
                        tanto de su inversión como de su estancia, es un
                        tema crucial. ¿Te identificas?
                        <br><br>
                        Ahora que sabes de nosotros, adéntrate a conocer
                        la gran diversidad de hoteles que podrás
                        encontrar fácilmente. Sólo ten en cuenta que el
                        límite depende de tu imaginación para hacer de
                        tu viaje, el mejor de tu vida. 
                    </p>
                    <p class="text-center" style="color: #228ce3; margin: 16px 0px;">Bienvenidos a <b>LexGo Travel</b></p>
                    <a type="button" href='./../destinos.php' class="btn w-100 rounded-0 text-white fw-bold" style="background: #e3223e;">Descubre el mundo</a>
                </div>
            </div>
            <div class="col-md-8 p-5 img-info-2" style="background-image: url('medios/img/parejasderechamid.jpg') !important; background-size:cover; background-position: center;">
                
            </div>
        </div>
    </div>
    <!--FIN DESDE 2020-->

    <!--CONOCE-->
    <div class="row m-0 w-100 d-flex align-items-center">
        <?php for($i=0;$i<$cestados;$i++){ 
            if($estados['imgweb'][$i] == NULL){$img = "medios/img/not-found.png";}else{$img = $estados['imgweb'][$i];}?>
        <div  class="col-md-3 m-0 p-0" style="height: 565px;">
            <div id="dest-img" class="bg-black text-white p-5 text-center justify-content-center" style="height: 565px; background-image: url('<?php echo $img ?>') !important; background-size:cover; background-position: center;"></div>
            <div id="sect-btn" class="col-6 mx-auto" style="position: relative; top: -298px;">
                <a type="button" href='./../conoce.php?statecode=<?php echo $estados['code'][$i]; ?>&countrycode=<?php echo $estados['countryCode'][$i]; ?>&namestate=<?php echo $estados['name'][$i]; ?>' class="btn w-100 rounded-0 text-white fw-bold" style="background: #e3223e;">Conoce <?php echo ucfirst(strtolower($estados['name'][$i])); ?></a>
                <p class="text-white text-center mx-5 p-1" style="background: #05365e;"><?php echo number_format($estados['total'][$i],0); ?> hoteles</p>
            </div>
        </div>
        <?php } ?>    
    </div>
    <!--FIN CONOCE-->

    <!--SECCION DE CONTACTO-->
    <div class="w-100 py-5" id="CONTACTO" style="background-image: url('medios/img/imagenformulario.jpg') !important; background-size: cover; background-repeat: no-repeat; background-position: center;" >
        <div class="row p-3 m-0 w-100">
        <div class="col-md-3"></div>
        <div id="contact1" class="col-md-6 d-flex justify-content-center">
            <div class="px-5" action="">
                
                <form id="formContacto" class="px-5 for-cont-index" action="">
                    <h5 class="text-center fw-bold">Mantente al tanto<br>de las mejores ofertas</h5>
                    <input type="text" name="nombre" class="form-control text-center mb-4 w-100 border-0 mt-5" id="form-contacto" placeholder="Nombre:">
                    <input type="email" name="correo" class="form-control text-center mb-4 w-100 border-0" id="form-contacto" placeholder="Correo Electrónico:">
                    <button type="button" onclick="enviarCorreo();" class="btn w-100 fw-bold rounded-0 text-white" style="background: #e3223e;">Suscribirse</button>
                </form>
                
            </div>
            
        </div>
        <div class="col-md-3"></div>
            
        </div>
    </div>
    
    <!--FIN SECCION DE CONTACTO-->

    <!--SECCION DE TESTIMONIOS-->
    <div id="testimonios " class="" style="background:#05365e;">
        <h4 class="text-white text-center">Top Ejecutivos</h4>
        <div class="" style="background:#05365e; padding: 0% 4%; margin-bottom: 45px;">
            <div class="row w-100 m-0 p-0">
                <div class="col-md-3"><img id="img-ejec" class="img-aten-cli" style="height: 240px;" src="medios/img/aac1.png" alt=""></div>
                <div class="col-md-3"><img id="img-ejec" class="img-aten-cli" style="height: 240px;" src="medios/img/aac2.png" alt=""></div>
                <div class="col-md-3"><img id="img-ejec" class="img-aten-cli" style="height: 240px;" src="medios/img/aac3.png" alt=""></div>
                <div class="col-md-3"><img id="img-ejec" class="img-aten-cli" style="height: 240px;" src="medios/img/aac4.png" alt=""></div>
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
        <a href="#"><h3 class="text-center text-white p-5 m-0">Ranking de valor al cliente</h3></a>
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

    <!--FIN BODY-->

    <!--FOOTER-->
        <?php include ("footer.php") ?>
    <!--FIN FOOTER-->
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
    
    <script src="js/filtro.js"></script>
</body>
</html>