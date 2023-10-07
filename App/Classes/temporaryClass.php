<?php

namespace Classes;

include_once 'envVariables.php';

use Classes\funciones;

class temporaryClass {

    // Declaramos una variable para conexión
    
    public $conexion;
    
    // Declaramos el constructor de la clase
    
    public function __construct($host = HOST, $usuario = USUARIO, $clave = PASSWORD, $db = BD){
        $this->conexion = new \mysqli($host, $usuario, $clave, $db);
    }
    
    // Funcion para crear tablas
    
    public function creartabla($sql){
        if ($this->conexion->query($sql) === TRUE) {
            // echo "Se ha creado una tabla";
        } else {
            echo "Fallo:no se ha creado la tabla ".$this->conexion->error;
        }
              
    }
    
    //Guardar nuevos datos en la base de datos
    public function insertar($qry){
        //Insertamos los valores en cada campo
        if($this->conexion->query($qry) === TRUE){
            // echo "Nuevo cliente insertado";
            $registro = mysqli_insert_id($this->conexion);
            return $registro;
        } else {
            echo "Fallo no se ha insertado el cliente ".$this->conexion->error;
        }
    }
    
    //Borrar datos  de la base de datos
    public function borrar($tabla, $camposdatos){
        $i=0;

        foreach($camposdatos as $indice=>$valor) {
        $dato[$i] = "'".$valor."'";

            $i++;
        }

        $campoydato = implode(" AND ",$dato);

        if($this->conexion->query("DELETE FROM $tabla WHERE $campoydato") === TRUE){

            if(mysqli_affected_rows($this->conexion)){
                // echo "Registro eliminado";

            } else{
                echo "Fallo no se pudo eliminar el registro" . $this->conexion->error;

            }
        }
    }
    
    public function Actualizar($tabla, $camposset, $camposcondicion){
        //separamos los valores SET a modificar
        $i=0;
        foreach($camposset as $indice=>$dato) {
            $datoset[$i] = $indice." = '".$dato."'";
            $i++;
        }

        $consultaset = implode(", ",$datoset);

        $i=0;
        foreach($camposcondicion as $indice=>$datocondicion) {
        $condicion[$i] = $indice." = '".$datocondicion."'";
            $i++;
        }

        $consultacondicion = implode(" AND ",$condicion);

        //Actualización de registros
        if($this->conexion->query("UPDATE $tabla SET $consultaset WHERE $consultacondicion") === TRUE) {
            if(mysqli_affected_rows($this->conexion)){
                // echo "Registro actualizado";
            } else {
                echo "Fallo no se pudo eliminar el registro".$this->conexion->error;
            }
        }
    }
    
    // funcion Buscar en una tabla
    
    public function buscar($query){
        $conn = $this->conexion;        
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

        $number_cols = mysqli_num_fields($result);
        $number_filas = mysqli_num_rows($result);

        if ($number_filas >= 1) {
            for ($i = 0; $i < $number_cols; ++$i) {
                $cols[$i] = mysqli_fetch_field_direct($result, $i)->name;
            }

            $c1 = 0;
            $c2 = 0;

            while ($row = mysqli_fetch_row($result)) {
                foreach ($row as $field) {
                    $array[$cols[$c2]][$c1] = (is_null($field) ? '' : $field);
                    $c2++;
                }
                $c2 = 0;
                $c1++;
            }
            
            return $array;
        }
    }

    public function CreateTemporaryTable($ResponseAPI, $codigoTabla){
        $fn = new funciones();
        $json_decode = $ResponseAPI;
        
        //Se crea tabla Detalles de hotel
        $qryTemporaryHotelDetails = "CREATE TEMPORARY TABLE Temporary_Hotel_Details".$codigoTabla." (
            `id` int NOT NULL AUTO_INCREMENT,
            `code` int NULL,
            `name` varchar(255) NULL,
            `categoryCode` varchar(255) NULL,
            `categoryName` varchar(255) NULL,
            `destinationCode` varchar(255) NULL,
            `destinationName` varchar(255) NULL,
            `zoneCode` varchar(255) NULL,
            `zoneName` varchar(255) NULL,
            `latitude` varchar(255) NULL,
            `longitude` varchar(255) NULL,
            `minRate` decimal(8,2) NULL,
            `maxRate` decimal(8,2) NULL,
            `currency` varchar(255) NULL,
            PRIMARY KEY (`id`)
          )";
    
          $this->creartabla($qryTemporaryHotelDetails);
        
        //Se crea tabla Detalles de rooms
        $qryTemporary_Hotel_Details_Rooms = "CREATE TEMPORARY TABLE Temporary_Hotel_Details_Rooms".$codigoTabla." (
            `id` int NOT NULL AUTO_INCREMENT,
            `code` varchar(255) NULL,
            `name` varchar(255) NULL,
            `id_temp_hotel_details` int NULL,
            PRIMARY KEY (`id`)
          )";
    
          $this->creartabla($qryTemporary_Hotel_Details_Rooms);
    
        //Se crea tabla Detalles de rates
        $qryTemporary_Hotel_Rooms_Rates = "CREATE TEMPORARY TABLE Temporary_Hotel_Rooms_Rates".$codigoTabla." (
            `id` int NOT NULL AUTO_INCREMENT,
            `rateKey` varchar(255) NULL,
            `rateClass` varchar(255) NULL,
            `rateType` varchar(255) NULL,
            `net` varchar(255) NULL,
            `allotment` int NULL,
            `paymentType` varchar(255) NULL,
            `packaging` varchar(255) NULL,
            `boardCode` varchar(255) NULL,
            `boardName` varchar(255) NULL,
            `rooms` int NULL,
            `adults` int NULL,
            `children` int NULL,
            `childrenAges` int NULL,
            `id_hotel_rooms` int NULL,
            PRIMARY KEY (`id`)
          )";
    
          $this->creartabla($qryTemporary_Hotel_Rooms_Rates);
    
        //Se crea tabla Detalles de cancelacion
        $qryTemporary_Hotel_Rooms_Rates_CancellationPolicies = "CREATE TEMPORARY TABLE Temporary_CancellationPolicies".$codigoTabla." (
            `id` int NOT NULL AUTO_INCREMENT,
            `amount` varchar(255) NULL,
            `from` varchar(255) NULL,
            `id_hotel_room_rates` int NULL,
            PRIMARY KEY (`id`)
          )";
    
        $this->creartabla($qryTemporary_Hotel_Rooms_Rates_CancellationPolicies);
    
        //Se crea tabla Detalles de offers rates
        $qryTemporary_Offers_Rates = "CREATE TEMPORARY TABLE Temporary_Offers_Rates".$codigoTabla." (
            `id` int NOT NULL AUTO_INCREMENT,
            `code` varchar(255) NULL,
            `name` varchar(255) NULL,
            `amount` varchar(255) NULL,
            `id_hotel_rooms_rates` int NULL,
            PRIMARY KEY (`id`)
          )";
    
        $this->creartabla($qryTemporary_Offers_Rates);
    
        //Se crea tabla Detalles de taxes rates
        $qryTemporary_Taxes_Rates = "CREATE TEMPORARY TABLE Temporary_Taxes_Rates".$codigoTabla." (
            `id` int NOT NULL AUTO_INCREMENT,
            `allIncluded` int NULL,
            `id_rates` int NULL,
            PRIMARY KEY (`id`)
          )";
    
        $this->creartabla($qryTemporary_Taxes_Rates);
    
        //Se crea tabla Detalles de taxes
        $qryTemporary_Taxes_Tax = "CREATE TEMPORARY TABLE Temporary_Taxes_Tax".$codigoTabla." (
            `id` int NOT NULL AUTO_INCREMENT,
            `included` int NULL,
            `amount` varchar(255) NULL,
            `currency` varchar(255) NULL,
            `clientAmount` varchar(255) NULL,
            `clientCurrency` varchar(255) NULL,
            `id_taxes_rates` int NULL,
            PRIMARY KEY (`id`)
          )";
    
        $this->creartabla($qryTemporary_Taxes_Tax);
    
        //Se crea tabla Detalles de promotions
        $qryTemporary_Promotions = "CREATE TEMPORARY TABLE Temporary_Promotions".$codigoTabla." (
            `id` int NOT NULL AUTO_INCREMENT,
            `code` varchar(255) NULL,
            `name` varchar(255) NULL,
            `id_hotel_rooms_rates` int NULL,
            PRIMARY KEY (`id`)
          )";
    
        $this->creartabla($qryTemporary_Promotions);
    
        foreach ($json_decode["hotels"]["hotels"] as $hotel) {
            
                $sqlTemporary_Hotel_Details = "INSERT INTO Temporary_Hotel_Details".$codigoTabla." 
                (code,name,categoryCode,categoryName,destinationCode,destinationName,zoneCode,zoneName,latitude,longitude,minRate,maxRate,currency) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    
                $code  =                isset($hotel['code'])            ? $hotel['code']               : "";
                $name  =                isset($hotel['name'])            ? $hotel['name']               : "";
                $categoryCode  =        isset($hotel['categoryCode'])    ? $hotel['categoryCode']       : "";
                $categoryName  =        isset($hotel['categoryName'])    ? $hotel['categoryName']       : "";
                $destinationCode  =     isset($hotel['destinationCode']) ? $hotel['destinationCode']    : "";
                $destinationName  =     isset($hotel['destinationName']) ? $hotel['destinationName']    : "";
                $zoneCode  =            isset($hotel['zoneCode'])        ? $hotel['zoneCode']           : "";
                $zoneName  =            isset($hotel['zoneName'])        ? $hotel['zoneName']           : "";
                $latitude  =            isset($hotel['latitude'])        ? $hotel['latitude']           : "";
                $longitude  =           isset($hotel['longitude'])       ? $hotel['longitude']          : "";
                $minRate  =             isset($hotel['minRate'])         ? $hotel['minRate']            : "";
                $maxRate  =             isset($hotel['maxRate'])         ? $hotel['maxRate']            : "";
                $currency  =            isset($hotel['currency'])        ? $hotel['currency']           : "";
    
                $arreglo = [$code,$name,$categoryCode,$categoryName,$destinationCode,$destinationName,$zoneCode,$zoneName,$latitude,$longitude,$minRate,$maxRate,$currency];
    
                $consult_scape = $fn->prepararConsulta($sqlTemporary_Hotel_Details, $arreglo);
                $resTemporary_Hotel_Details = $this->insertar($consult_scape);

    
                // $CountRooms = count($hotel['rooms']);
                $CountRooms = isset($hotel['rooms']) ? count($hotel['rooms']) : 0;
    
                for($i=0;$i<$CountRooms;$i++){
    
                    $CodeRoom = isset($hotel['rooms'][$i]['code'])  ? $hotel['rooms'][$i]['code']   : "";
                    $NameRoom = isset($hotel['rooms'][$i]['name'])  ? $hotel['rooms'][$i]['name']   : "";
    
                    $sqlTemporary_Hotel_Details_Rooms = "INSERT INTO Temporary_Hotel_Details_Rooms".$codigoTabla." (code,name,id_temp_hotel_details) VALUES (?,?,?)";
    
                    $arreglo = [$CodeRoom,$NameRoom,$resTemporary_Hotel_Details];
    
                    $consult_scape = $fn->prepararConsulta($sqlTemporary_Hotel_Details_Rooms, $arreglo);
                    $resTemporary_Hotel_Details_Rooms = $this->insertar($consult_scape);
    
                    // $RatesCount = count($hotel['rooms'][$i]['rates']);
                    $RatesCount = isset($hotel['rooms'][$i]['rates']) ? count($hotel['rooms'][$i]['rates']) : 0;
                    for($a=0;$a<$RatesCount;$a++){
                        $rateKey =      isset($hotel['rooms'][$i]['rates'][$a]['rateKey'])      ? $hotel['rooms'][$i]['rates'][$a]['rateKey']       : "";
                        $rateClass =    isset($hotel['rooms'][$i]['rates'][$a]['rateClass'])    ? $hotel['rooms'][$i]['rates'][$a]['rateClass']     : "";
                        $rateType =     isset($hotel['rooms'][$i]['rates'][$a]['rateType'])     ? $hotel['rooms'][$i]['rates'][$a]['rateType']      : "";
                        $net =          isset($hotel['rooms'][$i]['rates'][$a]['net'])          ? $hotel['rooms'][$i]['rates'][$a]['net']           : "";
                        $allotment =    isset($hotel['rooms'][$i]['rates'][$a]['allotment'])    ? $hotel['rooms'][$i]['rates'][$a]['allotment']     : "";
                        $paymentType =  isset($hotel['rooms'][$i]['rates'][$a]['paymentType'])  ? $hotel['rooms'][$i]['rates'][$a]['paymentType']   : "";
                        $packaging =    isset($hotel['rooms'][$i]['rates'][$a]['packaging'])    ? $hotel['rooms'][$i]['rates'][$a]['packaging']     : "";
                        $boardCode =    isset($hotel['rooms'][$i]['rates'][$a]['boardCode'])    ? $hotel['rooms'][$i]['rates'][$a]['boardCode']     : "";
                        $boardName =    isset($hotel['rooms'][$i]['rates'][$a]['boardName'])    ? $hotel['rooms'][$i]['rates'][$a]['boardName']     : "";
                        $rooms =        isset($hotel['rooms'][$i]['rates'][$a]['rooms'])        ? $hotel['rooms'][$i]['rates'][$a]['rooms']         : "";
                        $adults =       isset($hotel['rooms'][$i]['rates'][$a]['adults'])       ? $hotel['rooms'][$i]['rates'][$a]['adults']        : "";
                        $children =     isset($hotel['rooms'][$i]['rates'][$a]['children'])     ? $hotel['rooms'][$i]['rates'][$a]['children']      : "";
                        $childrenAges = isset($hotel['rooms'][$i]['rates'][$a]['childrenAges']) ? $hotel['rooms'][$i]['rates'][$a]['childrenAges']  : "";
    
                        $sqlTemporary_Hotel_Rooms_Rates = "INSERT INTO Temporary_Hotel_Rooms_Rates".$codigoTabla." 
                        (rateKey,rateClass,rateType,net,allotment,paymentType,packaging,boardCode,boardName,rooms,adults,children,childrenAges,id_hotel_rooms) 
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    
                        $arreglo = [$rateKey,$rateClass,$rateType,$net,$allotment,$paymentType,$packaging,$boardCode,$boardName,$rooms,$adults,$children,$childrenAges,$resTemporary_Hotel_Details_Rooms];
    
                        $consult_scape = $fn->prepararConsulta($sqlTemporary_Hotel_Rooms_Rates, $arreglo);
                        $resTemporary_Hotel_Rooms_Rates = $this->insertar($consult_scape);
    
                        // $CancelacionCount = count($hotel['rooms'][$i]['rates'][$a]['cancellationPolicies']);
                        $CancelacionCount = isset($hotel['rooms'][$i]['rates'][$a]['cancellationPolicies']) ? count($hotel['rooms'][$i]['rates'][$a]['cancellationPolicies']) : 0;
                        for($c=0;$c<$CancelacionCount;$c++){
                            $amount =      isset($hotel['rooms'][$i]['rates'][$a]['cancellationPolicies'][$c]['amount']) ? $hotel['rooms'][$i]['rates'][$a]['cancellationPolicies'][$c]['amount']      : "";
                            $from =        isset($hotel['rooms'][$i]['rates'][$a]['cancellationPolicies'][$c]['from'])   ? $hotel['rooms'][$i]['rates'][$a]['cancellationPolicies'][$c]['from']        : "";
    
                            $sqlTemporary_CancellationPolicies = "INSERT INTO Temporary_CancellationPolicies".$codigoTabla."
                            (`amount`,`from`,id_hotel_room_rates) VALUES (?,?,?)";
    
                            $arreglo = [$amount,$from,$resTemporary_Hotel_Rooms_Rates];
    
                            $consult_scape = $fn->prepararConsulta($sqlTemporary_CancellationPolicies, $arreglo);
                            $resTemporary_CancellationPolicies = $this->insertar($consult_scape);
                            
                        }
    
                        if (isset($hotel['rooms'][$i]['rates'][$a]['offers'])) {
                            // $CountOffers_Rates = count($hotel['rooms'][$i]['rates'][$a]['offers']);
                            $CountOffers_Rates = isset($hotel['rooms'][$i]['rates'][$a]['offers']) ? count($hotel['rooms'][$i]['rates'][$a]['offers']) : 0;
                            for($c=0;$c<$CountOffers_Rates;$c++){
                                $code =      isset($hotel['rooms'][$i]['rates'][$a]['offers'][$c]['code'])   ? $hotel['rooms'][$i]['rates'][$a]['offers'][$c]['code']      : "";
                                $name =      isset($hotel['rooms'][$i]['rates'][$a]['offers'][$c]['name'])   ? $hotel['rooms'][$i]['rates'][$a]['offers'][$c]['name']      : "";
                                $amount =    isset($hotel['rooms'][$i]['rates'][$a]['offers'][$c]['amount']) ? $hotel['rooms'][$i]['rates'][$a]['offers'][$c]['amount']    : "";
        
                                $sqlTemporary_Offers_Rates = "INSERT INTO Temporary_Offers_Rates".$codigoTabla." 
                                (`code`,`name`,`amount`,id_hotel_rooms_rates) VALUES (?,?,?,?)";
        
                                $arreglo = [$code,$name,$amount,$resTemporary_Hotel_Rooms_Rates];
        
                                $consult_scape = $fn->prepararConsulta($sqlTemporary_Offers_Rates, $arreglo);
                                $resTemporary_Offers_Rates = $this->insertar($consult_scape);
                                
                            }
                        }
    
                        if (isset($hotel['rooms'][$i]['rates'][$a]['promotions'])) {
                            // $CountPromotions = count($hotel['rooms'][$i]['rates'][$a]['promotions']);
                            $CountPromotions = isset($hotel['rooms'][$i]['rates'][$a]['promotions']) ? count($hotel['rooms'][$i]['rates'][$a]['promotions']) : 0;
                            for($c=0;$c<$CountPromotions;$c++){
                                $code =      isset($hotel['rooms'][$i]['rates'][$a]['promotions'][$c]['code'])   ? $hotel['rooms'][$i]['rates'][$a]['promotions'][$c]['code']      : "";
                                $name =      isset($hotel['rooms'][$i]['rates'][$a]['promotions'][$c]['name'])   ? $hotel['rooms'][$i]['rates'][$a]['promotions'][$c]['name']      : "";
        
                                $sqlTemporary_Promotions = "INSERT INTO Temporary_Promotions".$codigoTabla." 
                                (`code`,`name`,id_hotel_rooms_rates) VALUES (?,?,?)";
        
                                $arreglo = [$code,$name,$resTemporary_Hotel_Rooms_Rates];
        
                                $consult_scape = $fn->prepararConsulta($sqlTemporary_Promotions, $arreglo);
                                $resTemporary_Promotions = $this->insertar($consult_scape);
                                
                            }
                        }
    
                        if (isset($hotel['rooms'][$i]['rates'][$a]['taxes'])) {
                            // $CountTaxes = count($hotel['rooms'][$i]['rates'][$a]['taxes']);
                            $CountTaxes = isset($hotel['rooms'][$i]['rates'][$a]['taxes']) ? count($hotel['rooms'][$i]['rates'][$a]['taxes']) : 0;
                            for($c=0;$c<$CountTaxes;$c++){
                                $countTaxTaxes =    isset($hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'])             ? count($hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'])         : 0;
                                $allIncluded =      isset($hotel['rooms'][$i]['rates'][$a]['taxes'][$c]['allIncluded'])   ? $hotel['rooms'][$i]['rates'][$a]['taxes'][$c]['allIncluded']      : "";
        
                                $sqlTemporary_Taxes_Rates = "INSERT INTO Temporary_Taxes_Rates".$codigoTabla." 
                                (`allIncluded`,id_rates) VALUES (?,?)";
        
                                $arreglo = [$allIncluded,$resTemporary_Hotel_Rooms_Rates];
        
                                $consult_scape = $fn->prepararConsulta($sqlTemporary_Taxes_Rates, $arreglo);
                                $resTemporary_Taxes_Rates = $this->insertar($consult_scape);
        
                                
                                for($t=0;$t<$countTaxTaxes;$t++){
                                    $included =         isset($hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'][$t]['included'])       ? $hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'][$t]['included']       : "";
                                    $amount =           isset($hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'][$t]['amount'])         ? $hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'][$t]['amount']         : "";
                                    $currency =         isset($hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'][$t]['currency'])       ? $hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'][$t]['currency']       : "";
                                    $clientAmount =     isset($hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'][$t]['clientAmount'])   ? $hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'][$t]['clientAmount']   : "";
                                    $clientCurrency =   isset($hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'][$t]['clientCurrency']) ? $hotel['rooms'][$i]['rates'][$a]['taxes']['taxes'][$t]['clientCurrency'] : "";
        
                                    $sqlTemporary_Taxes_Tax = "INSERT INTO Temporary_Taxes_Tax".$codigoTabla." 
                                    (`included`,amount,currency,clientAmount,clientCurrency,id_taxes_rates) VALUES (?,?,?,?,?,?)";
        
                                    $arreglo = [$included,$amount,$currency,$clientAmount,$clientCurrency,$resTemporary_Taxes_Rates];
        
                                    $consult_scape = $fn->prepararConsulta($sqlTemporary_Taxes_Tax, $arreglo);
                                    $resTemporary_Taxes_Tax = $this->insertar($consult_scape);
                                }
                                
                            }
                        }
                    }
                }
            
        }

        //$resTemporary_Hotel_Details = $this->buscar("Temporary_Hotel_Details{$codigoTabla}", ["*"]);
    
        return $resTemporary_Hotel_Details;
    }
    public function GetHotelesDisponibles($codigoTabla,$limit,$pagina){
        $qryGetHoteles = "SELECT thd.name as nombreHotel, thd.longitude, thd.latitude, thd.destinationName, thd.minRate, thd.code, trooms.id, thd.categoryCode
                        FROM Temporary_Hotel_Details{$codigoTabla} thd
                        LEFT JOIN Temporary_Hotel_Details_Rooms{$codigoTabla} trooms ON trooms.id_temp_hotel_details = thd.id
                        LEFT JOIN Temporary_Hotel_Rooms_Rates{$codigoTabla} trates ON trates.id_hotel_rooms = trooms.id GROUP BY thd.name LIMIT $limit offset $pagina";
        $res = $this->buscar($qryGetHoteles);

        return $res;
    }
    public function Filtros($categoria,$tipCama,$tipPlanes,$codigoTabla,$limit,$offset,$preMin,$preMax){
        $qryFiltros = "SELECT thd.name as nombreHotel, thd.longitude, thd.latitude, thd.destinationName, thd.minRate, thd.currency, thd.code, thd.categoryName, thd.categoryCode, COUNT(trates.rooms) as totalHabitaciones, trooms.id 
                        FROM Temporary_Hotel_Details{$codigoTabla} thd
                        LEFT JOIN Temporary_Hotel_Details_Rooms{$codigoTabla} trooms ON trooms.id_temp_hotel_details = thd.id
                        LEFT JOIN Temporary_Hotel_Rooms_Rates{$codigoTabla} trates ON trates.id_hotel_rooms = trooms.id WHERE";

        $qryFiltros .= "(thd.minRate BETWEEN '{$preMin}' AND '{$preMax}')";

        if($categoria>0){
            $catNumber = $categoria;
            if($catNumber > 0){
                $filtrados = " AND (thd.categoryCode = '{$catNumber}EST'";
                $filtrados .= " OR thd.categoryCode ='H{$catNumber}_5'";
                $filtrados .= " OR thd.categoryCode = 'H{$catNumber}LL'";
                $filtrados .= " OR thd.categoryCode = 'H{$catNumber}S'";
                
                if($catNumber > 3){
                    $filtrados .= " OR thd.categoryCode = 'H{$catNumber}LUX'";
                    // $filtrados .= $filtrados;
                }
            
            }else if($catNumber == 6){
                $filtrados = " AND (thd.categoryCode = 'AG' 
                                OR thd.categoryCode = 'ALBER' 
                                OR thd.categoryCode = 'BOU' 
                                OR thd.categoryCode = 'CAMP1' 
                                OR thd.categoryCode = 'CAMP2' 
                                OR thd.categoryCode = 'CHUES' 
                                OR thd.categoryCode = 'HIST' 
                                OR thd.categoryCode = 'HRS' 
                                OR thd.categoryCode = 'HSR1'
                                OR thd.categoryCode = 'HSR2' 
                                OR thd.categoryCode = 'LODGE' 
                                OR thd.categoryCode = 'MINI' 
                                OR thd.categoryCode = 'PENSI' 
                                OR thd.categoryCode = 'POUSA' 
                                OR thd.categoryCode = 'RESID'
                                OR thd.categoryCode = 'RSORT'
                                OR thd.categoryCode = 'SPC'
                                OR thd.categoryCode = 'STD'
                                OR thd.categoryCode = 'SUP'
                                OR thd.categoryCode = 'VILLA'
                                OR thd.categoryCode = 'VTV'
                                OR thd.categoryCode = 'HS'
                                OR thd.categoryCode = 'HR'
                                OR thd.categoryCode = 'BB'
                                OR thd.categoryCode = 'APTH'
                                OR thd.categoryCode = 'AT1'";
                for($i=2;$i<6;$i++){
                    $filtrados .= " OR thd.categoryCode = 'APTH{$i}'";
                    $filtrados .= " OR thd.categoryCode = 'AT{$i}'";
                    $filtrados .= " OR thd.categoryCode = 'BB{$i}'";
                    $filtrados .= " OR thd.categoryCode = 'HR{$i}'";
                    $filtrados .= " OR thd.categoryCode = 'HS{$i}'";
                }
            }else{}
            $qryFiltros .= $filtrados.")";
        }
        if($tipCama != 'Preferencia de camas'){
            $qryFiltros .= " AND (trooms.`code` = '$tipCama')";
        }
        if($tipPlanes != 'Tipos de planes'){
            $qryFiltros .= " AND (trates.boardCode = '$tipPlanes')";
        }
        $qryFiltros .= " GROUP BY thd.id ORDER BY thd.minRate ASC LIMIT {$limit} OFFSET {$offset}";

        $res = $this->buscar($qryFiltros);
        return $res;
        
    }
    public function CountFiltros($categoria,$tipCama,$tipPlanes,$codigoTabla,$preMin,$preMax){
        $qryFiltros = "SELECT thd.name as nombreHotel, thd.longitude, thd.latitude, thd.destinationName, thd.minRate, thd.code, thd.categoryName,COUNT(trates.rooms) as totalHabitaciones, trooms.id 
                        FROM Temporary_Hotel_Details{$codigoTabla} thd
                        LEFT JOIN Temporary_Hotel_Details_Rooms{$codigoTabla} trooms ON trooms.id_temp_hotel_details = thd.id
                        LEFT JOIN Temporary_Hotel_Rooms_Rates{$codigoTabla} trates ON trates.id_hotel_rooms = trooms.id WHERE";
        $qryFiltros .= "(thd.minRate BETWEEN '{$preMin}' AND '{$preMax}')";
        if($categoria > 0 && $categoria !== ''){
            $catNumber = $categoria;
            if($catNumber > 0){
                $filtrados = " AND (thd.categoryCode = '{$catNumber}EST'";
                $filtrados .= " OR thd.categoryCode ='H{$catNumber}_5'";
                $filtrados .= " OR thd.categoryCode = 'H{$catNumber}LL'";
                $filtrados .= " OR thd.categoryCode = 'H{$catNumber}S'";
                
                if($catNumber > 3){
                    $filtrados .= " OR thd.categoryCode = 'H{$catNumber}LUX'";
                }
            
            }else if($catNumber == 6){
                $filtrados = " AND (thd.categoryCode = 'AG' 
                                OR thd.categoryCode = 'ALBER' 
                                OR thd.categoryCode = 'BOU' 
                                OR thd.categoryCode = 'CAMP1' 
                                OR thd.categoryCode = 'CAMP2' 
                                OR thd.categoryCode = 'CHUES' 
                                OR thd.categoryCode = 'HIST' 
                                OR thd.categoryCode = 'HRS' 
                                OR thd.categoryCode = 'HSR1'
                                OR thd.categoryCode = 'HSR2' 
                                OR thd.categoryCode = 'LODGE' 
                                OR thd.categoryCode = 'MINI' 
                                OR thd.categoryCode = 'PENSI' 
                                OR thd.categoryCode = 'POUSA' 
                                OR thd.categoryCode = 'RESID'
                                OR thd.categoryCode = 'RSORT'
                                OR thd.categoryCode = 'SPC'
                                OR thd.categoryCode = 'STD'
                                OR thd.categoryCode = 'SUP'
                                OR thd.categoryCode = 'VILLA'
                                OR thd.categoryCode = 'VTV'
                                OR thd.categoryCode = 'HS'
                                OR thd.categoryCode = 'HR'
                                OR thd.categoryCode = 'BB'
                                OR thd.categoryCode = 'APTH'
                                OR thd.categoryCode = 'AT1'";
                for($i=2;$i<6;$i++){
                    $filtrados .= " OR thd.categoryCode = 'APTH{$i}'";
                    $filtrados .= " OR thd.categoryCode = 'AT{$i}'";
                    $filtrados .= " OR thd.categoryCode = 'BB{$i}'";
                    $filtrados .= " OR thd.categoryCode = 'HR{$i}'";
                    $filtrados .= " OR thd.categoryCode = 'HS{$i}'";
                }
            }else{}
            $qryFiltros .= $filtrados.")";
        }
        if($tipCama != 'Preferencia de camas'){
            $qryFiltros .= " AND (trooms.`code` = '$tipCama')";
        }
        if($tipPlanes != 'Tipos de planes'){
            $qryFiltros .= " AND (trates.boardCode = '$tipPlanes')";
        }
        $qryFiltros .= " GROUP BY thd.id";

        $res = $this->buscar($qryFiltros);
        // echo $qryFiltros;
        return $res;
    }
    public function ConteoDeHoteles($codigoTabla){
        $qry = "SELECT COUNT(*) AS contador FROM Temporary_Hotel_Details{$codigoTabla}";
        $res = $this->buscar($qry);
        return $res;
    }
    public function TipPlanes(){
        $qry = "SELECT `code`, `description` FROM `T_Boards`";
        $res = $this->buscar($qry);
        return $res;
    }
    public function PrefHabitaciones($codigoTabla){
        $qry = "SELECT `code`, `name` FROM Temporary_Hotel_Details_Rooms{$codigoTabla} GROUP BY `code`";
        $res = $this->buscar($qry);
        return $res;
    }
    
}
?>