<?php

// Constantes de entorno
include_once("./App/Classes/envVariables.php");

// Autoload
require "./vendor/autoload.php";

use MVC\Core\App;

use Classes\controladorMySql;

use Controlador\controladorBooking;
use Controlador\controladorHotel;
use Controlador\controladorRanking;
use Controlador\controladorSesion;
use Controlador\controladorOpenPay;

$sql = controladorMySql::getInstance();
$app = new App();

$app->router->get    ('/admin/privado/api/v1/pruebas',                          [controladorHotel::class,   'pruebas'                   ]);
$app->router->get    ('/admin/privado/api/v1/hotel',                            [controladorHotel::class,   'leerHoteles'               ]);
$app->router->get    ('/admin/privado/api/v1/hotel/habitacion',                 [controladorHotel::class,   'leerHabitaciones'          ]);
$app->router->get    ('/admin/privado/api/v1/hotel/habitacion/facilidad',       [controladorHotel::class,   'facilidadesHabitacion'     ]);
$app->router->get    ('/admin/privado/api/v1/hotel/detalle',                    [controladorHotel::class,   'informacionCompleta'       ]);
$app->router->get    ('/admin/privado/api/v1/hotel/detallesReservacion',        [controladorHotel::class,   'getBooking'                ]);
$app->router->get    ('/admin/privado/api/v1/hotel/detallesHotel',              [controladorHotel::class,   'getBookingHotel'           ]);
$app->router->get    ('/admin/privado/api/v1/hotel/detallesHabitacion',         [controladorHotel::class,   'getDetallesHabitacion'     ]);
$app->router->get    ('/admin/privado/api/v1/hotel/imagen',                     [controladorHotel::class,   'imagenes'                  ]);
$app->router->get    ('/admin/privado/api/v1/hotel/punto-de-interes',           [controladorHotel::class,   'puntosInteres'             ]);
$app->router->get    ('/admin/privado/api/v1/hotel/facilidad',                  [controladorHotel::class,   'hotelFacilidades'          ]);
$app->router->get    ('/admin/privado/api/v1/hotel/coordenada',                 [controladorHotel::class,   'leerCoordenadas'           ]);
$app->router->get    ('/admin/privado/api/v1/hotel/servicio',                   [controladorHotel::class,   'leerServicios'             ]);
$app->router->get    ('/admin/privado/api/v1/hotel/destino',                    [controladorHotel::class,   'leerDestinos'              ]);
$app->router->get    ('/admin/privado/api/v1/hotel/destino/coordenada',         [controladorHotel::class,   'coordenadaDestino'         ]);
$app->router->get    ('/admin/privado/api/v1/facilidad',                        [controladorHotel::class,   'facilidades'               ]);
$app->router->get    ('/admin/privado/api/v1/facilidad/tipologia',              [controladorHotel::class,   'facilidadesTipologias'     ]);
$app->router->get    ('/admin/privado/api/v1/cadena',                           [controladorHotel::class,   'leerCadenas'               ]);
$app->router->get    ('/admin/privado/api/v1/sesion',                           [controladorSesion::class,  'leerDatos'                 ]);
$app->router->get    ('/admin/privado/api/v1/sesion/destruir',                  [controladorSesion::class,  'destruir'                  ]);
$app->router->get    ('/admin/privado/api/v1/ranking',                          [controladorRanking::class, 'topCuatro'                 ]);
$app->router->get    ('/admin/privado/api/v1/booking',                          [controladorBooking::class, 'listarReservaciones'       ]);
$app->router->get    ('/admin/privado/api/v1/booking/detalle/([-0-9A-Z]+)',     [controladorBooking::class, 'detalleReservacion'        ]);
$app->router->get    ('/admin/privado/api/v1/booking/pdf/([-0-9A-Z]+)',         [controladorBooking::class, 'descargarReservacion'      ]);

$app->router->post   ('/admin/privado/api/v1/sesion',                           [controladorSesion::class,  'guardarJson'               ]);
$app->router->post   ('/admin/privado/api/v1/booking',                          [controladorBooking::class, 'hacerReservacion'          ]);

$app->router->put    ('/admin/privado/api/v1/booking/([-0-9]+)',                [controladorBooking::class, 'modificarReservacion'      ]);

$app->router->delete ('/admin/privado/api/v1/booking/([-0-9A-Z]+)',             [controladorBooking::class, 'cancelarReservacion'       ]);
$app->router->post ('/procesando-pago',                                       [controladorOpenPay::class, 'procesarPago'       ]);

$app->iniciar();
