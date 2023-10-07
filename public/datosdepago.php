<?php
    include_once "vendor/autoload.php";

    use MVC\Core\Middleware\VerifyCsrfToken;
    
    session_start();
    $infoSesion = $_SESSION;
    $countTarjetas = 0;

    if (isset($infoSesion['rateKey']['tarjetasHotel'])) {
        $countTarjetas = count($infoSesion['rateKey']['tarjetasHotel']);
    }

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
    <title>Datos de compra | Pago</title>
    <?php include ("links.php") ?>

    <!-- OpenPay JS -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
    <script type="text/javascript" src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>
</head>

<script type="text/javascript">
    // Los campos amount, description, etc.. que no están en el formulario de ejemplo, son datos propios de tu aplicación que deben haberse obtenido antes del formulario de pago.
    $(document).ready(function () {
        let idFormulario = "formCard";
        let formulario = document.getElementById(idFormulario);

        OpenPay.setId('muxjo69goowkivk7i1mz');
        OpenPay.setApiKey('pk_54e8d6b6152a425fa5840f3022fc751a');
        OpenPay.setSandboxMode(true);

        //Se genera el id de dispositivo
        var deviceSessionId = OpenPay.deviceData.setup(idFormulario, "device_session_id");

        $('#pay-button').on('click', function (event) {
            event.preventDefault();
            $("#pay-button").prop("disabled", true);
            OpenPay.token.extractFormAndCreate(idFormulario, sucess_callbak, error_callbak);
        });

        var sucess_callbak = function (response) {
            var token_id = response.data.id;

            $('#token_id').val(token_id);
            // console.log("Éxito");
            // return;
            $(`#${idFormulario}`).submit();
        };

        var error_callbak = function (response) {
            var desc = response.data.description != undefined ? response.data.description : response.message;
            alert("ERROR [" + response.status + "] " + desc);
            $("#pay-button").prop("disabled", false);
        };

        let validarTarjeta = function (event) {
            if (OpenPay.card.validateCardNumber(event.target.value)) {
                event.target.style.backgroundColor = "#e8f3e5";

                if (OpenPay.card.cardType(event.target.value) == 'American Express') {
                    document.querySelector("input[data-openpay-card=cvv2").placeholder = '4 Digitos';
                    document.querySelector("input[data-openpay-card=cvv2").maxLength = '4';
                } else {
                    document.querySelector("input[data-openpay-card=cvv2").placeholder = '3 Digitos';
                    document.querySelector("input[data-openpay-card=cvv2").maxLength = '3';
                }
            }
            else {
                event.target.style.backgroundColor = "#f7f2f2";
            }
        }

        let validarExpiracion = function (event) {
            let mes = document.querySelector("input[data-openpay-card=expiration_month").value;
            let anio = document.querySelector("input[data-openpay-card=expiration_year").value;
            
            if (OpenPay.card.validateExpiry(mes, anio)) {
                document.querySelector("input[data-openpay-card=expiration_month").style.backgroundColor = "#e8f3e5";
                document.querySelector("input[data-openpay-card=expiration_year").style.backgroundColor = "#e8f3e5";
            } else {
                document.querySelector("input[data-openpay-card=expiration_month").style.backgroundColor = "#f7f2f2";
                document.querySelector("input[data-openpay-card=expiration_year").style.backgroundColor = "#f7f2f2";
            }
        }

        let validarCVV = function (event) {
            if (OpenPay.card.validateCVC(event.target.value, document.querySelector("input[data-openpay-card=card_number").value)) {
                event.target.style.backgroundColor = "#e8f3e5";
            }
            else {
                event.target.style.backgroundColor = "#f7f2f2";
            }
        }

        document.querySelector("input[data-openpay-card=holder_name").value = '<?php echo $_SESSION["datoCliente"]["nombre"] . " " . $_SESSION["datoCliente"]["apellido"] ?>';
        document.querySelector("input[data-openpay-card=holder_name").focus();

        document.querySelector("input[data-openpay-card=card_number").addEventListener("change",        () => { validarTarjeta(event) });
        document.querySelector("input[data-openpay-card=expiration_month").addEventListener("change",   () => { validarExpiracion(event) });
        document.querySelector("input[data-openpay-card=expiration_year").addEventListener("change",    () => { validarExpiracion(event) });
        document.querySelector("input[data-openpay-card=cvv2").addEventListener("change",               () => { validarCVV(event) });
    });
</script>

<body>

<!--HEADER-->
<?php include ("header.php") ?>
<!--FIN HEADER-->

<?php if (isset($infoSesion['rateKey']['habitaciones'])) { ?>

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
        
           
        <div class="w-100 p-3" style="background:#228ce3;">
            <h4 class="text-white text-center"></h4>
        </div>

        <div class="bg-white p-5 form-pago">
            <div class="image-footer d-flex justify-content-center p-5">
                <img src="medios/img/logolexgotravel.png" alt="">
            </div>
            <h3 class="text-center mb-3">Pago con tarjeta</h3>
            
            <form class="row mx-5 px-3" id="formCard" action="../procesando-pago" method="POST">
                <input type="hidden" name="token_id" id="token_id">

                <input type="hidden" name="titular-nombre" id="titular-nombre" value="<?php echo base64_encode($_SESSION["datoCliente"]["nombre"]) ?>" >
                <input type="hidden" name="titular-apellidos" id="titular-apellidos" value="<?php echo base64_encode($_SESSION["datoCliente"]["apellido"]) ?>" >
                <input type="hidden" name="titular-email" id="titular-email" value="<?php echo base64_encode($_SESSION["datoCliente"]["correo"]) ?>" >
                <input type="hidden" name="titular-telefono" id="titular-telefono" value="<?php echo base64_encode($_SESSION["datoCliente"]["telefono"]) ?>" >
                <input type="hidden" name="precio-total" id="precio-total" value="<?php echo number_format($infoSesion['rateKey']['precioTotal'], 2); ?>" >

                <div class="row w-100 my-3 cont-imp-pago">
                    <input  id="pago-nombre"
                            class="w-100 campo-pago"
                            type="text"
                            placeholder="Nombre del titular de la tarjeta"
                            name="nombreTitular"
                            data-openpay-card="holder_name" >
                </div>
                
                <div class="row w-100 my-3 cont-imp-pago">
                    <div class="col-md-8 ps-0 cont-imp-pago">
                        <input  id="pago-tarjeta"
                                class="w-100 campo-pago"
                                type="text"
                                placeholder="Numero Tarjeta (0000 0000 0000 0000)"
                                name="NumTarjeta"
                                maxlength="16"
                                pattern="^[0-9]"
                                data-openpay-card="card_number">
                    </div>
                    <div class="col-md-4 pe-0 cont-imp-pago">
                        
                        <select class="campo-pago " name="tipTarjeta" id="" style="width: 100%;">
                            <option selected="">Tipo de tarjeta</option>
                            <?php for($i=0;$i<$countTarjetas;$i++){?>
                                <option value="<?php echo $infoSesion['rateKey']['tarjetasHotel'][$i] ?>"><?php echo $infoSesion['rateKey']['tarjetasHotel'][$i] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row w-100 my-3 m-0 p-0 cont-imp-pago">
                    <div class="row col-md-6 cont-imp-pago">
                        <div class="col-md-6 cont-imp-pago imp-fecha ps-0">
                            <input  id="pago-fecha"
                                    class="w-100 campo-pago"
                                    type="text"
                                    name="mesExp"
                                    placeholder="Mes"
                                    maxlength="2"
                                    data-openpay-card="expiration_month">
                        </div>
                        <div class="col-md-6 cont-imp-pago imp-fecha">
                            <input  id="pago-fecha"
                                    class="w-100 campo-pago"
                                    type="text" 
                                    name="anioExp"
                                    placeholder="Año"
                                    maxlength="2"
                                    data-openpay-card="expiration_year">
                        </div>
                    </div>
                    <div class="col-md-6 pe-0 cont-imp-pago">
                        <input  id="pago-cvc"
                                class="w-100 campo-pago"
                                type="password"
                                maxlength="3"
                                name="cvc"
                                placeholder="CVC (3 dígitos)"
                                data-openpay-card="cvv2">
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    <a class="button rght btn-pago" id="pay-button">PAGAR $<?php echo number_format($infoSesion['rateKey']['precioTotal'], 2); ?></a>
                    <!-- <input id="btnPagar" class="btn-pago m-3" type="button" value="PAGAR $<?php echo number_format($infoSesion['rateKey']['precioTotal'], 2); ?>"> -->
                </div>

                <?php echo $csrfInput; ?>
                
            </form>
            <div class="image-footer d-flex justify-content-center p-5">
                <img src="medios/img/tarjetasdedebito.png" alt="">
            </div>
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


    <!--FOOTER-->
    <?php include ("footer.php") ?>
    <!--FIN FOOTER-->
   
</body>
<script src="js/datosdecompra.js"></script>
</html>