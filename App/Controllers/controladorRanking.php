<?php
namespace Controlador;

use Classes\controladorMySql;
use Classes\funciones;

class controladorRanking {
    public function topCuatro() {
        $fn     = new funciones();
        $sql    = controladorMySql::getInstance();

        $code   = $_GET["code"];

        $qry            = "SELECT 	`T_Hotel_Description`.`id`, `T_Hotel_Description`.`code`, `T_Hotel_Description`.`name`, `T_Hotel_Description`.`categoryCode`
                           FROM     `T_Hotel_Description`
                           
                           WHERE `T_Hotel_Description`.`countryCode` = '$code' AND `T_Hotel_Description`.`accommodationTypeCode` = 'HOTEL' 
                                                                       
                           GROUP BY `T_Hotel_Description`.`name`
                           ORDER BY `T_Hotel_Description`.`ranking`, `T_Hotel_Description`.`id` LIMIT 4";

        $qryCoordinates = "SELECT 	`T_Hotel_Coordinates`.`latitude`, `T_Hotel_Coordinates`.`longitude`
                           FROM     `T_Hotel_Coordinates`
                            
                           WHERE `T_Hotel_Coordinates`.`id_hotel_description` IN (?, ?, ?, ?)
                           ORDER BY `T_Hotel_Coordinates`.`id_hotel_description`";

        $qryCity        = "SELECT 	`T_Hotel_Address`.`city`
                           FROM     `T_Hotel_Address`
                            
                           WHERE `T_Hotel_Address`.`id_hotel_description` IN (?, ?, ?, ?)
                           ORDER BY `T_Hotel_Address`.`id_hotel_description`";

        $qryImg         = "SELECT concat_ws('','https://photos.hotelbeds.com/giata/bigger/', `T_Img_Hotel_Room`.`path`) as `imgweb`
                           FROM   `T_Img_Hotel_Room`
                                                   
                           WHERE `T_Img_Hotel_Room`.`id_hotel_description` IN (?, ?, ?, ?)
                           GROUP BY `T_Img_Hotel_Room`.`id_hotel_description`
                           ORDER BY `T_Img_Hotel_Room`.`id_hotel_description`";

        $res            = $sql->consulta($qry);
        $resCoordinates = $sql->consulta($fn->prepararConsulta($qryCoordinates, $res['id']));        
        $resCity        = $sql->consulta($fn->prepararConsulta($qryCity,        $res['id']));
        $resImg         = $sql->consulta($fn->prepararConsulta($qryImg,         $res['id']));

        
        $resultado = array_merge($res, $resCoordinates, $resCity, $resImg);
        
        header('Content-type: application/json');
        echo json_encode($resultado);
    }
}