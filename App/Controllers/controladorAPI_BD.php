<?php
namespace Controlador;

use Classes\mysqlconsultas;

class controladorAPI_BD{
    public function DescripcionIMG($code){
        $qry = "SELECT thd.description, timg.path FROM T_Hotel_Description thd
                LEFT JOIN T_Img_Hotel_Room timg ON timg.id_hotel_description = thd.id 
                WHERE `code` = '$code' ORDER BY thd.id LIMIT 1";
        $sql = new mysqlconsultas();
        $res = $sql->consulta($qry);
        return $res;
    }

    public function Estrellas($cat,$codeDestination){
        $qry = "SELECT thd.id, thd.description, timg.path, thd.categoryCode FROM T_Hotel_Description thd
                LEFT JOIN T_Img_Hotel_Room timg ON timg.id_hotel_description = thd.id 
                WHERE thd.categoryCode = '$cat' and thd.destinationCode = '$codeDestination' GROUP BY thd.id";
        $sql = new mysqlconsultas();
        $res = $sql->consulta($qry);
        return $res;
    }

    public function Destinos(){
        $qry = "SELECT * FROM T_Destinations";
        $sql = new mysqlconsultas();
        $res = $sql->consulta($qry);
        return $res;
    }

    public function DestinationCoordenadas($stateCode){
        $qry = "SELECT THC.longitude, THC.latitude FROM T_Destinations TD
                LEFT JOIN T_Hotel_Description THD ON TD.code = THD.destinationCode
                LEFT JOIN T_Hotel_Coordinates THC ON THC.id_hotel_description = THD.id 
                WHERE TD.`code` = '$stateCode' LIMIT 1";
        $sql = new mysqlconsultas();
        $res = $sql->consulta($qry);
        $json = json_encode($res);
        return $json;
    }

    public function getHotelReserva($idHotel){
        $qry = "SELECT thc.latitude, thc.longitude, thd.`name`, thi.path FROM T_Hotel_Description thd
                JOIN T_Img_Hotel_Room thi ON thi.id_hotel_description = thd.id
                JOIN T_Hotel_Coordinates thc ON thc.id_hotel_description = thd.id
                WHERE thd.`code` = {$idHotel} GROUP BY thd.id";
        $sql = new mysqlconsultas();
        $res = $sql->consulta($qry);
        return $res;
    }
}


?>