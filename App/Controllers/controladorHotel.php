<?php

namespace Controlador;

use Classes\controladorMySql;
use Classes\funciones;
use Modelo\T_Hotel_Description;

class controladorHotel
{
    public static controladorMySql $sql;

    public function __construct()
    {
        self::$sql = controladorMySql::getInstance();
    }
    public function leerHoteles($code = null)
    {
        $return = true;

        if (!$code) {
            $code   = $_GET["code"];
            $return = false;
        }

        $fn     = new funciones();

        if (strpos($code, ",")) {
            $code = explode(",", $code);

            //////////////////////////////////////
            // Consulta para los hoteles
            $qry = "SELECT thd.id, thd.code, thd.description FROM T_Hotel_Description thd
                
                    WHERE `code` IN (?";

            for ($i = 0, $l = count($code) - 1; $i < $l; $i++) {
                $qry .= ",?";
            }

            $qry .= ") ORDER BY thd.id";

            $res        = self::$sql->consulta($fn->prepararConsulta($qry, $code));

            //////////////////////////////////////
            // Consulta para las imagenes
            $qryImg = "SELECT timg.path FROM T_Img_Hotel_Room timg
                                    
                    WHERE `id_hotel_description` IN (?";

            for ($i = 0, $l = count($res["id"]) - 1; $i < $l; $i++) {
                $qryImg .= ",?";
            }

            $qryImg .= ") GROUP BY timg.id_hotel_description 
            ORDER BY timg.id_hotel_description";

            $resImg     = self::$sql->consulta($fn->prepararConsulta($qryImg, $res["id"]));
        } else {
            $qry = "SELECT thd.id, thd.code, thd.description FROM T_Hotel_Description thd
                
                    WHERE `code` = ? ORDER BY thd.id LIMIT 1";

            $qryImg = "SELECT timg.path FROM T_Img_Hotel_Room timg
                                    
                    WHERE `id_hotel_description` = ? LIMIT 1";

            $res        = self::$sql->consulta($fn->prepararConsulta($qry, [$code]));
            $resImg     = self::$sql->consulta($fn->prepararConsulta($qryImg, [$res["id"][0]]));
        }

        if (!$return) {
            header('Content-type: application/json');
            echo json_encode(array_merge($res, $resImg));

            return;
        }

        return json_encode(array_merge($res, $resImg));
    }

    public static function informacionCompleta($code = null)
    {
        $return = true;

        if (!$code) {
            $code = $_GET["code"];
            $return = false;
        }

        $qry = "SELECT `T_Hotel_Description`.`id`, `T_Hotel_Description`.`name`, `T_Hotel_Description`.`description`, `T_Hotel_Description`.`categoryCode`, 
                        `T_Hotel_Description`.`accommodationTypeCode`, `T_Hotel_Address`.`city`, `T_Hotel_Address`.`content`, `T_Hotel_Address`.`postalCode`,
                        `T_Hotel_Coordinates`.`latitude`, `T_Hotel_Coordinates`.`longitude`, `thi`.`path`, `T_Hotel_Description`.`chainCode`, `T_Hotel_Description`.`S2C`

                FROM T_Hotel_Description

                JOIN `T_Hotel_Address` 	    ON `T_Hotel_Description`.`id` = `T_Hotel_Address`.`id_hotel_description`
                JOIN `T_Img_Hotel_Room` thi   ON `thi`.`id_hotel_description` = `T_Hotel_Description`.`id`
                JOIN `T_Hotel_Coordinates` 	ON `T_Hotel_Description`.`id` = `T_Hotel_Coordinates`.`id_hotel_description`

                WHERE `code` = ? limit 1";

        $fn = new funciones();
        $res = self::$sql->consulta($fn->prepararConsulta($qry, [$code]));

        if (!$return) {
            header('Content-type: application/json');
            echo json_encode($res);

            return;
        }

        return json_encode($res);
    }

    public function leerHabitaciones($code = null)
    {
        $return = true;

        if (!$code) {
            $code = $_GET["code"];
            $return = false;
        }

        $qry = "SELECT  `T_Hotel_Rooms`.`id`, `T_Rooms`.`description` AS `name`, `T_Hotel_Rooms`.`roomCode`, `T_Hotel_Rooms`.`isParentRoom`,
                        `T_Hotel_Rooms`.`minPax`, `T_Hotel_Rooms`.`maxPax`, `T_Hotel_Rooms`.`maxAdults`, `T_Hotel_Rooms`.`maxChildren`,
                        `T_Hotel_Rooms`.`minAdults`, `T_Hotel_Rooms`.`roomType`, `T_Hotel_Rooms`.`characteristicCode`

                FROM `T_Hotel_Rooms`

                LEFT JOIN `T_Rooms` ON `T_Hotel_Rooms`.`roomCode` = `T_Rooms`.`code`
                WHERE `T_Hotel_Rooms`.`id_hotel_description` = ?";

        $res = self::$sql->consulta(funciones::prepararConsulta($qry, [$code]));

        if (!$return) {
            header('Content-type: application/json');
            echo json_encode($res);
            
            return;
        }

        return json_encode($res);
    }

    public function facilidadesHabitacion($code = null)
    {
        $return = true;

        if (!$code) {
            $code = $_GET["code"];
            $return = false;
        }

        $qry = "SELECT `T_Room_Facilities`.`id`, `T_Room_Facilities`.`facilityCode`, `T_Room_Facilities`.`facilityGroupCode`, `T_Room_Facilities`.`indLogic`,
                        `T_Room_Facilities`.`number`, `T_Room_Facilities`.`voucher`

                FROM `T_Room_Facilities`
                WHERE `T_Room_Facilities`.`id_hotel_room` = ?";

        $res = self::$sql->consulta(funciones::prepararConsulta($qry, [$code]));

        if (!$return) {
            header('Content-type: application/json');
            echo json_encode($res);

            return;
        }

        return json_encode($res);
    }

    public function leerCoordenadas()
    {
        $code = $_GET["code"];

        $qry = "SELECT `T_Hotel_Coordinates`.`latitude`, `T_Hotel_Coordinates`.`longitude`, `T_Hotel_Coordinates`.`id_hotel_description`
                
                FROM  `T_Hotel_Coordinates`
                
                WHERE `T_Hotel_Coordinates`.`id_hotel_description` = ?";

        $fn = new funciones();
        $res = self::$sql->consulta($fn->prepararConsulta($qry, [$code]));

        header('Content-type: application/json');
        echo json_encode($res);
    }

    public function imagenes($code = null)
    {
        $return = true;

        if(!$code) {
            $code = $_GET["code"];
            $return = false;
        }

        $qry = "SELECT `T_Img_Hotel_Room`.`imageTypeCode`, `T_Img_Hotel_Room`.`path`, `T_Img_Hotel_Room`.`order`, `T_Img_Hotel_Room`.`roomCode`, `T_Img_Hotel_Room`.`roomType`
                
                FROM T_Hotel_Description
                JOIN T_Img_Hotel_Room ON `T_Hotel_Description`.`id` = `T_Img_Hotel_Room`.`id_hotel_description`
                
                WHERE `T_Hotel_Description`.`code` = ?";

        $fn     = new funciones();
        $res    = self::$sql->consulta($fn->prepararConsulta($qry, [$code]));

        if (!$return) {
            header('Content-type: application/json');
            echo json_encode($res);

            return;
        }

        return json_encode($res);
    }

    public function leerDestinos()
    {
        $qry = "SELECT `T_Destinations`.`id`, `T_Destinations`.`code`, `T_Destinations`.`name`, `T_Destinations`.`countryCode`
                
                FROM `T_Destinations`";

        $res = self::$sql->consulta($qry);

        header('Content-type: application/json');
        echo json_encode($res);
    }

    public function puntosInteres($code = null)
    {
        $return = true;

        if (!$code) {
            $code = $_GET["code"];
        }

        $qry = "SELECT `T_Hotel_Description`.`code`, `T_Hotel_Description`.`name`, `T_InterestPoint_Hotel`.`facilityCode`, `T_InterestPoint_Hotel`.`facilityGroupCode`, `T_InterestPoint_Hotel`.`poiName`
                
                FROM `T_Hotel_Description`
                LEFT JOIN `T_InterestPoint_Hotel` ON `T_Hotel_Description`.`id` 	= `T_InterestPoint_Hotel`.`id_hotel_description`
                
                WHERE `T_Hotel_Description`.`code` = ?";

        $fn = new funciones();
        $res = self::$sql->consulta($fn->prepararConsulta($qry, [$code]));

        if(!$return) {
            header('Content-type: application/json');
            echo json_encode($res);

            return;
        }

        return json_encode($res);
    }

    public function hotelFacilidades($code = null, $columns = null)
    {
        $fn = new funciones();

        $return = true;

        if ( (!$code) && (!$columns) ) {
            $return = false;
        }

        if ((!$code) && (isset($_GET["code"]))) {
            $code = $_GET["code"];
        }

        if ((!$columns) && (isset($_GET["columns"]))) {
            $columns = $_GET["columns"];
        } else {
            if ((!$columns)) {
                $columns = [
                    'T_Facilities_Hotel_Description.facilityCode', 'T_Facilities_Hotel_Description.facilityGroupCode', 'T_Facilities_Hotel_Description.order',
                    'T_Facilities_Hotel_Description.indYesOrNo', 'T_Facilities_Hotel_Description.number', 'T_Facilities_Hotel_Description.voucher',
                    'T_Facilities_Hotel_Description.indLogic', 'T_Facilities_Hotel_Description.indFee', 'T_Facilities_Hotel_Description.distance',
                    'T_Facilities_Hotel_Description.timeFrom', 'T_Facilities_Hotel_Description.timeTo', 'T_Facilities_Hotel_Description.dateTo'
                ];
    
                $columns = implode(",", $columns);
            }
        }
        
        if (strpos($code, ",")) {
            $code = explode(",", $code);

            //////////////////////////////////////
            // Consulta para las facilidades
            $qry = "SELECT $columns

                    FROM T_Facilities_Hotel_Description 
                    
                    WHERE `T_Facilities_Hotel_Description`.`id_hotel_description` IN (?";


            for ($i = 0, $l = count($code) - 1; $i < $l; $i++) {
                $qry .= ",?";
            }

            $qry .= ")";

            $res        = self::$sql->consulta($fn->prepararConsulta($qry, $code));
        } else {
            $qry = "SELECT $columns

                    FROM T_Facilities_Hotel_Description 
                    
                    WHERE `T_Facilities_Hotel_Description`.`id_hotel_description` = ?";

            $res = self::$sql->consulta($fn->prepararConsulta($qry, [$code]));
        }

        if (!$return) {
            header('Content-type: application/json');
            echo json_encode($res);

            return;
        }
        
        return json_encode($res);
    }

    public function facilidades($return = null)
    {
        $qry = "SELECT * FROM `T_Facilities`";

        $res = self::$sql->consulta($qry);

        if (!$return) {
            header('Content-type: application/json');
            echo json_encode($res);

            return;
        }

        return json_encode($res);
    }

    public function facilidadesTipologias($return = null)
    {
        $qry = "SELECT `T_Facilities_Typologies`.`code`, `T_Facilities_Typologies`.`numberFlag`, `T_Facilities_Typologies`.`logicFlag`, `T_Facilities_Typologies`.`distanceFlag`,
                       `T_Facilities_Typologies`.`dateFromFlag`, `T_Facilities_Typologies`.`dateToFlag`

                FROM `T_Facilities_Typologies`";

        $res = self::$sql->consulta($qry);

        if (!$return) {
            header('Content-type: application/json');
            echo json_encode($res);

            return;
        }

        return json_encode($res);
    }

    public function coordenadaDestino()
    {
        $stateCode = $_GET["stateCode"];


        $qry = "SELECT THC.longitude, THC.latitude
        
                FROM T_Destinations TD
                
                LEFT JOIN T_Hotel_Description THD ON TD.code = THD.destinationCode
                LEFT JOIN T_Hotel_Coordinates THC ON THC.id_hotel_description = THD.id 
                
                WHERE TD.`code` = '$stateCode' LIMIT 1";

        $res = self::$sql->consulta($qry);
        $json = json_encode($res);

        http_response_code(200);
        header('Content-type: application/json');
        echo $json;
    }

    public function leerServicios($idHotelBDD = null)
    {
        $return = true;

        if (!$idHotelBDD) {
            $idHotelBDD = $_GET["code"];
            $return = false;
        }

        $qry = "SELECT `T_Boards`.`description`

                FROM `T_Hotel_Boards`
                LEFT JOIN `T_Boards` ON `T_Hotel_Boards`.`code` = `T_Boards`.`code`
                
                WHERE `T_Hotel_Boards`.`id_hotel_description` = ?";

        $fn = new funciones();
        $res = self::$sql->consulta($fn->prepararConsulta($qry, [$idHotelBDD]));

        if (!$return) {
            header('Content-type: application/json');
            echo json_encode($res);

            return;
        }

        return json_encode($res);
    }

    public function leerCadenas($columns = null, $where = null)
    {
        $return = true;

        if ( (!$where) && (!$columns) ) {
            $return = false;
        }

        if (!$columns) {
            if (isset($_GET["columns"])) {
                $columns = $_GET["columns"];
            } else {
                $columns = ['T_Chains.code', 'T_Chains.description'];
                $columns = implode(",", $columns);
            }
        }

        $qry = "SELECT $columns FROM `T_Chains`";

        if(!$where) {
            if (isset($_GET["where"])) {
                $getWhere = explode(',', $_GET["where"]);
                $qry .= "WHERE {$getWhere[0]} = '{$getWhere[1]}'";
            }
        }

        $res = self::$sql->consulta($qry);

        if (!$return) {
            header('Content-type: application/json');
            echo json_encode($res);

            return;
        }

        return json_encode($res);
    }

    public function getBooking()
    {
        $codigo = $_GET['codigo'];
        $qry = "SELECT u.telefono, u.correo, b.*
                FROM T_Booking b
                JOIN T_Booking_Client u ON u.id = b.id_cliente
                WHERE b.reference = '$codigo' OR b.Folio_LGT = '$codigo'";
        $res = self::$sql->consulta($qry);
        $json = json_encode($res);

        header('Content-type: application/json');
        echo $json;
    }

    public function getBookingHotel()
    {
        $idBooking = $_GET['idbooking'];

        $qry = "SELECT * FROM T_Booking_Hotel WHERE id_booking = '$idBooking'";
        $res = self::$sql->consulta($qry);
        $json = json_encode($res);

        header('Content-type: application/json');
        echo $json;
    }

    public function getDetallesHabitacion()
    {
        $idHotel = $_GET['idHotel'];
        $qry = "SELECT tr.*, tra.boardName, tp.`name` as nombre, tp.surname as apellido, tp.type, tc.amount, tc.from FROM T_Booking_H_Rooms tr
                JOIN T_Booking_H_R_Paxes tp ON tp.id_bookinh_h_room = tr.id
                JOIN T_Booking_H_R_Rates tra ON tra.id_booking_h_room= tr.id
                JOIN T_Booking_PolCancel tc ON tc.id_rate = tra.id
                WHERE tr.id_booking_hotel = '$idHotel'";
        $res = self::$sql->consulta($qry);
        $json = json_encode($res);

        header('Content-type: application/json');
        echo $json;
    }

    public function pruebas()
    {
        $code = $_GET['code'];

        if (strpos($code, ',')) {
            $code = explode(',', $code);
        }

        T_Hotel_Description::establecerDB(controladorMySql::getInstance());

        $test = new T_Hotel_Description;
        $res = $test->select(['id', 'code', 'name', 'description'])->whereIn("code", [$code])->orderBy('id')->ejecutarSQL();

        header('Content-type: application/json');
        echo json_encode($res);
    }
}
