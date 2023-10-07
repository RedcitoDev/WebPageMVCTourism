<?php
include_once "../vendor/autoload.php";
include_once('./../admin/privado/clases/Types.class.php');
include_once('./../admin/privado/clases/bd.php');
include_once('./../admin/privado/clases/BDD.php');
$folio = filter_input(INPUT_POST, 'folio', FILTER_SANITIZE_STRING);
$condicion = filter_input(INPUT_POST, 'condicion', FILTER_SANITIZE_NUMBER_INT);
use BDD\ControladorMySql;
use TypesHotelBeds\TypesHotel;
use Dompdf\Dompdf;

$type = new TypesHotel();
$htmlReservacionTemplate = file_get_contents("formato-reserva.html");

// Consultar los datos de la reserva mediante el folio de reserva desde la base de datos

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
$datosReserva = $type->CheckDocument("LGT-211130-1");

$htmlHabitaciones   = '';
$htmlHuespedes      = '';

for ($i=0, $l=count($datosReserva['habitacion']); $i < $l; $i++) { 
    $indice = $i + 1;

    $txtTipoServicio = $datosReserva['boardName'][$i];

    $htmlHabitaciones .= "<div class='fila' style='margin-bottom: 1.5rem'>
                                <h3>Información habitación {$datosReserva['habitacion'][$i]}</h3>
                            </div>
                        
                            <div class='fila'>
                                <div class='contenedor-txt-input'>
                                    <div class='d-inline_block v_a-top w-14'>
                                        <p>Tipo de servicio: </p>
                                    </div>

                                    <div class='input-border' style='width: 58.75rem'>
                                        <p>$txtTipoServicio</p>
                                    </div>
                                </div>
                            </div>

                            <div class='fila'>
                                <div class='contenedor-txt-input'>
                                    <div class='d-inline_block v_a-top w-14'>
                                        <p>Observaciones extras: </p>
                                    </div>

                                    <div class='input-border' style='width: 58.75rem'>
                                        <p>{$datosReserva['remark'][$i]}</p>
                                    </div>
                                </div>
                            </div>";

    $htmlHuespedes .= "<div class='fila'>
                            <div class='contenedor-txt-input' style='width: 100%;'>
                                <div class='d-inline_block v_a-top w-14'>
                                    <p>Nombre del huesped [0{$indice}]: </p>
                                </div>

                                <div class='input-border input-txt-medium' style='width: 58.75rem;'>
                                    <p>{$datosReserva["nombreTitular"][$i]} {$datosReserva["apellidoTitular"][$i]}</p>
                                </div>
                            </div>
                        </div>";
}
=======
$datosReserva = $type->CheckDocument($folio);
<<<<<<< HEAD
>>>>>>> 06740aa5b8cfe7fed80aa2d1510f711cc49105f7
=======
$datosReserva = $type->CheckDocument($folio);
>>>>>>> ccbc722c90cea4f4a52a816a302a834229f5c447
=======
$datosReserva = $type->CheckDocument($folio);
=======
<<<<<<< HEAD
>>>>>>> isaac
=======
$datosReserva = $type->CheckDocument($folio);
>>>>>>> isaac
=======
>>>>>>> b500b359216b73f223306a4892ff6fd736744328
=======
$datosReserva = $type->CheckDocument($folio);
>>>>>>> ccbc722c90cea4f4a52a816a302a834229f5c447
>>>>>>> pablo
=======
$datosReserva = $type->CheckDocument($folio);
<<<<<<< HEAD
>>>>>>> origin/david
=======
>>>>>>> david
>>>>>>> jose
>>>>>>> jose

$sql = new ControladorMySql();
$qry = "SELECT thd.id, tc.phoneHotel, ta.content, ta.street, ta.number, ta.postalCode, ta.city from T_Hotel_Description thd
        JOIN T_Hotel_Contact tc ON tc.id_hotel_description = thd.id
        JOIN T_Hotel_Address ta ON ta.id_hotel_description = thd.id
        WHERE code = {$datosReserva['code'][0]}";

$datosExtra = $sql->consulta($qry);

// TODO: Poner el nombre del titular en una sola linea, poner la direccion completa del hotel, formatear correctamente el tipo de servicio
$estrellas = $datosReserva["categoryCode"][0];

if ($estrellas == "MINI") {
    $estrellas = 2;
} else if (($estrellas == "BB") || ($estrellas == "BB3")) {
    $estrellas = 3;
} else if (($estrellas == "SUP") || ($estrellas == "BOU") || ($estrellas == "BB4")) {
    $estrellas = 4;
} else if (($estrellas == "HIST") || ($estrellas == "BB5")) { 
    $estrellas = 5;
} else {
    $matches = [];

    preg_match_all("/\d/", $estrellas, $matches);
    $estrellas = implode(".", $matches[0]);
    $estrellas = round( $estrellas, 0, PHP_ROUND_HALF_UP);
}                                    

$estrellas = ($estrellas > 5) ? 5 : $estrellas;

$vars = array(
    '$fecha'                => date("Y-m-d"),
    '$noFolio'              => $datosReserva["Folio_LGT"][0],
    '$nombreHotel'          => $datosReserva["hotelName"][0],
    '$categoriaHotel'       => $estrellas .  " Estrellas",
    '$destinationName'      => $datosReserva["destinationName"][0],
    '$telefonoHotel'        => $datosExtra["phoneHotel"][0],
    '$direccionHotel'       => $datosExtra["content"][0],
    '$htmlHabitaciones'     => $htmlHabitaciones,
    '$checkIn'              => $datosReserva["checkIn"][0],
    '$checkOut'             => $datosReserva["checkOut"][0],
    '$txtExtras'            => $datosReserva["remark"][0],
    '$txt-apellido-paterno' => $datosReserva["apeResp"][0],
    '$txt-apellido-materno' => $datosReserva["apeResp"][0],
    '$txt-nombre-completo'  => $datosReserva["nombreResp"][0],
    '$txt-numero-tel'       => $datosReserva["telefono"][0],
    '$txt-email'            => $datosReserva["correo"][0],
    '$htmlHuespedes'        => $htmlHuespedes,
);

$htmlReservacionTemplate = strtr($htmlReservacionTemplate, $vars);

$dompdf = new Dompdf([ 'chroot' => $_SERVER["DOCUMENT_ROOT"], 'fontHeightRatio' => 1 ]);

// La medida original de la pagina en px es de 1920 * 2480, sin embargo para definir las medidas en DOMPDF se tienen que especificar en puntos (pt)
// 1pt = 1.333px, por lo tanto nuestras medidas quedarian en (1920 / 1.333 = 1440) * (2480 /  1.333 = 1860)
// Se agrega una tolerancia de 40pt en la altura debido a que al renderizar si no se hace esto sew genera una pagina en blanco 
$customPaper = array(0,0,1440,1860 + 40);
$dompdf->setPaper($customPaper);

$dompdf->loadHtml($htmlReservacionTemplate);
$dompdf->render();
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
$dompdf->stream();
$contenido = $dompdf->output();

// require("../scripts/phpmailer/class.phpmailer.php");
 
// $mail = new PHPMailer();
// $mail->IsSMTP();

// $mail->SMTPSecure = 'tls';
// $mail->SMTPDebug  = 1;
// $mail->SMTPAuth   = true;
// $mail->Host       = "mail.lexgotour.com";
// $mail->Port       = 587;
// $mail->Username   = "isaac.pacheco@lexgotour.com";
// $mail->Password   = "m3uV88g?";
// $mail->CharSet = "UTF-8";

// $mail->From     = 'isaac.pacheco@lexgotour.com'; //Nuesto correo de la empresa
// $mail->FromName = $datosReserva["nombreResp"][0]." ".$datosReserva["apeResp"][0]; //Nombre del cliente al que se le enviara el archivo
// $mail->Subject  = 'Check In del Hotel';
// $mail->Body     = 'Este archivo te sera util para el check in en el hotel';
// $mail->AddAddress($datosReserva["correo"][0]); //correo a donde se enviara el pdf (correo del cliente)
 
// // definiendo el adjunto 
// $mail->AddStringAttachment($contenido, 'doc.pdf', 'base64', 'application/pdf');
// // enviando
// $mail->Send();
=======
=======
<<<<<<< HEAD
>>>>>>> ccbc722c90cea4f4a52a816a302a834229f5c447
=======
<<<<<<< HEAD
>>>>>>> isaac
=======
>>>>>>> ccbc722c90cea4f4a52a816a302a834229f5c447
>>>>>>> pablo
<<<<<<< HEAD
>>>>>>> origin/david
=======
>>>>>>> david
>>>>>>> jose
if($condicion == '1'){
    $dompdf->stream();
    $contenido = $dompdf->output();
}else if($condicion == '2'){
    $contenido = $dompdf->output();
    require("../scripts/phpmailer/class.phpmailer.php");
 
=======
if($condicion == '1'){
    $dompdf->stream();
    $contenido = $dompdf->output();
}else if($condicion == '2'){
    $contenido = $dompdf->output();
    require("../scripts/phpmailer/class.phpmailer.php");
 
>>>>>>> jose
    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->SMTPSecure = 'tls';
    $mail->SMTPDebug  = 1;
    $mail->SMTPAuth   = true;
    $mail->Host       = "mail.lexgotour.com";
    $mail->Port       = 587;
    $mail->Username   = "isaac.pacheco@lexgotour.com";
    $mail->Password   = "m3uV88g?";
    $mail->CharSet = "UTF-8";

    $mail->From     = 'isaac.pacheco@lexgotour.com'; //Nuesto correo de la empresa
    $mail->FromName = $datosReserva["nombreResp"][0]." ".$datosReserva["apeResp"][0]; //Nombre del cliente al que se le enviara el archivo
    $mail->Subject  = 'Check In del Hotel';
    $mail->Body     = 'Este archivo te sera util para el check in en el hotel';
    $mail->AddAddress('isaacpacheco.go@gmail.com'); //correo a donde se enviara el pdf (correo del cliente)-------$datosReserva["correo"][0]
    
    // definiendo el adjunto 
    $mail->AddStringAttachment($contenido, 'doc.pdf', 'base64', 'application/pdf');
    // enviando
    $mail->Send();
}
<<<<<<< HEAD
<<<<<<< HEAD
?>
<<<<<<< HEAD
>>>>>>> 06740aa5b8cfe7fed80aa2d1510f711cc49105f7
=======
?>
>>>>>>> ccbc722c90cea4f4a52a816a302a834229f5c447
=======
>>>>>>> isaac
=======
<<<<<<< HEAD
?>
>>>>>>> b500b359216b73f223306a4892ff6fd736744328
=======
?>
>>>>>>> ccbc722c90cea4f4a52a816a302a834229f5c447
>>>>>>> pablo
<<<<<<< HEAD
>>>>>>> origin/david
=======
>>>>>>> david
>>>>>>> jose
=======
?>
>>>>>>> jose
