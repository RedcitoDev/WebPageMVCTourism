<?php
    include_once('vendor/autoload.php');
    use Classes\controladorMySql;

    session_start();
    $idCliente      = $_SESSION['datoCliente']['idCliente'] ?? null;
    $fechas         = $_SESSION['stay'] ?? null;
    $datosReserva   = $_SESSION['datosReserva'] ?? null;
    session_write_close();

    if ($idCliente !== null) {
        $sql            = controladorMySql::getInstance();
        $infoBooking    = $sql->consulta("SELECT id, Folio_LGT FROM `T_Booking` WHERE id_cliente = $idCliente");
        $folio          = $infoBooking["Folio_LGT"][0];
        $idBooking      = $infoBooking["id"][0];
        $infoReserva    = $sql->consulta("SELECT * FROM `T_Booking_Hotel` WHERE id_booking = $idBooking");
        $codeHotel      = $infoReserva["code"][0];
        $infoHotel      = $sql->consulta("SELECT email, web FROM `T_Hotel_Description` WHERE `code` = $codeHotel");
    }
?>

<?php
    session_start();

    // Destruir todas las variables de sesión.
    $_SESSION = array();

    // Si se desea destruir la sesión completamente, borre también la cookie de sesión.
    // Nota: ¡Esto destruirá la sesión, y no la información de la sesión!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finalmente, destruir la sesión.
    session_destroy();
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
    <title>Compra exitosa</title>
    <?php include ("links.php") ?>
</head>

<body folio=<?php echo $folio; ?>>
     <!--HEADER-->
     <?php include ("header.php") ?>
    <!--FIN HEADER-->  

    <?php if ( $datosReserva !== null ) { ?>

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
            <h4 class="text-center">¡Enhorabuena!</h4>
        </div>    
        <div class="w-100 p-3" style="background:#228ce3;">
            <h4 class="text-white text-center">¡Tu compra se ha realizado con éxito!</h4>
            <h6 class="text-white text-center">Se ha enviado la reserva a tu correo...</h6>
            <p class="text-white text-center">(Si no aun no ves tu correo es posible que se encuentre en la carpeta Spam)</p>
        </div>

        <div class="bg-white cont-pag-exi">
            <h4 class="text-center">Numero de Folio: <?php echo $folio ?></h4>

            <div class="d-flex flex-column justify-content-center align-items-center mt-2">
                <a class="btn btn-light w-50 border" data-bs-toggle="collapse" href="#collapse0" role="button" aria-expanded="true" aria-controls="collapseExample">
                    Ver mas información de la reserva
                </a>

                <div class="w-75 collapse" id="collapse0">
                    <div class="card card-body mt-2 d-flex justify-content-center align-items-center flex-row flex-wrap gap-3">
                        <div class="alert alert-warning inline-block" style="width: fit-content;" role="alert">
                            <p>Entrada: <?php echo $fechas["checkIn"] ?></p>
                        </div>

                        <div class="alert alert-warning inline-block" style="width: fit-content;" role="alert">
                            <p>Salida: <?php echo $fechas["checkOut"] ?></p>
                        </div>

                        <div class="w-100"></div>
                        
                        <div class="alert alert-primary inline-block" style="width: fit-content;" role="alert">
                            <p>Destino: <?php echo $infoReserva["destinationName"][0] ?></p>
                        </div>

                        <div class="alert alert-primary inline-block" style="width: fit-content;" role="alert">
                            <p>Hotel: <?php echo $infoReserva["name"][0] ?></p>
                        </div>

                        <?php if($infoHotel["web"][0] !== '') {?>
                        <div class="alert alert-primary inline-block" style="width: fit-content;" role="alert">
                            <p>Web Hotel: <a href="//<?php echo $infoHotel["web"][0] ?>" target="_blank"><?php echo $infoHotel["web"][0] ?></a></p>
                        </div>
                        <?php } ?>

                        <?php if($infoHotel["email"][0] !== '') {?>
                        <div class="alert alert-primary inline-block" style="width: fit-content;" role="alert">
                            <p>Email Hotel: <a href="mailto: <?php echo $infoHotel["email"][0] ?>"><?php echo $infoHotel["email"][0] ?></a></p>
                        </div>
                        <?php } ?>

                        <div class="w-100"></div>

                        <div class="alert alert-success inline-block" style="width: fit-content;" role="alert">
                            <p>Contacto LexgoTravel: <a href="mailto: soporte@lexgotravel.com">soporte@lexgotravel.com</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="mt-4 mb-4">
                    <p class="text-comp-ex">¡La aventura está a punto de iniciar!; considera tomar estas vacaciones, como un tiempo de relajación y reflexión.</p>
                    <p class="text-comp-ex">Cárgate de energia y disfruta al máximo tu estadía. Cualquier duda o aclaración, recuerda que Lex Go Travel te respalda en todo momento</p>
                    <p class="text-comp-ex">¡Ya es hora!, checa tu correo electrónico y conserva tu reservación. ¡Nos vemos pronto!</p>
                </div>
                
                <div style="text-align: center;">
                    <button id="btnDescargarReservacion" style="background: #e3223e; margin: 0px 97px; padding: 7px 22px; color: white; border: navajowhite;">Descarga reserva en PDF</button>
                    <p class="text-comp-ex">ó</p>
                    <button id="btnEnviarCorreo" style="background: #e3223e; margin: 0px 97px; padding: 7px 22px; color: white; border: navajowhite;">Reenviar reserva a mi correo</button>
                </div>
                
            </div>
            <div style="padding: 9px; background: #228ce3; text-align: center; margin: 15px 0px;"><h6 class="text-white">Antes de irte...</h6></div>
            <div>
                <p class="text-comp-ex">¡Nos encantaría conocer tu opinión!</p>
                <p class="text-comp-ex">¡Agradecemos tu entera confianza!; Sin embargo, nos encantaría saber cómo te sentiste durante tu reservación para esas formidables vacaciones.</p>
                <p class="text-comp-ex">Esto nos ayuda constantemente a mejorar, para brindarte siempre, el mejor servicio que tú y todos se merecen.</p>
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

    <?php } else { ?>

    <div class="container mb-3 d-flex justify-content-center flex-column align-items-center">
        <div class="d-flex justify-content-center gap-3">
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

    <!--FOOTER-->
    <?php include ("footer.php") ?>
    <!--FIN FOOTER-->
    <script>
        $(document).ready(function() {
            let reenvioCorreo = 0;
            let txtProcesando = false;

            let descargarPdf = function(element) {
                let folio = document.querySelector("body").getAttribute("folio");
                let endpoint = `admin/privado/api/v1/booking/pdf/${folio}?${element}=true`;
                let url = (window.location.href.split("?")[0].split("/")[0] == "http:") ? "http://localhost/lexgotravel/" + endpoint : "https://lexgotravel.com/" + endpoint;


                // Para poder descargar el PDF de la respuesta necesitamos establecer el tipo de respuesta como Blob
                var xhr = new XMLHttpRequest();
                
                xhr.open('GET', url, true);
                
                if (element == "pdf") {
                    xhr.responseType = 'blob';
                    
                    xhr.onload = function(e) {
                        if (this.status == 200) {
                            let blob = new Blob([this.response], {type: 'application/pdf'});
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = folio + ".pdf";
                            link.click();  
                        }
                    };
                }
                
                if (element == "mail") {                    
                    xhr.responseType = 'text';

                    xhr.onreadystatechange = function(e) {
                        if (xhr.readyState == 4) {
                            if (xhr.status == 200) {
                                if (reenvioCorreo > 0) {
                                    document.getElementById("btnEnviarCorreo").textContent = "Enviado ✅";
                                    document.getElementById("btnEnviarCorreo").style.backgroundColor = "#26562c";
                                    document.getElementById("btnEnviarCorreo").disabled = true;
                                    document.getElementById("btnEnviarCorreo").style.cursor = 'not-allowed';
                                }

                                txtProcesando = true;
                            }
                            reenvioCorreo = reenvioCorreo + 1;
                        }
                    };
                }

                xhr.send();
            }

            document.getElementById("btnDescargarReservacion").addEventListener("click", () => {descargarPdf("pdf")});
            document.getElementById("btnEnviarCorreo").addEventListener("click", () => {
                descargarPdf("mail");
                document.getElementById("btnEnviarCorreo").textContent = "Procesando 📧";
            });

            descargarPdf("mail");
        });
    </script>
</body>
</html>