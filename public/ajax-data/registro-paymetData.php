<?php
session_start();

include_once "vendor/autoload.php";

use Classes\typesHotel;
use Controlador\controladorBooking;

$type = new typesHotel();
$controladorBooking = new controladorBooking();

// $titular                = filter_input(INPUT_POST, 'nombreTitular', FILTER_SANITIZE_STRING);
// $tipo                   = filter_input(INPUT_POST, 'tipTarjeta', FILTER_SANITIZE_STRING);
// $cardNumber             = filter_input(INPUT_POST, 'NumTarjeta', FILTER_SANITIZE_STRING);
// $mesExp                 = filter_input(INPUT_POST, 'mesExp', FILTER_SANITIZE_STRING);
// $anioExp                = filter_input(INPUT_POST, 'anioExp', FILTER_SANITIZE_STRING);
// $cvc                    = filter_input(INPUT_POST, 'cvc', FILTER_SANITIZE_STRING);

// $cv = $mesExp.$anioExp;

// $paymetdata = array("paymentCard" => 
//                         array("cardHolderName" => $titular,
//                               "cardType" => $tipo, 
//                               "cardNumber" => $cardNumber, 
//                               "expiryDate" => $cv, 
//                               "cardCVC" => $cvc),
//                     "contactData" => 
//                         array("email" => $_SESSION['datoCliente']['correo'], 
//                               "phoneNumber" => $_SESSION['datoCliente']['telefono']));


// $_SESSION['datosReserva']['paymentData'] = $paymetdata;


$txtReservacion = json_encode($_SESSION['datosReserva']);
$response       = $controladorBooking->hacerReservacion($txtReservacion);

// echo $response;
// die;

$idcliente      = $_SESSION['datoCliente']['idCliente'];
$respuestasql   = $type->BookingConfirmation($response, $idcliente);

echo $respuestasql;
