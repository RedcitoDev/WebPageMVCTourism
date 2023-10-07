<?php
// Primero que nada se obtienen la información de la sesion para poder recuperar datos en caso de que existan
error_reporting(0);
session_start();
$infoSesion = $_SESSION;
session_write_close();
require_once('utils/currencyConverter.php');
function establecerServidor($direccion)
{
    $url = ($_SERVER["DOCUMENT_ROOT"] == 'C:/xampp/htdocs') ? "http://localhost/lexgotravel/" . $direccion : "https://lexgotravel.com/" . $direccion;

    return $url;
}


$codigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_STRING);
$infoReserva = file_get_contents(establecerServidor("admin/privado/api/v1/hotel/detallesReservacion?codigo=$codigo"));
$infoReserva = json_decode($infoReserva, true);

$idBooking = (int)$infoReserva["id"][0];
$infoHotel = file_get_contents(establecerServidor("admin/privado/api/v1/hotel/detallesHotel?idbooking=$idBooking"));
$infoHotel = json_decode($infoHotel, true);

$code = $infoHotel['code'][0];
$infoImgHotel = file_get_contents(establecerServidor("admin/privado/api/v1/hotel/detalle?code=$code"));
$infoImgHotel = json_decode($infoImgHotel, true);

$idHotel = (int)$infoHotel["id"][0];
$infoHabitacion = file_get_contents(establecerServidor("admin/privado/api/v1/hotel/detallesHabitacion?idHotel=$idHotel"));
$infoHabitacion = json_decode($infoHabitacion, true);


$checkIn = $infoHotel['checkIn'][0];
$checkOut = $infoHotel['checkOut'][0];

$FromCurrency = 'EUR';
$ToCurrency = 'MXN';
$amount = 1;
$converter = new currencyConverter($amount, $ToCurrency, $FromCurrency);
$valorEuro =  $converter->getUpdate();

?>
<?php if (isset($infoReserva)) { ?>
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
        <title>Datos reserva | Lex Go Travel</title>
        
        <?php include("links.php") ?>
    </head>

    <body folio=<?php echo $codigo; ?> >
        <!--HEADER-->
        <link rel="stylesheet" href="js/venobox/venobox.css" type="text/css" media="screen" />
        <?php include("header.php") ?>
        <!--FIN HEADER-->


        <!--INFO HOTELES-->
        <div class="w-100 p-3 tit-reservacion" style="background:#f8f8f8;">
            <h4 class="text-center">Verificación de tu reservación</h4>
        </div>
        <div class="w-100 p-3" style="background:#228ce3;">
            <h4 class="text-white text-center text-num-ver">Su numero de verificación es: <?php echo $infoReserva['reference'][0] ?> Su folio de reservación es: <?php echo $infoReserva['Folio_LGT'][0] ?></h4>
        </div>

        <?php if ($infoReserva['status'][0] === "CONFIRMED") { ?>
        <h3 style="margin: 0 auto;width: 650px;text-align: center;margin: 20px auto;">Status de la reserva: <span class="badge bg-success"><?php echo $infoReserva['status'][0] ?></span></h3>
        <?php } else if ($infoReserva['status'][0] === "CANCELLED") { ?>
        <h3 style="margin: 0 auto;width: 650px;text-align: center;margin: 20px auto;">Status de la reserva: <span class="badge bg-danger"><?php echo $infoReserva['status'][0] ?></span></h3>
        <?php } ?>

        <div class="container">
            <div class="row w-100 m-3 cont-prin-reser" style="background:#f8f8f8; box-shadow: 2px 4px 5px 1px #0000003d;">

                <div class="col-md-8 p-0 m-0" style="height: 342px; background-image: url('https://photos.hotelbeds.com/giata/bigger/<?php echo $infoImgHotel['path'][0]; ?>'); background-size: cover; background-position: center;"></div>

                <div class="col-md-4 m-0 p-0" style="background-image: url(medios/img/Pattern.png) !important; background-attachment: fixed; background-size: contain;">
                    <div class="row m-0 p-5 w-100">
                        <h3><?php echo $infoHotel['name'][0]; ?></h3>
                        <a data-gall='iframe' class='venoboxframe' data-vbtype='iframe' href="https://www.google.com/maps/embed/v1/search?key=AIzaSyCS7kK67VZxjgmzINsI1_C4zamwkNaUaD4&q=<?php echo $infoHotel['name'][0] ?>+<?php echo $infoHotel['zoneName'][0]; ?>&center=<?php echo $infoHotel['latitude'][0]; ?>,<?php echo $infoHotel['longitude'][0]; ?>&zoom=18">
                            <p class="my-3 text-decoration-underline">Mostrar en el mapa</p>
                        </a>
                        <div class="d-flex">
                            <?php
                            $hotelEstrellas = $infoHotel["categoryName"][0];
                            $hotelEstrellas = (int)$hotelEstrellas[0];
                            $htmlEstrellas = "";
                            for ($e = 0; $e < $hotelEstrellas; $e++) {
                            ?>
                                <img class="estrella-1" src="medios/img/Estrellarecomendcioneschica.png" alt="">
                            <?php } ?>
                            <img class="estrella-1" src="medios/img/iconovaloraciondehotelenlista.png" alt="">
                        </div>
                        <br>
                        <p>Check-In &nbsp;&nbsp;&nbsp;| <?php echo date('d M Y', strtotime($checkIn)); ?></p>
                        <p>Check-out | <?php echo date('d M Y', strtotime($checkOut)); ?></p>
                        <br>
                    </div>

                </div>

            </div>

        </div>

        <div class="container">
            <form action="" id="formDatosReservacion">
                <div class="row w-100 m-3 cont-prin-reser" style="background:#f8f8f8; box-shadow: 2px 4px 5px 1px #0000003d;">


                    <div class="col-md-8 m-0 p-0">
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
                                <h4 class="text-white text-center">Información del titular de la reservación</h4>
                            </div>

                            <div class="p-5">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="titularNombre" class="form-label" style="font-size: 12px;">Nombre</label>
                                            <input type="text" name="nombre" value="<?php echo $infoReserva['name'][0] ?>" class="form-control rounded-0 border-0" placeholder="Ingresa tu nombre" id="titularNombre" aria-describedby="emailHelp" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="titularApellido" class="form-label" style="font-size: 12px;">Apellido</label>
                                            <input type="text" name="apellido" value="<?php echo $infoReserva['surname'][0] ?>"" class=" form-control rounded-0 border-0" placeholder="Ingresa tu apellido" id="titularApellido" aria-describedby="emailHelp" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="titularApellido" class="form-label" style="font-size: 12px;">Telefono</label>
                                            <input type="text" name="apellido" value="<?php echo $infoReserva['telefono'][0] ?>"" class=" form-control rounded-0 border-0" placeholder="Ingresa tu apellido" id="titularApellido" aria-describedby="emailHelp" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="titularApellido" class="form-label" style="font-size: 12px;">Correo</label>
                                            <input type="text" name="apellido" value="<?php echo $infoReserva['correo'][0] ?>"" class=" form-control rounded-0 border-0" placeholder="Ingresa tu apellido" id="titularApellido" aria-describedby="emailHelp" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="DivHabitaciones">
                                <?php
                                $template = '<div class="w-100 p-3" style="background:#228ce3;">
                                            <h4 class="text-white text-center">$habitacion ($tipoServicio)</h4>
                                        </div>
                                        <div class="p-5 habitaciones" >
                                            <h5>Titular de la habitacion</h5>
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="mb-3">
                                                        <label for="exampleInputName1" class="form-label">Nombre completo del huesped</label>
                                                        <input type="text" name="titularHabitacion[]" value="$nombre" class="form-control rounded-0 nombreTitular" placeholder="Nombre, Apellido" id="titularHabitacion$i" disabled>
                                                    </div>
                                                </div>                                    
                                                <div class="col-md-1 mt-auto mb-4">
                                                    <span class="d-inline-block" tabindex="0" title="Política de cancelacion" data-bs-placement="right" data-bs-toggle="popover" data-bs-trigger="hover focus" 
                                                    data-bs-content="Puedes cancelar tu reservación totalmente gratis hasta el $fch entre $hora, a partir de esa fecha tendras que pagar una suma de $$precio MXN." data-bs-original-title="Para que sirve esto?">
                                                        <img src="medios/img/iconinfo.png" style="width: 21px; margin-right: 7px;" alt="">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>';

                                if (isset($infoHabitacion['name'][0])) {
                                    $total = $infoReserva['totalNet'][0];
                                    $totalAdultos = (int)"";
                                    $totalMenores = (int)"";
                                    for ($o = 0; $o < count($infoHabitacion['type']); $o++) {
                                        if ($infoHabitacion['type'][$o] == 'AD') {
                                            $totalAdultos = $totalAdultos + count($infoHabitacion['type'][$o]);
                                        } else if ($infoHabitacion['type'][$o] == 'CH') {
                                            $totalMenores = $totalMenores + count($infoHabitacion['type'][$o]);
                                        }
                                        $apellido   = $infoHabitacion['apellido'][$o];
                                        $nombre     = $infoHabitacion['nombre'][$o];
                                        $habitacion = $infoHabitacion['name'][$o];
                                        $tipoServicio = $infoHabitacion['boardName'][$o];
                                        $monto      = $infoHabitacion['amount'][$o];
                                        $fchCancel  = $infoHabitacion['from'][$o];
                                        $fch        = explode("T",$fchCancel);

                                        $vars = array(
                                            '$i'          => $o + 1,
                                            '$nombre'     => $nombre . " " . $apellido,
                                            '$habitacion' => $habitacion,
                                            '$tipoServicio' => $tipoServicio,
                                            '$precio'     => number_format(($monto * $valorEuro),2),
                                            '$fch'        => $fch[0],
                                            '$hora'       => $fch[1],
                                            '$name'       => $habitacion,
                                        );
                                        echo strtr($template, $vars);
                                    }
                                }
                                ?>
                            </div>

                            <div class="w-100 p-3" style="background:#228ce3;">
                                <h4 class="text-white text-center">¿Algun pedido especial?</h4>
                            </div>
                            <div class="p-5 habitaciones" >
                                <label for="exampleInputName1" class="form-label">Esto puede generar un cargo extra y está sujeto a la disponibilidad del alojamiento</label>
                                <textarea name="pedidoEspecial" class="rounded-0" style="width: 100%; height: 113px;" id="pedidoEspecial" cols="30" rows="10" disabled><?php echo $infoReserva['remark'][0]; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 p-0 m-0">
                        <div class="detall-reserv" style="position: -webkit-sticky; position: sticky; top: 158px;">
                            <div class="w-100 bg-white">
                                <div class="p-4 bg-white w-100" style="background-image: url(medios/img/Pattern.png) !important; background-attachment: fixed; background-size: contain;">
                                    <h3 class="text-center">Detalle de compra</h3>
                                </div>


                                <div class="row m-0 p-5 w-100" style="border-bottom: 1px solid #05365e;">
                                    <?php
                                    $checkIn    = new DateTime($checkIn);
                                    $checkOut   = new DateTime($checkOut);

                                    $diasEstancia   = $checkIn->diff($checkOut);
                                    $nochesEstancia = $diasEstancia->days;

                                    if (isset($infoHabitacion['name'][0])) {
                                        $total = $infoReserva['totalNet'][0];
                                        $totalAdultos = (int)"";
                                        $totalMenores = (int)"";
                                        for ($o = 0; $o < count($infoHabitacion['type']); $o++) {
                                            if ($infoHabitacion['type'][$o] == 'AD') {
                                                $totalAdultos = $totalAdultos + count($infoHabitacion['type'][$o]);
                                            } else if ($infoHabitacion['type'][$o] == 'CH') {
                                                $totalMenores = $totalMenores + count($infoHabitacion['type'][$o]);
                                            }
                                        }

                                    ?>
                                        <p>Habitación estandar</p>
                                        <p class="p-1 w-50 text-center" style="border: 1px solid #d7d7d7;">Sólo hospedaje</p>
                                        <br>
                                        <p class="fw-bold">Información de tu reserva</p>
                                        <p>- <?php echo $nochesEstancia; ?> Noches</p>
                                        <p>- <?php echo $totalAdultos; ?> Adultos | <?php echo $totalMenores; ?> Menores</p>
                                        <?php $numHabitaciones = (int)"";
                                        $habitacion = "";
                                        for ($c = 0; $c < count($infoHabitacion['type']); $c++) {
                                            $habitacion = $infoHabitacion['code'][$c];

                                            if ($infoHabitacion['code'][$c] == $habitacion && $infoHabitacion['nombre'][$c] != NULL) {
                                                $numHabitaciones = $numHabitaciones + count($infoHabitacion['code'][$c]);
                                            }

                                            if ($numHabitaciones > 1) {
                                                $numHabitacionesShow = $numHabitaciones . " habitaciones";
                                            } else {
                                                $numHabitacionesShow = $numHabitaciones . " habitación";
                                            }
                                            
                                            $nomHabitaciones[] = $infoHabitacion['name'][$c]
                                        ?>

                                        <?php } ?>
                                        <p>- <?php echo $numHabitacionesShow; ?></p>
                                        <div class="col-md-4">
                                            <h3 class="m-0 fw-bold" style="color:#e3223e !important;">Total</h3>
                                        </div>
                                        <div class="col-md-8">
                                            <h2 class="m-0 fw-bold" style="color:#e3223e !important;">MXN$<?php echo number_format(($total * $valorEuro), 2, '.', ','); ?></h2>
                                            
                                            <p></p>
                                        </div>
                                        <span class="d-flex" tabindex="0" title="Estimado viajero" data-bs-placement="left" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Es un gusto verte llegar hasta aquí; sin embargo, debes considerar que, este precio puede variar dependiendo del cambio actual en la moneda. 
                                            Esto dependiendo de tu origen, ejemplo: EUR a MXN. Contemplando esto, si has hecho tu reservación con un precio y tu banco se demora en procesar el cobro, 
                                            ¡no te preocupes!, pues pese a los cambios constantes de la moneda, el precio se te respetará.
                                            Ahora, es momento de continuar tu reservación. ¡Vamos!" data-bs-original-title="Para que sirve esto?">
                                                <img src="medios/img/iconinfo.png" style="width: 21px; margin-right: 7px;" alt=""><p>El precio podría estar sijeto a cambios</p> 
                                        </span>


                                    <?php } ?>
                                </div>
                            </div>

                            
                           
                        </div>
                    </div>
                </div>
                <div class="container m-3 cont-prin-reser-btn" style="background:#f8f8f8; box-shadow: 2px 4px 5px 1px #0000003d;">
                    <div class="p-5 w-100 cont-btn-reservacion">
                        <div class="d-flex align-items-center justify-content-center btn-reservacion">
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
                            <button type="button" class="btn w-25 rounded-0 text-white fw-bold" onclick="RegresarInicio();" style="background: #e3223e; margin: 0px 97px;">Regresar al inicio</button>
                            <button type="button" class="btn w-25 rounded-0 text-white fw-bold" onclick="checkIn('<?php echo $codigo ?>');" style="background: #e3223e; margin: 0px 97px;">Imprimir CheckIn</button>
                            <button type="button" class="btn w-25 rounded-0 text-white fw-bold" onclick="getDatosReservacion();" style="background: #e3223e; margin: 0px 97px;">Cancelar reserva</button>
=======
                            <button id="btnPaginaInicio" type="button" class="btn w-25 rounded-0 text-white fw-bold" style="background: #e3223e; margin: 0px 97px;">Regresar al inicio</button>
                            <button id="btnDescargarReservacion" type="button" class="btn w-25 rounded-0 text-white fw-bold" style="background: #e3223e; margin: 0px 97px;">Descargar CheckIn</button>
                            <button id="btnCancelarReservacion" type="button" class="btn w-25 rounded-0 text-white fw-bold" style="background: #e3223e; margin: 0px 97px;">Cancelar reserva</button>
<<<<<<< HEAD
>>>>>>> 9e9c11ccbaf4189182b489892b24314e7b47bfeb
=======
<<<<<<< HEAD
>>>>>>> jose
=======
>>>>>>> 9e9c11ccbaf4189182b489892b24314e7b47bfeb
>>>>>>> pablo
<<<<<<< HEAD
>>>>>>> origin/david
=======
>>>>>>> david
>>>>>>> jose
=======
                            <button type="button" class="btn w-25 rounded-0 text-white fw-bold" onclick="RegresarInicio();" style="background: #e3223e; margin: 0px 97px;">Regresar al inicio</button>
                            <button type="button" class="btn w-25 rounded-0 text-white fw-bold" onclick="checkIn('<?php echo $codigo ?>');" style="background: #e3223e; margin: 0px 97px;">Imprimir CheckIn</button>
                            <button type="button" class="btn w-25 rounded-0 text-white fw-bold" onclick="getDatosReservacion();" style="background: #e3223e; margin: 0px 97px;">Cancelar reserva</button>
>>>>>>> jose
=======
=======
>>>>>>> jose
                            <button id="btnPaginaInicio" type="button" class="btn w-25 rounded-0 text-white fw-bold" style="background: #e3223e; margin: 0px 97px;">Regresar al inicio</button>
                            <button id="btnDescargarReservacion" type="button" class="btn w-25 rounded-0 text-white fw-bold" style="background: #e3223e; margin: 0px 97px;">Imprimir CheckIn</button>
                            <?php if ($infoReserva['status'][0] === "CANCELLED") { ?>
                            <button disabled id="btnCancelarReservacion" type="button" class="btn w-25 rounded-0 text-white fw-bold" style="background: #e3223e; margin: 0px 97px;">Cancelar reserva</button>
                            <?php } else { ?>
                            <button id="btnCancelarReservacion" type="button" class="btn w-25 rounded-0 text-white fw-bold" style="background: #e3223e; margin: 0px 97px;">Cancelar reserva</button>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </form>
        </div>
        <P id="resultado"></P>

        <!--FIN INFO HOTELES-->
        <div id="MAPA" class="m-0" style="height: 450px; border-top: 10px solid #05365e;">
            <div class="position-absolute w-100">
                <h3 id="titulo-mapa" class="text-white text-center">Cerca de ti</h3>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.564841352278!2d-89.62110568466804!3d21.01007369379092!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f56778330413d1d%3A0x9a9fe61e1910ccf2!2sLex%20Go%20Tours!5e0!3m2!1ses!2smx!4v1629905404330!5m2!1ses!2smx" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>

        <!--FOOTER-->
        <?php include("footer.php") ?>
        <!--FIN FOOTER-->

        <script>
            $(document).ready(function() {
                $('.venoboxframe').venobox();

                let descargarPdf = function() {
                    let folio = document.querySelector("body").getAttribute("folio");
                    let endpoint = `admin/privado/api/v1/booking/pdf/${folio}?pdf=true`;
                    let url = (window.location.href.split("?")[0].split("/")[0] == "http:") ? "http://localhost/lexgotravel/" + endpoint : "https://lexgotravel.com/" + endpoint;

                    // Para poder descargar el PDF de la respuesta necesitamos establecer el tipo de respuesta como Blob
                    let xhr = new XMLHttpRequest();
                    xhr.open('GET', url, true);
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

                    xhr.send();
                }

                let cancelarReservacion = function() {
                    let folio = document.querySelector("body").getAttribute("folio");
                    let endpoint = `admin/privado/api/v1/booking/${folio}`;
                    let url = (window.location.href.split("?")[0].split("/")[0] == "http:") ? "http://localhost/lexgotravel/" + endpoint : "https://lexgotravel.com/" + endpoint;

                    // TODO: Implementar la funcionalidad completa {Costo de cancelación, advertencias, etc}
                    Swal.fire({
                        title: 'Estas seguro?',
                        text: "No será posible revertir esta acción!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, cancelar!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(url, {
                                method: "DELETE"
                            })
                            .then(res => res.json()) 
                            .then(data => {
                                console.log(data);
                                if (data.hasOwnProperty("booking")) {
                                    Swal.fire(
                                        'Cancelado!',
                                        'Su reservación ha sido cancelada con éxito!',
                                        'success'
                                    );

                                    window.location.reload();
                                } else {
                                    Swal.fire(
                                        data.error.code,
                                        data.error.message,
                                        'question'
                                    );
                                }
                            });
                        }
                    })
                }

                document.getElementById("btnPaginaInicio").addEventListener("click", () => {
                    window.location.href = "./../";
                });

                document.getElementById("btnDescargarReservacion").addEventListener("click", descargarPdf);
                document.getElementById("btnCancelarReservacion").addEventListener("click", cancelarReservacion);
            });
        </script>
    </body>

    </html>
<?php } else { ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Datos de compra</title>
        <?php include("links.php") ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    </head>

    <body>
        <!--HEADER-->
        <link rel="stylesheet" href="js/venobox/venobox.css" type="text/css" media="screen" />
        <?php include("header.php") ?>
        <!--FIN HEADER-->


        <!--INFO HOTELES-->
        <div class="w-100 p-3" style="background:#f8f8f8;">
            <h4 class="text-center">No hay Informacion sobre la reserva</h4>
        </div>
        <div class="w-100 p-3" style="background:#228ce3;">
            <h4 class="text-white text-center">La reserva no existe ó capturo mal su referencia / folio. Si el problema persiste contacte al soporte del sitío</h4>
        </div>

        <div class="container">
            

        </div>

        
        <P id="resultado"></P>

        <!--FIN INFO HOTELES-->
        <div id="MAPA" class="m-0" style="height: 450px; border-top: 10px solid #05365e;">
            <div class="position-absolute w-100">
                <h3 id="titulo-mapa" class="text-white text-center">Cerca de ti</h3>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.564841352278!2d-89.62110568466804!3d21.01007369379092!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f56778330413d1d%3A0x9a9fe61e1910ccf2!2sLex%20Go%20Tours!5e0!3m2!1ses!2smx!4v1629905404330!5m2!1ses!2smx" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>




        <!--FOOTER-->
        <?php include("footer.php") ?>
        <!--FIN FOOTER-->
        <script type="text/javascript" src="js/venobox/venobox.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.venoboxframe').venobox();
            });
        </script>
    </body>

    </html>
<?php } ?>