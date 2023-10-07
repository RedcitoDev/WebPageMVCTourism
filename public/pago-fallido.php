<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algo salio mal</title>
    <?php include ("links.php") ?>
</head>
<body>
     <!--HEADER-->
     <?php include ("header.php") ?>
    <!--FIN HEADER-->



    <div class="w-100 m-0 p-0" style="background: #f8f8f8 !important; padding-bottom: 4% !important;">

        <div class="bg-white">
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
                            <div id="pasos-icon-33-ce" class="p-3" style="width: 81px; height: 81px; border-radius: 50px; background: #228ce3; box-shadow: 0px 0px 7px 1px #00000026;"><img src="medios/img/iconoreservacionblanco.png" style="width: 30px; margin: 10px;"></div>
                        </div>
                        
                        <p class="fw-bold text-center mt-3" style="color: #228ce3;">Reservación</p>
                    </div>
                    <div class="col-md-3 text-center p-4">
                        <div class="p-2 d-flex align-items-center  justify-content-center">
                            <div id="pasos-icon-4-ce" class="p-3" style="width: 81px; height: 81px; border-radius: 50px; background: #228ce3; box-shadow: 0px 0px 7px 1px #00000026;"><img src="medios/img/iconoconfirmacionblanco.png" style="width: 30px; margin: 10px;"></div>
                        </div>
                        <p class="fw-bold text-center mt-3" style="color: #228ce3;">Confirmación</p>
                    </div>
                </div>
            </div>
            <!--FIN PASOS-->
        </div>
        
        <div class="w-100 p-3 tit-pago-fallido" style="background:#f8f8f8;">
            <h4 class="text-center">Algo a salido mal</h4>
        </div>    
        <div class="w-100 p-3" style="background:#228ce3;">
            <h4 class="text-white text-center">¡El pago a sido rechasado!</h4>
        </div>

        <div class="bg-white cont-pag-rech">
            <div class="px-5">
                <h5 class="text-comp-ex text-center">Lamentamos el inconveniente; esto muchas veces es debido, a que el método de pago no cuenta con fondos suficientes para realizar la compra o existe algún incorrecto.</h5>
                <br>
                <h5 class="text-comp-ex text-center">Si tienes alguna duda u aclaración, en breve nos pondremos en contacto contigo, o bien, puedes modificar tu método aquí.</h5>
                <br>
            </div>
            <div>
                <div class="w-100 d-flex align-items-center justify-content-center"><li class="nav-item align-middle"><a href="#" class="nav-link link-light bg-black px-1 py-1 my-1 mx-1">Modificar método de pago</a></li></div>
                <div class="w-100 d-flex align-items-center justify-content-center"><li class="nav-item align-middle"><a href="#" class="nav-link link-light bg-black px-1 py-1 my-1 mx-1" style="background: #228ce3 !important;">Ser atendido por un ejecutivo</a></li></div>
                <div style="margin-top: 37px; padding: 16px;">
                    <b><p style="text-align: center;">Comparte tus comentarios en cualquiera de nuestras redes</p></b>
                    <div>
                        <div class="mmoviltop20 py-2 sect-men-3">
                            <ul class="list-unstyled fs-3 m-0 list-inline text-center azul">
                                <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="medios/img/iconofacencabezado.png" alt=""></a></li>
                                <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="medios/img/iconoinstagramencabezado.png" alt=""></a></li>
                                <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="medios/img/iconoyoutubeencabezado.png" alt=""></a></li>
                                <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="medios/img/iconotripadvisorencabezado.png" alt=""></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>



    </div>



    <!--FOOTER-->
    <?php include ("footer.php") ?>
    <!--FIN FOOTER-->
    
</body>
</html>