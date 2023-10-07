<?php

namespace Controlador;

use Classes\controladorMySql;
use Classes\typesHotel;
use Controlador\controladorBooking;
use Openpay\Data\Openpay;
use Openpay\Data\OpenpayApiTransactionError;

class controladorOpenPay
{
    private $openpay;
    public static controladorMySql $sql;

    public function __construct()
    {
        Openpay::setProductionMode(false);
        Openpay::setId(OPENPAY_ID);
        Openpay::setApiKey(OPENPAY_PRIVATE_KEY);

        $this->openpay = Openpay::getInstance();
    }

    public function procesarPago()
    {
        $customerData = array(
            'name'              => base64_decode($_POST["titular-nombre"]),
            'last_name'         => base64_decode($_POST["titular-apellidos"]),
            'email'             => base64_decode($_POST["titular-email"]),
            'requires_account'  => false,
            'phone_number'      => base64_decode($_POST["titular-telefono"])
        );

        $orderId = "LGT-00";
        $orderId .= time();
        $redirectUrl = ($_SERVER['HTTP_HOST'] === 'localhost') ? 'http://localhost/lexgotravel/compra-exitosa' : 'https://lexgotravel.com/compra-exitosa';

        $precioTotal = filter_input(INPUT_POST, "precio-total", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $chargeRequest = array(
            'method'            => 'card',
            'source_id'         => $_POST["token_id"],
            'amount'            => number_format($precioTotal, 2, '.', ''),
            'currency'          => 'MXN',
            'description'       => 'Cargo con precio generado automáticamente',
            'order_id'          => $orderId,
            'device_session_id' => $_POST["device_session_id"],
            'customer'          => $customerData,
            'use_3d_secure'     => true,
            'redirect_url'      => $redirectUrl
        );

        try {
            $charge = $this->openpay->charges->create($chargeRequest);

            $type = new typesHotel();
            $controladorBooking = new controladorBooking();

            $txtReservacion = json_encode($_SESSION['datosReserva']);
            $response       = $controladorBooking->hacerReservacion($txtReservacion);

            $idcliente      = $_SESSION['datoCliente']['idCliente'];
            $respuestasql   = $type->BookingConfirmation($response, $idcliente);

        
            http_response_code(303);
            header('Location: '.$charge->payment_method->url);
            die();
        } catch (\Exception $e) {
            var_dump($e);
        }
    }
}