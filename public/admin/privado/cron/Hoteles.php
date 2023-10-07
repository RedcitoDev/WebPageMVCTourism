<?php
    include("./../../../../vendor/autoload.php");

    use Classes\controladorMySql;
    
    set_time_limit(0);

    class Hoteles {
        public $apiKey      = "";
        public $secret      = "";
        public $endpoint    = "";

        public $mysql;

        // Constructor
        public function __construct($apiKey = HOTELBEDS_API_KEY, $secret = HOTELBEDS_SECRET, $endpoint = HOTELBEDS_API_ENDPOINT){
            $this->apiKey       = $apiKey;
            $this->secret       = $secret;
            $this->endpoint     = $endpoint;
            
            $this->mysql        = controladorMySql::getInstance();
        }

        public function prepararConsulta ($qry, $arreglo) {
            $qry = str_replace("?", '${?}', $qry);

            for ($y = 0, $z = count($arreglo); $y < $z; $y++) {
                $arreglo[$y] = stripslashes($arreglo[$y]);
                $arreglo[$y] = addslashes($arreglo[$y]);
                
                $arreglo[$y] = str_replace("\\'", "\'", $arreglo[$y]);
                
                $pos = strpos($qry, '${?}');

                if ($pos !== false) {
                    $qry = substr_replace($qry, "'$arreglo[$y]'", $pos, strlen('${?}'));
                }
            }
            
            return $qry;
        }

        public function variables_T_Hotel_Description($arr) {
            $code                   = isset($arr["code"])                    ? $arr["code"]                     : "";
            $name                   = isset($arr["name"]["content"])         ? $arr["name"]["content"]          : "";
            $description            = isset($arr["description"]["content"])  ? $arr["description"]["content"]   : "";
            $countryCode            = isset($arr["countryCode"])             ? $arr["countryCode"]              : "";
            $stateCode              = isset($arr["stateCode"])               ? $arr["stateCode"]                : "";
            $destinationCode        = isset($arr["destinationCode"])         ? $arr["destinationCode"]          : "";
            $categoryCode           = isset($arr["categoryCode"])            ? $arr["categoryCode"]             : "";
            $categoryGroupCode      = isset($arr["categoryGroupCode"])       ? $arr["categoryGroupCode"]        : "";
            $chainCode              = isset($arr["chainCode"])               ? $arr["chainCode"]                : "";
            $accommodationTypeCode  = isset($arr["accommodationTypeCode"])   ? $arr["accommodationTypeCode"]    : "";
            $email                  = isset($arr["email"])                   ? $arr["email"]                    : "";
            $license                = isset($arr["license"])                 ? $arr["license"]                  : "";
            $S2C                    = isset($arr["S2C"])                     ? $arr["S2C"]                      : "";
            $web                    = isset($arr["web"])                     ? $arr["web"]                      : "";
            $ranking                = isset($arr["ranking"])                 ? $arr["ranking"]                  : "";

            return [$code, $name, $description, $countryCode, $stateCode,$destinationCode,
                    $categoryCode, $categoryGroupCode, $chainCode, $accommodationTypeCode,
                    $email, $license, $S2C, $web, $ranking];
        }

        public function crear_T_Hotel_Description($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO `T_Hotel_Description` ( `code`, `name`, `description`, `countryCode`, `stateCode`, `destinationCode`,
                                `categoryCode`, `categoryGroupCode`, `chainCode`, `accommodationTypeCode`,
                                `email`, `license`, `S2C`, `web`, `ranking`)

                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            return $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function variables_T_Hotel_Address($arr) {
            $content    = isset($arr["address"]["content"])  ? $arr["address"]["content"]    : "";
            $street     = isset($arr["address"]["street"])   ? $arr["address"]["street"]     : "";
            $number     = isset($arr["address"]["number"])   ? $arr["address"]["number"]     : "";
            $postalCode = isset($arr["postalCode"])          ? $arr["postalCode"]            : "";
            $city       = isset($arr["city"]["content"])     ? $arr["city"]["content"]       : "";

            return [$content, $street, $number, $postalCode, $city];
        }

        public function crear_T_Hotel_Address($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO `T_Hotel_Address` (`content`, `street`, `number`, `postalCode`, `city`, `id_hotel_description`)
                    
                    VALUES (?, ?, ?, ?, ?, ?)";

            $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function variables_T_Hotel_Contact($arr) {
            $phoneHotel         = "";
            $phoneManagement    = "";
            $phoneFaxnumber     = "";

            if (isset($arr["phones"])) {
                // Este ciclo se llena la tabla de T_Hotel_Contact
                for ($j=0, $m = count($arr["phones"]); $j < $m; $j++) {
                    if (($arr["phones"][$j]["phoneType"] == "PHONEHOTEL")) {
                        $phoneHotel = $arr["phones"][$j]["phoneNumber"];
                        continue;
                    }

                    if (($arr["phones"][$j]["phoneType"] == "PHONEMANAGEMENT")){
                        $phoneManagement = $arr["phones"][$j]["phoneNumber"];
                        continue;
                    }
                    
                    if (($arr["phones"][$j]["phoneType"] == "FAXNUMBER")) {
                        $phoneFaxnumber = $arr["phones"][$j]["phoneNumber"];
                        continue;
                    }
                }
            }

            return [$phoneHotel, $phoneManagement, $phoneFaxnumber];
        }

        public function crear_T_Hotel_Contact($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO `T_Hotel_Contact` (`phoneHotel`, `phoneManagement`, `phoneFaxnumber`, `id_hotel_description`)
                                    
            VALUES (?, ?, ?, ?)";

            $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function variables_T_Hotel_Coordinates($arr) {
            $longitude   = isset($arr["longitude"])  ? $arr["longitude"]  : "";
            $latitude    = isset($arr["latitude"])   ? $arr["latitude"]   : "";

            return [$longitude, $latitude];
        }

        public function crear_T_Hotel_Coordinates($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO `T_Hotel_Coordinates` (`longitude`, `latitude`, `id_hotel_description`)
                            
            VALUES (?, ?, ?)";

            $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function variables_T_Facilities_Hotel_Description($arr) {
            $facilityCode           = isset($arr["facilityCode"])          ? $arr["facilityCode"]        : "";
            $facilityGroupCode      = isset($arr["facilityGroupCode"])     ? $arr["facilityGroupCode"]   : "";
            $order                  = isset($arr["order"])                 ? $arr["order"]               : "";
            $indYesOrNo             = isset($arr["indYesOrNo"])            ? $arr["indYesOrNo"]          : "";
            $number                 = isset($arr["number"])                ? $arr["number"]              : "";
            $voucher                = isset($arr["voucher"])               ? $arr["voucher"]             : "";
            $indLogic               = isset($arr["indLogic"])              ? $arr["indLogic"]            : "";
            $indFee                 = isset($arr["indFee"])                ? $arr["indFee"]              : "";
            $distance               = isset($arr["distance"])              ? $arr["distance"]            : "";
            $timeFrom               = isset($arr["timeFrom"])              ? $arr["timeFrom"]            : "";
            $timeTo                 = isset($arr["timeTo"])                ? $arr["timeTo"]              : "";
            $dateTo                 = isset($arr["dateTo"])                ? $arr["dateTo"]              : "";

            return [$facilityCode, $facilityGroupCode, $order, $indYesOrNo, $number, $voucher, $indLogic, $indFee, $distance, $timeFrom, $timeTo, $dateTo];
        }

        public function crear_T_Facilities_Hotel_Description($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO `T_Facilities_Hotel_Description` ( `facilityCode`, `facilityGroupCode`, `order`,
                                                                    `number`, `indYesOrNo`, `voucher`, `indLogic`,
                                                                    `indFee`, `distance`, `timeFrom`, `timeTo`,
                                                                    `dateTo`, `id_hotel_description`)

                                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function variables_T_InterestPoint_Hotel($arr) {
            $facilityCode           = isset($arr["facilityCode"])          ? $arr["facilityCode"]        : "";
            $facilityGroupCode      = isset($arr["facilityGroupCode"])     ? $arr["facilityGroupCode"]   : "";
            $order                  = isset($arr["order"])                 ? $arr["order"]               : "";
            $poiName                = isset($arr["poiName"])               ? $arr["poiName"]             : "";
            $distance               = isset($arr["distance"])              ? $arr["distance"]            : "";

            return [$facilityCode, $facilityGroupCode, $order, $poiName, $distance];
        }

        public function crear_T_InterestPoint_Hotel($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO `T_InterestPoint_Hotel` (   `facilityCode`, `facilityGroupCode`, `order`,
                                `poiName`, `distance`, `id_hotel_description`)

                    VALUES (?, ?, ?, ?, ?, ?)";

            $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function variables_T_Img_Hotel_Room($arr) {
            $imageTypeCode           = isset($arr["imageTypeCode"])                ? $arr["imageTypeCode"]       : "";
            $path                    = isset($arr["path"])                         ? $arr["path"]                : "";
            $order                   = isset($arr["order"])                        ? $arr["order"]               : "";
            $visualOrder             = isset($arr["visualOrder"])                  ? $arr["visualOrder"]         : "";
            $roomCode                = isset($arr["roomCode"])                     ? $arr["roomCode"]            : "";
            $roomType                = isset($arr["roomType"])                     ? $arr["roomType"]            : "";
            $characteristicCode      = isset($arr["characteristicCode"])           ? $arr["characteristicCode"]  : "";

            return [$imageTypeCode, $path, $order, $visualOrder, $roomCode, $roomType, $characteristicCode];
        }

        public function crear_T_Img_Hotel_Room($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO `T_Img_Hotel_Room` (`imageTypeCode`, `path`, `order`, `visualOrder`, `roomCode`, `roomType`, `characteristicCode`, `id_hotel_description`)
                                        
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function crear_T_Hotel_Boards($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO `T_Hotel_Boards` (`code`, `id_hotel_description`)
                                        
                    VALUES (?, ?)";
            
            $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function crear_T_Hotel_Segment($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO `T_Hotel_Segment` (`segmentCode`, `id_hotel_description`)
                                        
                    VALUES (?, ?)";
            

            $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function crear_T_Hotel_Terminals($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO `T_Hotel_Terminals` (`terminalCode`, `distance`, `id_hotel_description`)
                                        
                    VALUES (?, ?, ?)";
            

            $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function variables_T_Hotel_Issues($arr) {
            $issueCode      = isset($arr["issueCode"])    ? $arr["issueCode"]     : "";
            $issueType      = isset($arr["issueType"])    ? $arr["issueType"]     : "";
            $dateFrom       = isset($arr["dateFrom"])     ? $arr["dateFrom"]      : "";
            $dateTo         = isset($arr["dateTo"])       ? $arr["dateTo"]        : "";
            $order          = isset($arr["order"])        ? $arr["order"]         : "";
            $alternative    = isset($arr["alternative"])  ? $arr["alternative"]   : "";

            return [$issueCode, $issueType, $dateFrom, $dateTo, $order, $alternative];
        }

        public function crear_T_Hotel_Issues($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO `T_Hotel_Issues` (`issueCode`, `issueType`, `dateFrom`, `dateTo`, `order`, `alternative`,`id_hotel_description`)
                                        
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                    
            $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function variables_T_Hotel_Rooms($arr) {
            $roomCode               = isset($arr["roomCode"])              ? $arr["roomCode"]            : "";
            $isParentRoom           = isset($arr["isParentRoom"])          ? $arr["isParentRoom"]        : "";
            $minPax                 = isset($arr["minPax"])                ? $arr["minPax"]              : "";
            $maxPax                 = isset($arr["maxPax"])                ? $arr["maxPax"]              : "";
            $maxAdults              = isset($arr["maxAdults"])             ? $arr["maxAdults"]           : "";
            $maxChildren            = isset($arr["maxChildren"])           ? $arr["maxChildren"]         : "";
            $minAdults              = isset($arr["minAdults"])             ? $arr["minAdults"]           : "";
            $roomType               = isset($arr["roomType"])              ? $arr["roomType"]            : "";
            $characteristicCode     = isset($arr["characteristicCode"])    ? $arr["characteristicCode"]  : "";

            return [$roomCode, $isParentRoom, $minPax, $maxPax, $maxAdults, $maxChildren, $minAdults, $roomType, $characteristicCode];
        }

        public function crear_T_Hotel_Rooms($arreglo) {
            // Se guardan los registros en la base de datos
            $qry = "INSERT INTO T_Hotel_Rooms ( roomCode, isParentRoom, minPax,
                                                maxPax, maxAdults, maxChildren,
                                                minAdults, roomType, characteristicCode,
                                                id_hotel_description)

                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            return $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
        }

        public function guardarHoteles() {
            for ($cont = 0, $limit = 1 /*286000 / 1000*/; $cont < $limit; $cont++) {
                $limiteInferior =  1 + (1000 * $cont);
                $limiteSuperior =  1000 + (1000 * $cont);

                // Con este decidimos si deseamos consultar directo a la API o en su lugar utilizar respuestas descargadas
                $src = "file";
                $urlConsulta = ($src == "file") ? "./consultas_guardadas/Hotels_" . $limiteInferior . "to" . $limiteSuperior . ".txt" : "hotel-content-api/1.0/hotels?fields=all&language=CAS&from={$limiteInferior}&to={$limiteSuperior}&useSecondaryLanguage=false";
            
                $qryHotels = $this->obtenerHoteles($src, "GET", $urlConsulta, 10);

                // En este ciclo lo que se hace es guardar los datos base de los hoteles para obtener su ID e ir comenzando a llenar las tablas secundarias
                for ($i=0, $l = count($qryHotels["hotels"]); $i < $l/*1*/; $i++) { 
                    $hotelActual = $limiteInferior + $i;
                    echo "Hotel #{$hotelActual} guardado..."; 

                    try {
                        [$code, $name, $description, $countryCode, $stateCode,$destinationCode,
                        $categoryCode, $categoryGroupCode, $chainCode, $accommodationTypeCode,
                        $email, $license, $S2C, $web, $ranking] = $this->variables_T_Hotel_Description($qryHotels["hotels"][$i]);
                        
                        $arreglo = [$code, $name, $description, $countryCode, $stateCode,
                                    $destinationCode,$categoryCode, $categoryGroupCode,
                                    $chainCode, $accommodationTypeCode, $email, $license,
                                    $S2C, $web, $ranking];

                        $id_T_Hotel_Description = $this->crear_T_Hotel_Description($arreglo);
        
                        // En esta parte se llena la tabla de T_Hotel_Address
                        if (true) {
                            [$content, $street, $number, $postalCode,$city] = $this->variables_T_Hotel_Address($qryHotels["hotels"][$i]);
                            
                            $id_hotel_description = $id_T_Hotel_Description;

                            $arreglo = [$content, $street, $number, $postalCode, $city, $id_hotel_description];

                            $this->crear_T_Hotel_Address($arreglo);                 
                        }
        
                        // En esta parte se llena la tabla de T_Hotel_Contact
                        if (true) {
                            [$phoneHotel, $phoneManagement, $phoneFaxnumber] = $this->variables_T_Hotel_Contact($qryHotels["hotels"][$i]);
                                
                            $id_hotel_description = $id_T_Hotel_Description;
                            
                            $arreglo = [$phoneHotel, $phoneManagement, $phoneFaxnumber, $id_hotel_description];
        
                            $this->crear_T_Hotel_Contact($arreglo);
                        }
        
                        // En esta parte se llena la tabla de T_Hotel_Coordinates
                        if (true) {
                            [$longitude, $latitude] = $this->variables_T_Hotel_Coordinates($qryHotels["hotels"][$i]["coordinates"]);
        
                            $id_hotel_description = $id_T_Hotel_Description;
        
                            $arreglo = [$longitude, $latitude, $id_hotel_description];
        
                            $this->crear_T_Hotel_Coordinates($arreglo);
                        }   
        
                        // En este ciclo se llena la tabla de T_Facilities_Hotel_Description
                        if (array_key_exists("facilities", $qryHotels["hotels"][$i])) {
                            for ($j=0, $m = count($qryHotels["hotels"][$i]["facilities"]); $j < $m; $j++) { 
                                [$facilityCode, $facilityGroupCode, $order,
                                $indYesOrNo,$number, $voucher, $indLogic,
                                $indFee, $distance, $timeFrom, $timeTo, $dateTo] = $this->variables_T_Facilities_Hotel_Description($qryHotels["hotels"][$i]["facilities"][$j]);
                                
                                $id_hotel_description   = $id_T_Hotel_Description;
                                
                                $arreglo = [$facilityCode, $facilityGroupCode, $order,
                                            $number, $indYesOrNo, $voucher, $indLogic,
                                            $indFee, $distance, $timeFrom, $timeTo,
                                            $dateTo, $id_hotel_description];
            
                                $this->crear_T_Facilities_Hotel_Description($arreglo);
                            }
                        }
                        
        
                        // En este ciclo se llena la tabla de T_InterestPoint_Hotel
                        if (array_key_exists("interestPoints", $qryHotels["hotels"][$i])) {
                            for ($j=0, $m = count($qryHotels["hotels"][$i]["interestPoints"]); $j < $m; $j++) { 
                                [$facilityCode, $facilityGroupCode, $order, $poiName, $distance] = $this->variables_T_InterestPoint_Hotel($qryHotels["hotels"][$i]["interestPoints"][$j]);
                                
                                $id_hotel_description   = $id_T_Hotel_Description;

                                $arreglo = [$facilityCode, $facilityGroupCode, $order,
                                            $poiName, $distance, $id_hotel_description];
            
                                $this->crear_T_InterestPoint_Hotel($arreglo);
                            }
                        }
                        
                        // En este ciclo se llena la tabla de T_Img_Hotel_Room
                        if (array_key_exists("images", $qryHotels["hotels"][$i])) {
                            for ($j=0, $m = count($qryHotels["hotels"][$i]["images"]); $j < $m; $j++) {
                                [$imageTypeCode, $path, $order, $visualOrder,
                                $roomCode, $roomType, $characteristicCode] = $this->variables_T_Img_Hotel_Room($qryHotels["hotels"][$i]["images"][$j]);
                                
                                $id_hotel_description    = $id_T_Hotel_Description;

                                $arreglo = [$imageTypeCode, $path, $order , $visualOrder, $roomCode, $roomType, $characteristicCode, $id_hotel_description];
            
                                $this->crear_T_Img_Hotel_Room($arreglo);
                            }
                        }
                        
                        // En este ciclo se llena la tabla de T_Hotel_Boards
                        if (array_key_exists("boardCodes", $qryHotels["hotels"][$i])) {
                            for ($j=0, $m = count($qryHotels["hotels"][$i]["boardCodes"]); $j < $m; $j++) { 
                                $code = isset($qryHotels["hotels"][$i]["boardCodes"][$j]) ? $qryHotels["hotels"][$i]["boardCodes"][$j]  : "";
                                
                                $id_hotel_description    = $id_T_Hotel_Description;
                                        
                                $arreglo = [$code, $id_hotel_description];
            
                                $this->crear_T_Hotel_Boards($arreglo);
                            }
                        }

                        // En este ciclo se llena la tabla de T_Hotel_Segment
                        if (array_key_exists("segmentCodes", $qryHotels["hotels"][$i])) {
                            for ($j=0, $m = count($qryHotels["hotels"][$i]["segmentCodes"]); $j < $m; $j++) { 
                                $segmentCode = isset($qryHotels["hotels"][$i]["segmentCodes"][$j]) ? $qryHotels["hotels"][$i]["segmentCodes"][$j]  : "";
                                
                                $id_hotel_description    = $id_T_Hotel_Description;
                                        
                                $arreglo = [$segmentCode, $id_hotel_description];
            
                                $this->crear_T_Hotel_Segment($arreglo);
                            }
                        }
                        
                        // En este ciclo se llena la tabla de T_Hotel_Terminals
                        if (array_key_exists("terminals", $qryHotels["hotels"][$i])) {
                            for ($j=0, $m = count($qryHotels["hotels"][$i]["terminals"]); $j < $m; $j++) { 
                                $terminalCode   = isset($qryHotels["hotels"][$i]["terminals"][$j]["terminalCode"])  ? $qryHotels["hotels"][$i]["terminals"][$j]["terminalCode"]     : "";
                                $distance       = isset($qryHotels["hotels"][$i]["terminals"][$j]["distance"])      ? $qryHotels["hotels"][$i]["terminals"][$j]["distance"]         : "";
                                
                                $id_hotel_description    = $id_T_Hotel_Description;
                                        
                                $arreglo = [$terminalCode, $distance, $id_hotel_description];
            
                                $this->crear_T_Hotel_Terminals($arreglo);
                            }
                        }

                        // En este ciclo se llena la tabla de T_Hotel_Issues
                        if (array_key_exists("issues", $qryHotels["hotels"][$i])) {
                            for ($j=0, $m = count($qryHotels["hotels"][$i]["issues"]); $j < $m; $j++) {
                                [$issueCode, $issueType, $dateFrom, $dateTo, $order, $alternative] = $this->variables_T_Hotel_Issues($qryHotels["hotels"][$i]["issues"][$j]);
                                
                                $id_hotel_description    = $id_T_Hotel_Description;
                                        
                                $arreglo = [$issueCode, $issueType, $dateFrom, $dateTo, $order, $alternative, $id_hotel_description];
            
                                $this->crear_T_Hotel_Issues($arreglo);
                            }
                        }
                        
                        // En este ciclo se llena la tabla de T_Hotel_Rooms
                        if (array_key_exists("rooms", $qryHotels["hotels"][$i])) {
                            for ($j=0, $m = count($qryHotels["hotels"][$i]["rooms"]); $j < $m; $j++) {
                                [$roomCode, $isParentRoom, $minPax, $maxPax, $maxAdults,$maxChildren, $minAdults, $roomType, $characteristicCode] = 
                                $this->variables_T_Hotel_Rooms($qryHotels["hotels"][$i]["rooms"][$j]);
                                
                                $id_hotel_description    = $id_T_Hotel_Description;
                                            
                                $arreglo = [$roomCode, $isParentRoom, $minPax,
                                            $maxPax, $maxAdults, $maxChildren,
                                            $minAdults, $roomType, $characteristicCode,
                                            $id_hotel_description];
            
                                $id_T_Hotel_Rooms = $this->crear_T_Hotel_Rooms($arreglo);
            
                                // Ahora llenamos la tabla de tercer nivel (T_Room_Facilities) con el id que acabamos de crear ($id_T_Hotel_Rooms)
                                if (array_key_exists("roomFacilities", $qryHotels["hotels"][$i]["rooms"][$j])) {
                                    for ($k=0, $n = count($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"]); $k < $n; $k++) { 
                                        $facilityCode       = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["facilityCode"])         ? $qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["facilityCode"]       : "";
                                        $facilityGroupCode  = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["facilityGroupCode"])    ? $qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["facilityGroupCode"]  : "";
                                        $indLogic           = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["indLogic"])             ? $qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["indLogic"]           : "";
                                        $number             = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["number"])               ? $qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["number"]             : "";
                                        $voucher            = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["voucher"])              ? $qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["voucher"]            : "";
            
                                        $id_hotel_room    = $id_T_Hotel_Rooms;
            
                                        // Se guardan los registros en la base de datos
                                        $qry = "INSERT INTO T_Room_Facilities ( facilityCode,facilityGroupCode,
                                                                                indLogic, number, voucher,
                                                                                id_hotel_room)

                                                VALUES (?, ?, ?, ?, ?, ?)";

                                        $arreglo = [$facilityCode, $facilityGroupCode,
                                                    $indLogic, $number, $voucher,
                                                    $id_hotel_room];
            
                                        $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                                    }
                                }
            
                                // Ahora llenamos la tabla de tercer nivel (T_Room_Stay) con el id que acabamos de crear ($id_T_Hotel_Rooms)
                                if (array_key_exists("roomStays", $qryHotels["hotels"][$i]["rooms"][$j])) {
                                    for ($k=0, $n = count($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"]); $k < $n; $k++) { 
                                        $stayType        = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["stayType"])     ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["stayType"]     : "";
                                        $order           = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["order"])        ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["order"]        : "";
                                        $description     = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["description"])  ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["description"]  : "";
            
                                        $id_hotel_room    = $id_T_Hotel_Rooms;
            
                                        // Se guardan los registros en la base de datos
                                        $qry = "INSERT INTO T_Room_Stay (stayType, `order`, description, id_hotel_room) VALUES (?, ?, ?, ?)";
                                        $arreglo = [$stayType, $order, $description, $id_hotel_room];
            
                                        $id_T_Room_Stay = $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
            
                                        // Ahora llenamos la tabla de cuarto nivel (T_Room_Stay_Facilities) con el id que acabamos de crear ($id_T_Room_Stay)
                                        if (array_key_exists("roomStayFacilities", $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k])) {
                                            for ($i_a=0, $l_a = count($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"]); $i_a < $l_a; $i_a++) {
                                                $facilityCode       = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["facilityCode"])       ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["facilityCode"]         : "";
                                                $facilityGroupCode  = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["facilityGroupCode"])  ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["facilityGroupCode"]    : "";
                                                $number             = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["number"])             ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["number"]               : "";
            
                                                $id_room_stay = $id_T_Room_Stay;
            
                                                // Se guardan los registros en la base de datos
                                                $qry = "INSERT INTO T_Room_Stay_Facilities (facilityCode, facilityGroupCode, number, id_room_stay) VALUES (?, ?, ?, ?)";
                                                $arreglo = [$facilityCode, $facilityGroupCode, $number, $id_room_stay  ];
            
                                                $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                                            }
                                        } 
                                    }
                                }
                            }
                        }
                    } catch (\Exception $ex) {
                        echo $ex->getMessage();
                    }
                }
            }

        }

        public function actualizacionDiaria() {
            $lastUpdateTime = date("Y-m-d");

            // Con este decidimos si deseamos consultar directo a la API o en su lugar utilizar respuestas descargadas
            $src = "web";
            $urlConsulta = ($src == "file") ? "./consultas_guardadas/lastUpdateTime_$lastUpdateTime.txt" : "hotel-content-api/1.0/hotels?fields=all&lastUpdateTime=$lastUpdateTime&language=CAS&from=1&to=1000&useSecondaryLanguage=true";

            $qryHotels = $this->obtenerHoteles($src, "GET", $urlConsulta, 10);

            // En este ciclo lo que se hace es guardar los datos base de los hoteles para obtener su ID e ir comenzando a llenar las tablas secundarias
            for ($i=0, $l = count($qryHotels["hotels"]); $i < $l; $i++) { 
                $hotelActual = $qryHotels["hotels"][$i]["code"];
                echo "Hotel con codigo {$hotelActual} actualizado... <br>"; 

                try {
                    [$code, $name, $description, $countryCode, $stateCode,$destinationCode,
                    $categoryCode, $categoryGroupCode, $chainCode, $accommodationTypeCode,
                    $email, $license, $S2C, $web, $ranking] = $this->variables_T_Hotel_Description($qryHotels["hotels"][$i]);

                    $qrySelect = "SELECT * FROM `T_Hotel_Description` WHERE `code` = ?";
                    $arreglo = [$code];

                    $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                    if (count($resSelect) > 0) {
                        // Se actualizan los registros en la base de datos
                        $qry = "UPDATE `T_Hotel_Description` SET    `name`                  = ?,
                                                                    `description`           = ?,
                                                                    `countryCode`           = ?,
                                                                    `stateCode`             = ?,
                                                                    `destinationCode`       = ?,
                                                                    `categoryCode`          = ?,
                                                                    `categoryGroupCode`     = ?,
                                                                    `chainCode`             = ?,
                                                                    `accommodationTypeCode` = ?,
                                                                    `email`                 = ?,
                                                                    `license`               = ?,
                                                                    `S2C`                   = ?,
                                                                    `web`                   = ?,
                                                                    `ranking`               = ?
                                                            WHERE   `code`                  = ?";
                                                    
                        $arreglo = [$name, $description, $countryCode, $stateCode, $destinationCode,
                                    $categoryCode, $categoryGroupCode, $chainCode, $accommodationTypeCode,
                                    $email, $license, $S2C, $web, $ranking, $code];

                        // $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                    } else {
                        $arreglo = [$code, $name, $description, $countryCode, $stateCode, $destinationCode,
                                    $categoryCode, $categoryGroupCode, $chainCode, $accommodationTypeCode,
                                    $email, $license, $S2C, $web, $ranking];

                        $this->crear_T_Hotel_Description($arreglo);
                    }

                    $id_T_Hotel_Description = $this->mysql->consulta($this->prepararConsulta("SELECT id FROM `T_Hotel_Description` WHERE `code` = ?", [$code]))["id"][0];

                    // En esta parte se llena la tabla de T_Hotel_Address
                    if (true) {
                        [$content, $street, $number, $postalCode,$city] = $this->variables_T_Hotel_Address($qryHotels["hotels"][$i]);
                        
                        $id_hotel_description = $id_T_Hotel_Description;

                        $qrySelect = "SELECT * FROM `T_Hotel_Address` WHERE `id_hotel_description` = ?";
                        $arreglo = [$id_hotel_description];

                        $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                        if (count($resSelect) > 0) {
                            // Se actualizan los registros en la base de datos
                            $qry = "UPDATE `T_Hotel_Address` SET    `content`               = ?,
                                                                    `street`                = ?,
                                                                    `number`                = ?,
                                                                    `postalCode`            = ?,
                                                                    `city`                  = ?
                                                            WHERE   `id_hotel_description`  = ?";

                            $arreglo = [$content, $street, $number, $postalCode, $city, $id_hotel_description];
        
                            // $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                        } else {
                            $arreglo = [$content, $street, $number, $postalCode, $city, $id_hotel_description];

                            $this->crear_T_Hotel_Address($arreglo);
                        }
                    }
    
                    // En esta parte se llena la tabla de T_Hotel_Contact
                    if (true) {
                        [$phoneHotel, $phoneManagement, $phoneFaxnumber] = $this->variables_T_Hotel_Contact($qryHotels["hotels"][$i]);
                            
                        $id_hotel_description = $id_T_Hotel_Description;

                        $qrySelect = "SELECT * FROM `T_Hotel_Contact` WHERE `id_hotel_description` = ?";
                        $arreglo = [$id_hotel_description];

                        $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                        if (count($resSelect) > 0) {
                            // Se actualizan los registros en la base de datos
                            $qry = "UPDATE `T_Hotel_Contact` SET    `phoneHotel`            = ?,
                                                                    `phoneManagement`       = ?,
                                                                    `phoneFaxnumber`        = ?
                                                            WHERE   `id_hotel_description`  = ?";
    
                            $arreglo = [$phoneHotel, $phoneManagement, $phoneFaxnumber, $id_hotel_description];
        
                            // $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                        } else {
                            $arreglo = [$phoneHotel, $phoneManagement, $phoneFaxnumber, $id_hotel_description];

                            $this->crear_T_Hotel_Contact(($arreglo));
                        }
                        
                    }
    
                    // En esta parte se llena la tabla de T_Hotel_Coordinates
                    if (true) {
                        [$longitude, $latitude] = $this->variables_T_Hotel_Coordinates($qryHotels["hotels"][$i]["coordinates"]);
    
                        $id_hotel_description = $id_T_Hotel_Description;

                        $qrySelect = "SELECT * FROM `T_Hotel_Coordinates` WHERE `id_hotel_description` = ?";
                        $arreglo = [$id_hotel_description];

                        $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                        if (count($resSelect) > 0) {
                            // Se actualizan los registros en la base de datos
                            $qry = "UPDATE `T_Hotel_Coordinates` SET    `longitude`             = ?,
                                                                        `latitude`              = ?
                                                                WHERE   `id_hotel_description`  = ?";
                            
                            $arreglo = [$longitude, $latitude, $id_hotel_description];
        
                            // $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                        } else {
                            $arreglo = [$longitude, $latitude, $id_hotel_description];

                            $this->crear_T_Hotel_Coordinates(($arreglo));
                        }
                    }

                    // En este ciclo se llena la tabla de T_Facilities_Hotel_Description
                    if (array_key_exists("facilities", $qryHotels["hotels"][$i])) {
                        for ($j=0, $m = count($qryHotels["hotels"][$i]["facilities"]); $j < $m; $j++) { 
                            [$facilityCode, $facilityGroupCode, $order,
                            $indYesOrNo,$number, $voucher, $indLogic,
                            $indFee, $distance, $timeFrom, $timeTo, $dateTo] = $this->variables_T_Facilities_Hotel_Description($qryHotels["hotels"][$i]["facilities"][$j]);
                            
                            $id_hotel_description   = $id_T_Hotel_Description;

                            $qrySelect = "SELECT * FROM `T_Facilities_Hotel_Description` WHERE `id_hotel_description` = ? AND `facilityCode` = ? AND `facilityGroupCode` = ?";
                            $arreglo = [$id_hotel_description, $facilityCode, $facilityGroupCode];

                            $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                            if (count($resSelect) > 0) {
                                // Se actualizan los registros en la base de datos
                                $qry = "UPDATE `T_Facilities_Hotel_Description` SET     `order`                 = ?,
                                                                                        `number`                = ?,
                                                                                        `indYesOrNo`            = ?,
                                                                                        `voucher`               = ?,
                                                                                        `indLogic`              = ?,
                                                                                        `indFee`                = ?,
                                                                                        `distance`              = ?,
                                                                                        `timeFrom`              = ?,
                                                                                        `timeTo`                = ?,
                                                                                        `dateTo`                = ?
                                                                                WHERE   `id_hotel_description`  = ? AND `facilityCode` = ? AND `facilityGroupCode` = ?";
                                
                                $arreglo = [$order, $number, $indYesOrNo, $voucher, $indLogic,
                                            $indFee, $distance, $timeFrom, $timeTo, $dateTo,
                                            $id_hotel_description, $facilityCode, $facilityGroupCode];
            
                                // $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                            } else {
                                $arreglo = [$facilityCode, $facilityGroupCode, $order,
                                            $number, $indYesOrNo, $voucher, $indLogic,
                                            $indFee, $distance, $timeFrom, $timeTo,
                                            $dateTo, $id_hotel_description];
            
                                $this->crear_T_Facilities_Hotel_Description($arreglo);
                            }
        
                        }
                    }

                    // En este ciclo se llena la tabla de T_InterestPoint_Hotel
                    if (array_key_exists("interestPoints", $qryHotels["hotels"][$i])) {
                        for ($j=0, $m = count($qryHotels["hotels"][$i]["interestPoints"]); $j < $m; $j++) { 
                            [$facilityCode, $facilityGroupCode, $order, $poiName, $distance] = $this->variables_T_InterestPoint_Hotel($qryHotels["hotels"][$i]["interestPoints"][$j]);
                            
                            $id_hotel_description   = $id_T_Hotel_Description;

                            $qrySelect = "SELECT * FROM `T_InterestPoint_Hotel` WHERE `id_hotel_description` = ? AND `facilityCode` = ? AND `facilityGroupCode` = ?";
                            $arreglo = [$id_hotel_description, $facilityCode, $facilityGroupCode];

                            $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                            if (count($resSelect) > 0) {
                                // Se actualizan los registros en la base de datos
                                $qry = "UPDATE `T_InterestPoint_Hotel` SET      `order`                 = ?,
                                                                                `poiName`               = ?,
                                                                                `distance`              = ?
                                                                        WHERE   `id_hotel_description`  = ? AND `facilityCode` = ? AND `facilityGroupCode` = ?";
    
                                $arreglo = [$order, $poiName, $distance, 
                                            $id_hotel_description, $facilityCode, $facilityGroupCode];
            
                                // $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                            } else {
                                $arreglo = [$facilityCode, $facilityGroupCode, $order,
                                            $poiName, $distance, $id_hotel_description];
            
                                $this->crear_T_InterestPoint_Hotel($arreglo);
                            }
                        }
                    }
                    
                    // En este ciclo se llena la tabla de T_Img_Hotel_Room
                    if (array_key_exists("images", $qryHotels["hotels"][$i])) {
                        for ($j=0, $m = count($qryHotels["hotels"][$i]["images"]); $j < $m; $j++) { 
                            [$imageTypeCode, $path, $order, $visualOrder,
                            $roomCode, $roomType, $characteristicCode] = $this->variables_T_Img_Hotel_Room($qryHotels["hotels"][$i]["images"][$j]);
                            
                            $id_hotel_description    = $id_T_Hotel_Description;

                            $qrySelect = "SELECT * FROM T_Img_Hotel_Room WHERE `id_hotel_description`  = ? AND `visualOrder` = ?";
                            $arreglo = [$id_hotel_description, $visualOrder];

                            $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                            if (count($resSelect) > 0) {
                                // Se actualizan los registros en la base de datos
                                $qry = "UPDATE `T_Img_Hotel_Room` SET   `order`                 = ?,
                                                                        `imageTypeCode`         = ?,
                                                                        `path`                  = ?,
                                                                        `roomCode`              = ?,
                                                                        `roomType`              = ?,
                                                                        `characteristicCode`    = ?
                                                                WHERE `id_hotel_description`  = ? AND `visualOrder` = ?";

                                $arreglo = [$order, $imageTypeCode, $path, $roomCode, $roomType, $characteristicCode, $id_hotel_description, $visualOrder];

                                // $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                            } else {
                                $arreglo = [$imageTypeCode, $path, $order , $visualOrder, $roomCode, $roomType, $characteristicCode, $id_hotel_description];
            
                                $this->crear_T_Img_Hotel_Room($arreglo);
                            }    
                        }
                    }
                    
                    // En este ciclo se llena la tabla de T_Hotel_Boards
                    if (array_key_exists("boardCodes", $qryHotels["hotels"][$i])) {
                        for ($j=0, $m = count($qryHotels["hotels"][$i]["boardCodes"]); $j < $m; $j++) { 
                            $code = isset($qryHotels["hotels"][$i]["boardCodes"][$j]) ? $qryHotels["hotels"][$i]["boardCodes"][$j]  : "";
                            
                            $id_hotel_description    = $id_T_Hotel_Description;

                            $qrySelect = "SELECT * FROM T_Hotel_Boards WHERE `id_hotel_description`  = ? AND `code` = ?";
                            $arreglo = [$id_hotel_description, $code];

                            $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                            if (count($resSelect) > 0) {
                                // Se actualizan los registros en la base de datos
                                $qry = "UPDATE `T_Hotel_Boards` SET     `code`                  = ?
                                                                WHERE   `id_hotel_description`  = ?)";
                                
                                $arreglo = [$code, $id_hotel_description];

                                // $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                            } else {
                                $arreglo = [$code, $id_hotel_description];
            
                                $this->crear_T_Hotel_Boards($arreglo);
                            }                            
                        }
                    }
                    
                    // En este ciclo se llena la tabla de T_Hotel_Segment
                    if (array_key_exists("segmentCodes", $qryHotels["hotels"][$i])) {
                        for ($j=0, $m = count($qryHotels["hotels"][$i]["segmentCodes"]); $j < $m; $j++) { 
                            $segmentCode = isset($qryHotels["hotels"][$i]["segmentCodes"][$j]) ? $qryHotels["hotels"][$i]["segmentCodes"][$j]  : "";
                            
                            $id_hotel_description    = $id_T_Hotel_Description;

                            $qrySelect = "SELECT * FROM T_Hotel_Segment WHERE `id_hotel_description`  = ? AND `segmentCode` = ?";
                            $arreglo = [$id_hotel_description, $segmentCode];

                            $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                            if (count($resSelect) > 0) {
                                // Se actualizan los registros en la base de datos
                                $qry = "UPDATE `T_Hotel_Segment` SET  `segmentCode`          = ?

                                                                WHERE `id_hotel_description   = ?)";
                            
                                $arreglo = [$segmentCode, $id_hotel_description];

                                // $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                            } else {
                                $arreglo = [$segmentCode, $id_hotel_description];
            
                                $this->crear_T_Hotel_Segment($arreglo);
                            }           
                        }
                    }
                    
                    // En este ciclo se llena la tabla de T_Hotel_Terminals
                    if (array_key_exists("terminals", $qryHotels["hotels"][$i])) {
                        for ($j=0, $m = count($qryHotels["hotels"][$i]["terminals"]); $j < $m; $j++) { 
                            $terminalCode   = isset($qryHotels["hotels"][$i]["terminals"][$j]["terminalCode"])  ? $qryHotels["hotels"][$i]["terminals"][$j]["terminalCode"]     : "";
                            $distance       = isset($qryHotels["hotels"][$i]["terminals"][$j]["distance"])      ? $qryHotels["hotels"][$i]["terminals"][$j]["distance"]         : "";
                            
                            $id_hotel_description    = $id_T_Hotel_Description;

                            $qrySelect = "SELECT * FROM T_Hotel_Terminals WHERE `id_hotel_description`  = ? AND `terminalCode` = ?";
                            $arreglo = [$id_hotel_description, $terminalCode];

                            $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                            if (count($resSelect) > 0) {
                                // Se actualizan los registros en la base de datos
                                $qry = "UPDATE `T_Hotel_Terminals` SET  `distance` = ?
                                                                   WHERE `id_hotel_description` = ? AND `terminalCode` = ?";
                                        
                                $arreglo = [$distance, $id_hotel_description, $terminalCode];
            
                                // $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                            } else {
                                $arreglo = [$terminalCode, $distance, $id_hotel_description];
            
                                $this->crear_T_Hotel_Terminals($arreglo);
                            }
                        }
                    }
                    
                    // En este ciclo se llena la tabla de T_Hotel_Issues
                    if (array_key_exists("issues", $qryHotels["hotels"][$i])) {
                        for ($j=0, $m = count($qryHotels["hotels"][$i]["issues"]); $j < $m; $j++) { 
                            [$issueCode, $issueType, $dateFrom, $dateTo, $order, $alternative] = $this->variables_T_Hotel_Issues($qryHotels["hotels"][$i]["issues"][$j]);
                            
                            $id_hotel_description    = $id_T_Hotel_Description;

                            $qrySelect = "SELECT * FROM T_Hotel_Issues WHERE `id_hotel_description`  = ? AND `issueCode` = ? AND `issueType` = ?";
                            $arreglo = [$id_hotel_description, $issueCode, $issueType];

                            $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                            if (count($resSelect) > 0) {
                                // Se actualizan los registros en la base de datos
                                $qry = "UPDATE `T_Hotel_Issues` SET     `dateFrom`              = ?,
                                                                        `dateTo`                = ?,
                                                                        `order`                 = ?,
                                                                        `alternative`           = ?
                                                                WHERE   `id_hotel_description`  = ? AND `issueCode` = ? AND `issueType` = ?";
                            
                                $arreglo = [$dateFrom, $dateTo, $order, $alternative, $id_hotel_description, $issueCode, $issueType];

                                // $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                            } else {
                                $arreglo = [$issueCode, $issueType, $dateFrom, $dateTo, $order, $alternative, $id_hotel_description];
            
                                $this->crear_T_Hotel_Issues($arreglo);
                            } 
                        }
                    }

                    // En este ciclo se llena la tabla de T_Hotel_Rooms
                    if (array_key_exists("rooms", $qryHotels["hotels"][$i])) {
                        for ($j=0, $m = count($qryHotels["hotels"][$i]["rooms"]); $j < $m; $j++) { 
                            [$roomCode, $isParentRoom, $minPax, $maxPax, $maxAdults,$maxChildren, $minAdults, $roomType, $characteristicCode] = 
                            $this->variables_T_Hotel_Rooms($qryHotels["hotels"][$i]["rooms"][$j]);
                            
                            $id_hotel_description    = $id_T_Hotel_Description;

                            $qrySelect = "SELECT * FROM T_Hotel_Rooms WHERE `id_hotel_description`  = ? AND `roomCode` = ?";
                            $arreglo = [$id_hotel_description, $roomCode];

                            $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                            if (count($resSelect) > 0) {
                                // Se actualizan los registros en la base de datos
                                $qry = "UPDATE T_Hotel_Rooms SET    isParentRoom = ?,
                                                                    minPax = ?,
                                                                    maxPax = ?,
                                                                    maxAdults = ?,
                                                                    maxChildren = ?,
                                                                    minAdults = ?,
                                                                    roomType = ?,
                                                                    characteristicCode = ?
                                                            WHERE   id_hotel_description = ? AND roomCode = ?";

                                $arreglo = [$isParentRoom, $minPax, $maxPax, $maxAdults, $maxChildren, $minAdults, $roomType, $characteristicCode, $id_hotel_description, $roomCode];
            
                                $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                            } else {                                
                                $arreglo = [$roomCode, $isParentRoom, $minPax,
                                            $maxPax, $maxAdults, $maxChildren,
                                            $minAdults, $roomType, $characteristicCode,
                                            $id_hotel_description];
            
                                $this->crear_T_Hotel_Rooms($arreglo);
                            }
                            
                            $id_T_Hotel_Rooms = $this->mysql->consulta($this->prepararConsulta("SELECT id FROM `T_Hotel_Rooms` WHERE id_hotel_description = ? AND roomCode = ?", [$id_hotel_description, $roomCode]))["id"][0];

                            // Ahora llenamos la tabla de tercer nivel (T_Room_Facilities) con el id que acabamos de crear ($id_T_Hotel_Rooms)
                            if (array_key_exists("roomFacilities", $qryHotels["hotels"][$i]["rooms"][$j])) {
                                for ($k=0, $n = count($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"]); $k < $n; $k++) { 
                                    $facilityCode       = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["facilityCode"])         ? $qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["facilityCode"]       : "";
                                    $facilityGroupCode  = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["facilityGroupCode"])    ? $qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["facilityGroupCode"]  : "";
                                    $indLogic           = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["indLogic"])             ? $qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["indLogic"]           : "";
                                    $number             = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["number"])               ? $qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["number"]             : "";
                                    $voucher            = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["voucher"])              ? $qryHotels["hotels"][$i]["rooms"][$j]["roomFacilities"][$k]["voucher"]            : "";
        
                                    $id_hotel_room    = $id_T_Hotel_Rooms;

                                    $qrySelect = "SELECT * FROM T_Room_Facilities WHERE `id_hotel_room`  = ? AND `facilityCode` = ? AND `facilityGroupCode` = ?";
                                    $arreglo = [$id_hotel_room, $facilityCode, $facilityGroupCode];

                                    $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                                    if (count($resSelect) > 0) {
                                        // Se actualizan los registros en la base de datos
                                        $qry = "UPDATE T_Room_Facilities SET    indLogic            = ?,
                                                                                number              = ?,
                                                                                voucher             = ?
                                                                        WHERE   id_hotel_room       = ? AND facilityCode        = ? AND facilityGroupCode   = ?";
                                        $arreglo = [$indLogic, $number, $voucher, $id_hotel_room, $facilityCode, $facilityGroupCode];
            
                                        $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                                    } else {
                                        $qry = "INSERT INTO T_Room_Facilities (facilityCode, facilityGroupCode, indLogic, number, voucher, id_hotel_room) VALUES (?, ?, ?, ?, ?, ?)";
                                        $arreglo = [$facilityCode, $facilityGroupCode, $indLogic, $number, $voucher, $id_hotel_room];
            
                                        $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                                    }
                                }
                            }
        
                            // Ahora llenamos la tabla de tercer nivel (T_Room_Stay) con el id que acabamos de crear ($id_T_Hotel_Rooms)
                            if (array_key_exists("roomStays", $qryHotels["hotels"][$i]["rooms"][$j])) {
                                for ($k=0, $n = count($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"]); $k < $n; $k++) { 
                                    $stayType        = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["stayType"])     ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["stayType"]     : "";
                                    $order           = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["order"])        ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["order"]        : "";
                                    $description     = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["description"])  ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["description"]  : "";
        
                                    $id_hotel_room    = $id_T_Hotel_Rooms;

                                    $qrySelect = "SELECT * FROM T_Room_Stay WHERE `id_hotel_room`  = ? AND `stayType` = ? AND `order` = ?";
                                    $arreglo = [$id_hotel_room, $stayType, $order];

                                    $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                                    if (count($resSelect) > 0) {
                                        // Se actualizan los registros en la base de datos
                                        $qry = "UPDATE T_Room_Stay SET      `description` = ?
                                                                   WHERE    `id_hotel_room`  = ? AND `stayType` = ? AND `order` = ?";
                                        $arreglo = [$description, $id_hotel_room, $stayType, $order];
            
                                        $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                                    } else {
                                        $qry = "INSERT INTO T_Room_Stay (stayType, `order`, description, id_hotel_room) VALUES (?, ?, ?, ?)";
                                        $arreglo = [$stayType, $order, $description, $id_hotel_room];
            
                                        $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                                    }

                                    $id_T_Room_Stay = $this->mysql->consulta($this->prepararConsulta("SELECT id FROM `T_Room_Stay` WHERE `id_hotel_room`  = ? AND `stayType` = ? AND `order` = ?", [$id_hotel_room, $stayType, $order]))["id"][0];
        
                                    // Ahora llenamos la tabla de cuarto nivel (T_Room_Stay_Facilities) con el id que acabamos de crear ($id_T_Room_Stay)
                                    if (array_key_exists("roomStayFacilities", $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k])) {
                                        for ($i_a=0, $l_a = count($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"]); $i_a < $l_a; $i_a++) {
                                            $facilityCode       = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["facilityCode"])       ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["facilityCode"]         : "";
                                            $facilityGroupCode  = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["facilityGroupCode"])  ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["facilityGroupCode"]    : "";
                                            $number             = isset($qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["number"])             ? $qryHotels["hotels"][$i]["rooms"][$j]["roomStays"][$k]["roomStayFacilities"][$i_a]["number"]               : "";
        
                                            $id_room_stay = $id_T_Room_Stay;

                                            $qrySelect = "SELECT * FROM T_Room_Stay_Facilities WHERE `id_room_stay`  = ? AND `facilityCode` = ? AND `facilityGroupCode` = ?";
                                            $arreglo = [$id_room_stay, $facilityCode, $facilityGroupCode];

                                            $resSelect = $this->mysql->consulta($this->prepararConsulta($qrySelect, $arreglo));

                                            if (count($resSelect) > 0) {
                                                // Se actualizan los registros en la base de datos
                                                $qry = "UPDATE T_Room_Stay_Facilities SET   `number` = ?
                                                                                    WHERE   `id_room_stay`  = ? AND `facilityCode` = ? AND `facilityGroupCode` = ?";
                                                $arreglo = [$number, $id_room_stay, $facilityCode, $facilityGroupCode];
            
                                                $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                                            } else {
                                                $qry = "INSERT INTO T_Room_Stay_Facilities (facilityCode, facilityGroupCode, number, id_room_stay) VALUES (?, ?, ?, ?)";
                                                $arreglo = [$facilityCode, $facilityGroupCode, $number, $id_room_stay  ];
            
                                                $this->mysql->ejecuta($this->prepararConsulta($qry, $arreglo));
                                            }
                                        }
                                    } 
                                }
                            }
                        }
                    }
                    
                } catch (\Exception $ex) {
                    echo $ex->getMessage();
                }
            }
        }

        // Esta función retorna la lista de hoteles y puede ser mediante consultas web o mediante la lectura de ficheros que ya contengan la respuesta de la consulta
        public function obtenerHoteles ($source = "file", $metodo = "GET", $urlConsulta = "", $timeOut = 60, $postFields = null) {
            if ($source == "file") {
                $respuesta = file_get_contents($urlConsulta);
            } else if ($source == "web") {
                $respuesta = $this->ejecutarConsultaHotelBeds($metodo, $urlConsulta, $timeOut, $postFields);
            }

            $respuesta = json_decode($respuesta, true);

            return $respuesta;
        }

        public function ejecutarConsultaHotelBeds($metodo, $url, $timeOut = 60, $postFields = null) {
            try {
                // Signature is generated by SHA256 (Api-Key + Secret + Timestamp (in seconds))
                $signature = hash("sha256", $this->apiKey.$this->secret.time());
                
                $curlArray = array(
                    CURLOPT_URL => $this->endpoint . $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => $timeOut,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => $metodo,
                    CURLOPT_HTTPHEADER => array(
                        "accept: application/json",
                        "accept-encoding: gzip",
                        "api-key: ". $this->apiKey ."",
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "x-signature: ". $signature .""
                    ),
                );

                if ($metodo == "POST") {
                    $curlArray[CURLOPT_POSTFIELDS] = $postFields;
                }

                // Iniciamos la libreria de CURL
                $curl = curl_init();
                curl_setopt_array($curl, $curlArray);
            
                // Ejecutamos la solicitud
                $response = curl_exec($curl);

                // En caso de error regresamos el nombre del mismo
                $err = curl_error($curl);
                
                if ($err) {
                    $response = "cURL Error #:" . $err;
                }

            } catch (\Exception $ex) {
                    $response = $ex->getMessage();
            }       

            return $response;
        }
    }

    $cron = new Hoteles;

    // El siguiente metodo se utiliza para guardar los hoteles (Solo usado en la carga inicial de hoteles)
    // Si se usa con hoteles que ya existen los registros se repetiran
    // TODO: Corregir que no se pueda insertar hoteles que ya existen (Mediante el code)
    // $cron->guardarHoteles();

    // El siguiente metodo actualiza o crea los hoteles devueltos por la API diariamente con el parametro 'lastUpdateTime'
    // Este metodo tarda mas que el simple guardado de hoteles ya que primero consulta si los registros existen y dependiendo de la respuesta
    // actualiza o crea los valores, y esto hace que el tiempo de ejecucion dependa tanto del tiempo de consulta como del de creacion de registros
    $cron->actualizacionDiaria();
?>
