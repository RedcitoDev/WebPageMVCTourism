<?php
namespace Classes;

use Classes\funciones;

class typesHotel extends mysqlconsultas {
    
    public function Facilities($responseFacilities){
        $json_decode = json_decode($responseFacilities,true);
        $fn = new funciones();
        if(array_key_exists('facilities', $json_decode)){
            
            for($i=0;$i<count($json_decode['facilities']);$i++){
                $sqlFacilities = "INSERT INTO T_Facilities (code,facilityGroupCode,facilityTypologyCode,content) VALUES (?,?,?,?)";

                $code =                 isset($json_decode['facilities'][$i]['code'])                   ? $json_decode['facilities'][$i]['code']                    : "";
                $facilityGroupCode =    isset($json_decode['facilities'][$i]['facilityGroupCode'])      ? $json_decode['facilities'][$i]['facilityGroupCode']       : "";
                $facilityTypologyCode = isset($json_decode['facilities'][$i]['facilityTypologyCode'])   ? $json_decode['facilities'][$i]['facilityTypologyCode']    : "";
                $content =              isset($json_decode['facilities'][$i]['description']['content']) ? $json_decode['facilities'][$i]['description']['content']  : "";
                
                $arreglo = [$code,$facilityGroupCode,$facilityTypologyCode,$content];

                $consult_scape = $fn->prepararConsulta($sqlFacilities, $arreglo);
                
                $res = $this->ejecuta($consult_scape);
            }
        }else{

        }
        return $res;
    }
    public function FacilitiesGruop($responseFacilitieGroup){
        $json_decode = json_decode($responseFacilitieGroup,true);
        var_dump($json_decode);
        $fn = new funciones();
        if(array_key_exists('facilityGroups', $json_decode)){
            for($i=0;$i<count($json_decode['facilityGroups']);$i++){
                $sqlFacilitiesGruop = "INSERT INTO T_Facilities_Group (code,description) VALUES (?,?)";

                $code =         isset($json_decode['facilityGroups'][$i]['code'])                    ? $json_decode['facilityGroups'][$i]['code']                    : "";
                $description =  isset($json_decode['facilityGroups'][$i]['description']['content'])  ? $json_decode['facilityGroups'][$i]['description']['content']  : "";

                $arreglo = [$code,$description];

                $consult_scape = $fn->prepararConsulta($sqlFacilitiesGruop, $arreglo);

                $res = $this->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function FacilitiesTypologies($responseFacilitiesTypologies){
        $json_decode = json_decode($responseFacilitiesTypologies,true);
        var_dump($json_decode);
        $fn = new funciones();
        if(array_key_exists('facilityTypologies', $json_decode)){
            for($i=0;$i<count($json_decode['facilityTypologies']);$i++){
                $sqlFacilitiesTypologies = "INSERT INTO T_Facilities_Typologies (code,numberFlag,logicFlag,feeFlag,distanceFlag,ageFromFlag,ageToFlag,dateFromFlag,dateToFlag,timeFromFlag,timeToFlag,indYesOrNoFlag,amountFlag,currencyFlag,appTypeFlag,textFlag) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                
                $code =             isset($json_decode['facilityTypologies'][$i]['code'])           ? $json_decode['facilityTypologies'][$i]['code']            : "";
                $numberFlag =       isset($json_decode['facilityTypologies'][$i]['numberFlag'])     ? $json_decode['facilityTypologies'][$i]['numberFlag']      : "";
                $logicFlag =        isset($json_decode['facilityTypologies'][$i]['logicFlag'])      ? $json_decode['facilityTypologies'][$i]['logicFlag']       : "";
                $feeFlag =          isset($json_decode['facilityTypologies'][$i]['feeFlag'])        ? $json_decode['facilityTypologies'][$i]['feeFlag']         : "";
                $distanceFlag =     isset($json_decode['facilityTypologies'][$i]['distanceFlag'])   ? $json_decode['facilityTypologies'][$i]['distanceFlag']    : "";
                $ageFromFlag =      isset($json_decode['facilityTypologies'][$i]['ageFromFlag'])    ? $json_decode['facilityTypologies'][$i]['ageFromFlag']     : "";
                $ageToFlag =        isset($json_decode['facilityTypologies'][$i]['ageToFlag'])      ? $json_decode['facilityTypologies'][$i]['ageToFlag']       : "";
                $dateFromFlag =     isset($json_decode['facilityTypologies'][$i]['dateFromFlag'])   ? $json_decode['facilityTypologies'][$i]['dateFromFlag']    : "";
                $dateToFlag =       isset($json_decode['facilityTypologies'][$i]['dateToFlag'])     ? $$json_decode['facilityTypologies'][$i]['dateToFlag']     : "";
                $timeFromFlag =     isset($json_decode['facilityTypologies'][$i]['timeFromFlag'])   ? $json_decode['facilityTypologies'][$i]['timeFromFlag']    : "";
                $timeToFlag =       isset($json_decode['facilityTypologies'][$i]['timeToFlag'])     ? $json_decode['facilityTypologies'][$i]['timeToFlag']      : "";
                $indYesOrNoFlag =   isset($json_decode['facilityTypologies'][$i]['indYesOrNoFlag']) ? $json_decode['facilityTypologies'][$i]['indYesOrNoFlag']  : "";
                $amountFlag =       isset($json_decode['facilityTypologies'][$i]['amountFlag'])     ? $json_decode['facilityTypologies'][$i]['amountFlag']      : "";
                $currencyFlag =     isset($json_decode['facilityTypologies'][$i]['currencyFlag'])   ? $json_decode['facilityTypologies'][$i]['currencyFlag']    : "";
                $appTypeFlag =      isset($json_decode['facilityTypologies'][$i]['appTypeFlag'])    ? $json_decode['facilityTypologies'][$i]['appTypeFlag']     : "";
                $textFlag =         isset($json_decode['facilityTypologies'][$i]['textFlag'])       ? $json_decode['facilityTypologies'][$i]['textFlag']        : "";
                
                $arreglo = [$code,$numberFlag,$logicFlag,$feeFlag,$distanceFlag,$ageFromFlag,$ageToFlag,$dateFromFlag,$dateToFlag,$timeFromFlag,$timeToFlag,$indYesOrNoFlag,$amountFlag,$currencyFlag,$appTypeFlag,$textFlag];
                
                $consult_scape = $fn->prepararConsulta($sqlFacilitiesTypologies, $arreglo);
                    
                $res = $this->ejecuta($consult_scape);
            }
        }else{

        }
        return $res;
    }
    public function Rooms($responseRooms){
        $json_decode = json_decode($responseRooms,true);
        $fn = new funciones();
        if(array_key_exists('rooms', $json_decode)){
            for($i=0;$i<count($json_decode['rooms']);$i++){
                $sqlRooms = "INSERT INTO T_Rooms (code,type,characteristic,minPax,maxPax,maxAdults,maxChildren,minAdults,description,typeDescription,characteristicDescription) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

                $code =                         isset($json_decode['rooms'][$i]['code'])                                    ? $json_decode['rooms'][$i]['code']                                 : "";
                $type =                         isset($json_decode['rooms'][$i]['type'])                                    ? $json_decode['rooms'][$i]['type']                                 : "";
                $characteristic =               isset($json_decode['rooms'][$i]['characteristic'])                          ? $json_decode['rooms'][$i]['characteristic']                       : "";
                $minPax =                       isset($json_decode['rooms'][$i]['minPax'])                                  ? $json_decode['rooms'][$i]['minPax']                               : "";
                $maxPax =                       isset($json_decode['rooms'][$i]['maxPax'])                                  ? $json_decode['rooms'][$i]['maxPax']                               : "";
                $maxAdults =                    isset($json_decode['rooms'][$i]['maxAdults'])                               ? $json_decode['rooms'][$i]['maxAdults']                            : "";
                $maxChildren =                  isset($json_decode['rooms'][$i]['maxChildren'])                             ? $json_decode['rooms'][$i]['maxChildren']                          : "";
                $minAdults =                    isset($json_decode['rooms'][$i]['minAdults'])                               ? $json_decode['rooms'][$i]['minAdults']                            : "";
                $description =                  isset($json_decode['rooms'][$i]['description'])                             ? $json_decode['rooms'][$i]['description']                          : "";
                $typeDescription =              isset($json_decode['rooms'][$i]['typeDescription']['content'])              ? $json_decode['rooms'][$i]['typeDescription']['content']           : "";
                $characteristicDescription =    isset($json_decode['rooms'][$i]['characteristicDescription']['content'])    ? $json_decode['rooms'][$i]['characteristicDescription']['content'] : "";
                    
                $arreglo = [$code,$type,$characteristic,$minPax,$maxPax,$maxAdults,$maxChildren,$minAdults,$description,$typeDescription,$characteristicDescription];
                
                $consult_scape = $fn->prepararConsulta($sqlRooms, $arreglo);
                
                $res = $this->ejecuta($consult_scape);
            }
        }else{

        }
        return $res;
    }
    public function Promotions($responsePromotions){
        $json_decode = json_decode($responsePromotions,true);
        $fn = new funciones();
        if(array_key_exists('promotions', $json_decode)){
            for($i=0;$i<count($json_decode['promotions']);$i++){
                $sqlPromotions = "INSERT INTO T_Promotions (code,description,name) VALUES (?,?,?)";

                $code =        isset($json_decode['promotions'][$i]['code'])                    ? $json_decode['promotions'][$i]['code']                    : "";
                $description = isset($json_decode['promotions'][$i]['description']['content'])  ? $json_decode['promotions'][$i]['description']['content']  : "";
                $name =        isset($json_decode['promotions'][$i]['name']['content'])         ? $json_decode['promotions'][$i]['name']['content']         : "";
                
                $arreglo = [$code,$description,$name];

                $consult_scape = $fn->prepararConsulta($sqlPromotions, $arreglo);
                
                $res = $this->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function Accommodations($responseAccommodations){
        $json_decode = json_decode($responseAccommodations,true);
        $fn = new funciones();
        if(array_key_exists('accommodations', $json_decode)){
            for($i=0;$i<count($json_decode['accommodations']);$i++){
                $sqlaccommodations = "INSERT INTO T_Accommodations (`code`,typeMultiDescription,typeDescription) VALUES (?,?,?)";

                $code =        isset($json_decode['accommodations'][$i]['code'])                             ? $json_decode['accommodations'][$i]['code']                               : "";
                $description = isset($json_decode['accommodations'][$i]['typeMultiDescription']['content'])  ? $json_decode['accommodations'][$i]['typeMultiDescription']['content']    : "";
                $name =        isset($json_decode['accommodations'][$i]['typeDescription'])                  ? $json_decode['accommodations'][$i]['typeDescription']                    : "";
                
                $arreglo = [$code,$description,$name];

                $consult_scape = $fn->prepararConsulta($sqlaccommodations, $arreglo);
                
                $res = $this->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function Issues($responseIssues){
        $json_decode = json_decode($responseIssues,true);
        $fn = new funciones();
        if(array_key_exists('issues', $json_decode)){
            for($i=0;$i<count($json_decode['issues']);$i++){
                $sqlIssues = "INSERT INTO T_Issues (`code`,`type`,description,name,alternative) VALUES (?,?,?,?,?)";

                $code =        isset($json_decode['issues'][$i]['code'])                    ? $json_decode['issues'][$i]['code']                   : "";
                $type =        isset($json_decode['issues'][$i]['type'])                    ? $json_decode['issues'][$i]['type']                   : "";
                $description = isset($json_decode['issues'][$i]['description']['content'])  ? $json_decode['issues'][$i]['description']['content'] : "";
                $name =        isset($json_decode['issues'][$i]['name']['content'])         ? $json_decode['issues'][$i]['name']['content']        : "";
                $alternative = isset($json_decode['issues'][$i]['alternative'])             ? $json_decode['issues'][$i]['alternative']            : "";
                
                $arreglo = [$code,$type,$description,$name,$alternative];

                $consult_scape = $fn->prepararConsulta($sqlIssues, $arreglo);
                
                $res = $this->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function Segments($responseSegments){
        $json_decode = json_decode($responseSegments,true);
        $fn = new funciones();
        if(array_key_exists('segments', $json_decode)){
            for($i=0;$i<count($json_decode['segments']);$i++){
                $sqlSegments = "INSERT INTO T_Segments (`code`,description) VALUES (?,?)";

                $code =        isset($json_decode['segments'][$i]['code'])                    ? $json_decode['segments'][$i]['code']                   : "";
                $description = isset($json_decode['segments'][$i]['description']['content'])  ? $json_decode['segments'][$i]['description']['content'] : "";
                
                $arreglo = [$code,$description];

                $consult_scape = $fn->prepararConsulta($sqlSegments, $arreglo);
                
                $res = $this->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function Terminals($responseTerminals){
        $json_decode = json_decode($responseTerminals,true);
        $fn = new funciones();
        if(array_key_exists('terminals', $json_decode)){
            for($i=0;$i<count($json_decode['terminals']);$i++){
                $sqlTerminals = "INSERT INTO T_Terminals (`code`,type,country,name,description) VALUES (?,?,?,?,?)";

                $code =         isset($json_decode['terminals'][$i]['code'])                    ? $json_decode['terminals'][$i]['code']                   : "";
                $type =         isset($json_decode['terminals'][$i]['type'])                    ? $json_decode['terminals'][$i]['type']                   : "";
                $country =      isset($json_decode['terminals'][$i]['country'])                 ? $json_decode['terminals'][$i]['country']                : "";
                $name =         isset($json_decode['terminals'][$i]['name']['content'])         ? $json_decode['terminals'][$i]['name']['content']        : "";
                $description =  isset($json_decode['terminals'][$i]['description']['content'])  ? $json_decode['terminals'][$i]['description']['content'] : "";
                
                $arreglo = [$code,$type,$country,$name,$description];

                $consult_scape = $fn->prepararConsulta($sqlTerminals, $arreglo);
                
                $res = $this->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function Chains($responseChains){
        $json_decode = json_decode($responseChains,true);
        $fn = new funciones();
        if(array_key_exists('chains', $json_decode)){
            for($i=0;$i<count($json_decode['chains']);$i++){
                $sqlChains = "INSERT INTO T_Chains (`code`,description) VALUES (?,?)";

                $code =         isset($json_decode['chains'][$i]['code'])                    ? $json_decode['chains'][$i]['code']                   : "";
                $description =  isset($json_decode['chains'][$i]['description']['content'])  ? $json_decode['chains'][$i]['description']['content'] : "";
                
                $arreglo = [$code,$description];

                $consult_scape = $fn->prepararConsulta($sqlChains, $arreglo);
                
                $res = $this->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function Categories($responseCategories){
        $json_decode = json_decode($responseCategories,true);
        $fn = new funciones();
        if(array_key_exists('categories', $json_decode)){
            for($i=0;$i<count($json_decode['categories']);$i++){
                $sqlCategories = "INSERT INTO T_Categories (code,simpleCode,accommodationType,`group`,description) VALUES (?,?,?,?,?)";

                $code =                 isset($json_decode['categories'][$i]['code'])                    ? $json_decode['categories'][$i]['code']                    : "";
                $simpleCode =           isset($json_decode['categories'][$i]['simpleCode'])              ? $json_decode['categories'][$i]['simpleCode']              : "";
                $accommodationType =    isset($json_decode['categories'][$i]['accommodationType'])       ? $json_decode['categories'][$i]['accommodationType']       : "";
                $group =                isset($json_decode['categories'][$i]['group'])                   ? $json_decode['categories'][$i]['group']                   : "";
                $description =          isset($json_decode['categories'][$i]['description']['content'])  ? $json_decode['categories'][$i]['description']['content']  : "";

                
                $arreglo = [$code,$simpleCode,$accommodationType,$group,$description];

                $consult_scape = $fn->prepararConsulta($sqlCategories, $arreglo);
                
                $res = $this->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function CategoriesGroup($responseCategoriesGroup){
        $json_decode = json_decode($responseCategoriesGroup,true);
        var_dump($json_decode);
        $fn = new funciones();
        if(array_key_exists('groupCategories', $json_decode)){
            for($i=0;$i<count($json_decode['groupCategories']);$i++){
                $sqlCategoriesGroup = "INSERT INTO T_Group_Categories (code,`order`,name,description) VALUES (?,?,?,?)";

                $code =         isset($json_decode['groupCategories'][$i]['code'])                   ? $json_decode['groupCategories'][$i]['code']                   : "";
                $order =        isset($json_decode['groupCategories'][$i]['order'])                  ? $json_decode['groupCategories'][$i]['order']                  : "";
                $name =         isset($json_decode['groupCategories'][$i]['name']['content'])        ? $json_decode['groupCategories'][$i]['name']['content']        : "";
                $description =  isset($json_decode['groupCategories'][$i]['description']['content']) ? $json_decode['groupCategories'][$i]['description']['content'] :"";

                $arreglo = [$code,$order,$name,$description];
                
                $consult_scape = $fn->prepararConsulta($sqlCategoriesGroup, $arreglo);

                $res = $this->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function Countries($responseCountries){
        $json_decode = json_decode($responseCountries,true);
        $fn = new funciones();
        var_dump($json_decode);
        if(array_key_exists('countries', $json_decode)){
            for($i=0;$i<count($json_decode['countries']);$i++){
                $sqlCountries = "INSERT INTO T_Countries (code,isoCode,description) VALUES (?,?,?)";

                $code =         isset($json_decode['countries'][$i]['code'])                   ? $json_decode['countries'][$i]['code']                     : "";
                $isoCode =      isset($json_decode['countries'][$i]['isoCode'])                ? $json_decode['countries'][$i]['isoCode']                  : "";
                $description =  isset($json_decode['countries'][$i]['description']['content']) ? $json_decode['countries'][$i]['description']['content']   : "";
                
                $arreglo = [$code,$isoCode,$description];
                
                $consult_scape = $fn->prepararConsulta($sqlCountries, $arreglo);

                $res = $this->ejecuta($consult_scape);
                
                if(array_key_exists('states', $json_decode['countries'][$i])){
                    $estados = count($json_decode['countries'][$i]['states']);
                    for($a=0;$a<$estados;$a++){
                        $sqlStates = "INSERT INTO T_States(code,name,countryCode,id_country) VALUES(?, ?, ?,?)";

                        $code =         isset($json_decode['countries'][$i]['states'][$a]['code']) ? $json_decode['countries'][$i]['states'][$a]['code'] : "";
                        $name =         isset($json_decode['countries'][$i]['states'][$a]['name']) ? $json_decode['countries'][$i]['states'][$a]['name'] : "";
                        $countryCode =  isset($json_decode['countries'][$i]['code'])               ? $json_decode['countries'][$i]['code']               : "";
                        $idCountry =    isset($res)                                                ? $res                                                : "";
                        
                        $arreglo = [$code,$name,$countryCode,$idCountry];
                        
                        $consult_scape = $fn->prepararConsulta($sqlStates, $arreglo);
                        
                        $this->ejecuta($consult_scape);
                    }
                }else{
                    
                }
            }
        }else{
            echo "no exite";
        }
        return $res;
    }
    public function Destinations($responseDestinations){
        $json_decode = json_decode($responseDestinations,true);
        $fn = new funciones();
        var_dump($json_decode);
        if(array_key_exists('destinations', $json_decode)){
            for($i=0;$i<count($json_decode['destinations']);$i++){
                $sqlDestination = "INSERT INTO T_Destinations (code,name,countryCode,isoCode) VALUES (?,?,?,?)";

                $code  =        isset($json_decode['destinations'][$i]['code'])            ? $json_decode['destinations'][$i]['code']              : "";
                $name  =        isset($json_decode['destinations'][$i]['name']['content']) ? $json_decode['destinations'][$i]['name']['content']   : "";
                $countrycode  = isset($json_decode['destinations'][$i]['countryCode'])     ? $json_decode['destinations'][$i]['countryCode']       : "";
                $isoCode  =     isset($json_decode['destination']['isoCode'])              ? $json_decode['destination']['isoCode']                : "";

                $arreglo = [$code,$name,$countrycode,$isoCode];

                $consult_scape = $fn->prepararConsulta($sqlDestination, $arreglo);
                
                $res = $this->ejecuta($consult_scape);

                if(array_key_exists('zones', $json_decode['destinations'][$i])){
                    $zonas = count($json_decode['destinations'][$i]['zones']);
                    for($a=0;$a<$zonas;$a++){
                        $sqlZonas = "INSERT INTO T_Zonas_Destinations(zoneCode,name,description,destinationCode,id_destination) VALUES(?, ?, ?, ?, ?)";

                            $zoneCode =         isset($json_decode['destinations'][$i]['zones'][$a]['zoneCode'])                ? $json_decode['destinations'][$i]['zones'][$a]['zoneCode']               : "";
                            $name =             isset($json_decode['destinations'][$i]['zones'][$a]['name'])                    ? $json_decode['destinations'][$i]['zones'][$a]['name']                   : "";
                            $description =      isset($json_decode['destinations'][$i]['zones'][$a]['description']['content'])  ? $json_decode['destinations'][$i]['zones'][$a]['description']['content'] : "";
                            $destinationCode =  isset($json_decode['destinations'][$i]['code'])                                 ? $json_decode['destinations'][$i]['code']                                : "";
                            $idDestination =    isset($res)                                                                     ? $res                                                                    : "";

                            $arreglo = [$zoneCode,$name,$description,$destinationCode,$idDestination];
                            
                            $consult_scape = $fn->prepararConsulta($sqlZonas, $arreglo);
                            $this->ejecuta($consult_scape);
                    }
                }else{
                    
                }
            }
        }else{
            echo "no exite";
        }
        return $res;
    }
    public function Board($responseBoards){
        $json_decode = json_decode($responseBoards,true);
        var_dump($json_decode);
        $fn = new funciones();
        if(array_key_exists('boards', $json_decode)){
            for($i=0;$i<count($json_decode['boards']);$i++){
                $sqlBoards = "INSERT INTO T_Boards (code,description,multiLingualCode) VALUES (?,?,?)";

                $code =                 isset($json_decode['boards'][$i]['code'])                   ? $json_decode['boards'][$i]['code']                   : "";
                $content =              isset($json_decode['boards'][$i]['description']['content']) ? $json_decode['boards'][$i]['description']['content'] : "";
                $multiLingualCode =     isset($json_decode['boards'][$i]['multiLingualCode'])       ? $json_decode['boards'][$i]['multiLingualCode']       : "";

                $arreglo = [$code,$content,$multiLingualCode];
                
                $consult_scape = $fn->prepararConsulta($sqlBoards, $arreglo);

                $res = $this->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function CountGetHotelesPaises($CountryCode){
        $qry = "SELECT COUNT(*) AS contador FROM T_Hotel_Description WHERE countryCode = '$CountryCode'";
        $res = $this->consulta($qry);
        return $res;
    }
    public function GetHotelesPaises($CountryCode,$limit,$offset){
        $qry = "SELECT 
                thd.id as idhotel,
                thd.ranking as id, 
                thd.`code`,
                thd.name,
                thd.accommodationTypeCode AS tipo,
                thd.categoryCode, 
                thc.latitude, 
                thc.longitude, 
                tha.city,
                tc.description
                FROM T_Hotel_Description  thd
                JOIN T_Hotel_Coordinates thc ON thc.id_hotel_description = thd.id
                JOIN T_Hotel_Address tha ON tha.id_hotel_description = thd.id
                JOIN T_Countries tc ON tc.`code` = thd.countryCode
                WHERE thd.countryCode = '$CountryCode'
                -- GROUP BY thd.`name`
                LIMIT $limit OFFSET $offset";
        $res = $this->consulta($qry);
        for($i=0;$i<count($res['idhotel']);$i++){
            if($res['idhotel'][$i] != NULL){
                $qryimg = "SELECT concat_ws('','https://photos.hotelbeds.com/giata/bigger/', path) as imgweb FROM T_Img_Hotel_Room WHERE id_hotel_description = {$res['idhotel'][$i]} LIMIT 1";
                $resimg = $this->consulta($qryimg);
                $res['imgweb'][$i] = $resimg['imgweb'][0];
            }
        }
        return $res;
    }
    public function CountGetHotelesEstados($countryCode,$nameState,$statecode){
        $qry = "SELECT                
                ts.name
                FROM T_Hotel_Description  thd
                JOIN T_Hotel_Coordinates thc ON thc.id_hotel_description = thd.id
                JOIN T_Hotel_Address tha ON tha.id_hotel_description = thd.id
                JOIN T_States ts ON ts.countryCode = thd.countryCode
                JOIN T_Countries tc ON tc.`code` = thd.countryCode
                WHERE ts.`name` = '$nameState' and ts.countryCode = '$countryCode' and thd.stateCode = '$statecode'
                GROUP BY thd.id";
        $res = $this->consulta($qry);
        return $res;
    }
    public function GetHotelesEstados($countryCode,$nameState,$statecode,$limit,$offset){
        $qry = "SELECT 
                thd.id as idhotel,
                thd.`code`,
                thd.name AS hotel,
                thd.accommodationTypeCode AS tipo,
                thd.categoryCode, 
                thc.latitude, 
                thc.longitude, 
                tha.city
                FROM T_Hotel_Description  thd
                JOIN T_Hotel_Coordinates thc ON thc.id_hotel_description = thd.id
                JOIN T_Hotel_Address tha ON tha.id_hotel_description = thd.id
                JOIN T_States ts ON ts.countryCode = thd.countryCode
                JOIN T_Countries tc ON tc.`code` = thd.countryCode
                WHERE ts.`name` = '$nameState' and ts.countryCode = '$countryCode' and thd.stateCode = '$statecode'
                GROUP BY thd.id
                LIMIT $limit OFFSET $offset";
        $res = $this->consulta($qry);
        $countHotel = count($res['idhotel']);
        for($i=0;$i<$countHotel;$i++){
            if($res['idhotel'][$i] != NULL){
                $qryimg = "SELECT concat_ws('','https://photos.hotelbeds.com/giata/bigger/', path) as imgweb FROM T_Img_Hotel_Room WHERE id_hotel_description = {$res['idhotel'][$i]} LIMIT 1";
                $resimg = $this->consulta($qryimg);
                $res['imgweb'][$i] = $resimg['imgweb'][0];
            }
        }
        return $res;
    }
    public function getMejoresPaises(){
        $qry = "SELECT 
                thd.id, 
                tc.`code`, 
                tc.description
                FROM T_Countries tc
                LEFT JOIN T_Hotel_Description thd ON thd.countryCode = tc.`code`
                WHERE tc.`code` = 'ES' OR tc.`code` = 'MX' OR tc.`code` = 'US' OR tc.`code` = 'CA'
                GROUP BY tc.`code`";
        $res = $this->consulta($qry);
        
        for($i=0;$i<count($res['code']);$i++){
            if($res['id'][$i] != NULL){
                $qryimg = "SELECT concat_ws('','https://photos.hotelbeds.com/giata/bigger/', path) as imgweb FROM T_Img_Hotel_Room WHERE id_hotel_description = {$res['id'][$i]} LIMIT 1";// GROUP BY id_hotel_description
                $resimg = $this->consulta($qryimg);
                $res['imgweb'][$i] = $resimg['imgweb'][0];
            }
        }
        return $res;
    }
    public function getMejoresEstados(){
        $qry = "SELECT 
                th.id as idhotel,
                ts.id,
                ts.name, 
                ts.`code`,
                ts.countryCode
                FROM T_States ts 
                JOIN T_Hotel_Description th ON th.countryCode = ts.countryCode and th.stateCode = ts.`code`
                where ts.`name` = 'SAO PAULO' OR ts.`name` = 'Hiroshima' OR ts.`name` = 'SEVILLA' OR ts.`name` = 'MADEIRA' 
                GROUP BY ts.`code`";
        $res = $this->consulta($qry);
        for($i=0;$i<count($res['idhotel']);$i++){
            if($res['idhotel'][$i] != NULL){
                $qryimg = "SELECT concat_ws('','https://photos.hotelbeds.com/giata/bigger/', path) as imgweb FROM T_Img_Hotel_Room WHERE id_hotel_description = {$res['idhotel'][$i]} LIMIT 1"; // GROUP BY id_hotel_description
                $resimg = $this->consulta($qryimg);
                $res['imgweb'][$i] = $resimg['imgweb'][0];
                
                $qryCount = "SELECT COUNT(*) as total FROM T_Hotel_Description WHERE countryCode = '{$res['countryCode'][$i]}' and stateCode = '{$res['code'][$i]}'";
                $resCount = $this->consulta($qryCount);
                $res['total'][$i] = $resCount['total'][0];
            }
        }
        return $res;
    }
    public function getHotelesNacionales(){
        // $qry = "SELECT 
        //         th.id as idhotel,
        //         ts.name, 
        //         ts.`code`,
        //         th.countryCode
        //         FROM T_States ts 
        //         JOIN T_Hotel_Description th ON th.countryCode = ts.countryCode and th.stateCode = ts.`code`
        //         WHERE th.countryCode = 'MX'
        //         GROUP BY ts.`code`
        //         LIMIT 12";
        $qry = "SELECT id as idhotel, countryCode, stateCode as `code` FROM T_Hotel_Description WHERE countryCode = 'MX' GROUP BY stateCode LIMIT 12";
        $res = $this->consulta($qry);
        $tot = count($res['idhotel']);
        for($i=0;$i<$tot;$i++){
            if($res['idhotel'][$i] !== NULL){
                //se agrega imagen
                $qryimg = "SELECT concat_ws('','https://photos.hotelbeds.com/giata/bigger/', path) as imgweb FROM T_Img_Hotel_Room WHERE id_hotel_description = {$res['idhotel'][$i]} LIMIT 1"; // GROUP BY id_hotel_description
                $resimg = $this->consulta($qryimg);
                $res['imgweb'][$i] = $resimg['imgweb'][0];

                // se agrega nombre
                $qryname = "SELECT `name` FROM T_States WHERE countryCode = 'MX' and `code` = '{$res['code'][$i]}'"; // GROUP BY id_hotel_description
                $resname = $this->consulta($qryname);
                $res['name'][$i] = $resname['name'][0];
            }
        }
        return $res;
    }
    public function getHotelesInternacionales(){
        $fn = new funciones();
        // $qry = "SELECT 
        //         th.id as idhotel,
        //         ts.name, 
        //         ts.`code`,
        //         ts.countryCode
        //         FROM T_States ts 
        //         JOIN T_Hotel_Description th ON th.countryCode = ts.countryCode and th.stateCode = ts.`code`
        //         JOIN T_Countries tc ON tc.`code` = th.countryCode
        //         where th.countryCode <> 'MX'
        //         GROUP BY th.countryCode
        //         LIMIT 12";
        $qry = "SELECT id as idhotel, countryCode, stateCode as `code` FROM T_Hotel_Description WHERE countryCode <> 'MX' GROUP BY countryCode LIMIT 12";
        $res = $this->consulta($qry);
        $tot = count($res['idhotel']);
        for($i=0;$i<$tot;$i++){
            if($res['idhotel'][$i] != NULL){
                // $arreglo[] = $res['idhotel'][$i];
                $qryimg = "SELECT concat_ws('','https://photos.hotelbeds.com/giata/bigger/', path) as imgweb FROM T_Img_Hotel_Room WHERE id_hotel_description = {$res['idhotel'][$i]} LIMIT 1"; // GROUP BY id_hotel_description
                $resimg = $this->consulta($qryimg);
                $res['imgweb'][$i] = $resimg['imgweb'][0];

                // se agrega nombre
                $qryname = "SELECT `name` FROM T_States WHERE countryCode = '{$res['countryCode'][$i]}' and `code` = '{$res['code'][$i]}'"; // GROUP BY id_hotel_description
                $resname = $this->consulta($qryname);
                $res['name'][$i] = $resname['name'][0];
            }
        }
        return $res;
    }
    public function UpdateClient($jsonClient){
        $json_decode = json_decode($jsonClient,true);
        $fn = new funciones();
        if(array_key_exists('nombre', $json_decode)){
            $nombre =       isset($json_decode['nombre'])       ? $json_decode['nombre']    : "";
            $apellido =     isset($json_decode['apellido'])     ? $json_decode['apellido']  : "";
            $correo =       isset($json_decode['correo'])       ? $json_decode['correo']    : "";
            $telefono =     isset($json_decode['telefono'])     ? $json_decode['telefono']  : "";
            $id =           isset($json_decode['idCliente'])    ? $json_decode['idCliente'] : "";

            $sqlClienteUpdate = "UPDATE T_Booking_Client SET nombre = '$nombre', apellido = '$apellido', correo = '$correo', telefono = '$telefono' WHERE id = '$id'";

            $res = $this->ejecuta($sqlClienteUpdate);
            return $res;
        }
    }
    public function AddClient($jsonClient){
        $json_decode = json_decode($jsonClient,true);
        $fn = new funciones();
        if(array_key_exists('nombre', $json_decode)){
            $sqlCliente = "INSERT INTO T_Booking_Client (nombre,apellido,correo,telefono,status_pago,fch_alta,hora_alta) VALUES (?,?,?,?,?,curdate(),curtime())";

            $nombre =       isset($json_decode['nombre'])   ? $json_decode['nombre']   : "";
            $apellido =     isset($json_decode['apellido']) ? $json_decode['apellido'] : "";
            $correo =       isset($json_decode['correo'])   ? $json_decode['correo']   : "";
            $telefono =     isset($json_decode['telefono']) ? $json_decode['telefono'] : "";
            $estatus =      0;


            $arreglo = [$nombre, $apellido, $correo, $telefono, $estatus];
            $consult_scape = $fn->prepararConsulta($sqlCliente,$arreglo);
            $res = $this->ejecuta($consult_scape);
            return $res;
        }
    }

    public function BookingConfirmation($bookingResponse,$idCliente){
        $json_decode = json_decode($bookingResponse, true);

        $fn = new funciones();

        if(array_key_exists('booking', $json_decode)) {
            for($i=0;$i<1;$i++) {
                $sqlBooking = "INSERT INTO T_Booking(reference,clientReference,creationDate,`status`,cancellation,modification,creationUser,`name`,surname,remark,totalNet,pendingAmount,currency,folio_LGT,id_cliente) 
                                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $reference =       isset($json_decode['booking']['reference'])                            ? $json_decode['booking']['reference']                            : "";
                $clientReference = isset($json_decode['booking']['clientReference'])                      ? $json_decode['booking']['clientReference']                      : "";
                $creationDate =    isset($json_decode['booking']['creationDate'])                         ? $json_decode['booking']['creationDate']                         : "";
                $status =          isset($json_decode['booking']['status'])                               ? $json_decode['booking']['status']                               : "";
                $cancellation =    isset($json_decode['booking']['modificationPolicies']['cancellation']) ? $json_decode['booking']['modificationPolicies']['cancellation'] : "";
                $modification =    isset($json_decode['booking']['modificationPolicies']['modification']) ? $json_decode['booking']['modificationPolicies']['modification'] : "";
                $creationUser =    isset($json_decode['booking']['creationUser'])                         ? $json_decode['booking']['creationUser']                         : "";
                $name =            isset($json_decode['booking']['holder']['name'])                       ? $json_decode['booking']['holder']['name']                       : "";
                $surname =         isset($json_decode['booking']['holder']['surname'])                    ? $json_decode['booking']['holder']['surname']                    : "";
                $remark =          isset($json_decode['booking']['remark'])                               ? $json_decode['booking']['remark']                               : "";
                $totalNet =        isset($json_decode['booking']['totalNet'])                             ? $json_decode['booking']['totalNet']                             : "";
                $pendingAmount =   isset($json_decode['booking']['pendingAmount'])                        ? $json_decode['booking']['pendingAmount']                        : "";
                $currency =        isset($json_decode['booking']['currency'])                             ? $json_decode['booking']['currency']                             : "";
                $folio =           1;
                $idCliente =       isset($idCliente)                                                      ? $idCliente                                                      : "";

                $arreglo = [    $reference,
                                $clientReference,
                                $creationDate,
                                $status,
                                $cancellation,
                                $modification,
                                $creationUser,
                                $name,
                                $surname,
                                $remark,
                                $totalNet,
                                $pendingAmount,
                                $currency,
                                $folio,
                                $idCliente  ];
                
                $consult_scape = $fn->prepararConsulta($sqlBooking, $arreglo);
                $idBooking = $this->ejecuta($consult_scape);
                            
                $folio = "LGT-".date('ymd')."-".$idBooking;

                $sqlUpdateFolio = "UPDATE T_Booking SET `folio_LGT` = '$folio' WHERE id = $idBooking";
                
                $this->ejecuta($sqlUpdateFolio);
                            
                if(isset($json_decode['booking']['hotel'])) {
                    for($h=0;$h<1;$h++) {
                        $sqlBookingHotel = "INSERT INTO T_Booking_Hotel (checkOut,checkIn,`code`,`name`,categoryCode,categoryName,destinationCode,destinationName,zoneCode,zoneName,latitude,longitude,totalNet,currency,supplier_name,vatNumber,id_booking) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                        $checkOut =        isset($json_decode['booking']['hotel']['checkOut'])              ? $json_decode['booking']['hotel']['checkOut']              : "";
                        $checkIn =         isset($json_decode['booking']['hotel']['checkIn'])               ? $json_decode['booking']['hotel']['checkIn']               : "";
                        $code =            isset($json_decode['booking']['hotel']['code'])                  ? $json_decode['booking']['hotel']['code']                  : "";
                        $name =            isset($json_decode['booking']['hotel']['name'])                  ? $json_decode['booking']['hotel']['name']                  : "";
                        $categoryCode =    isset($json_decode['booking']['hotel']['categoryCode'])          ? $json_decode['booking']['hotel']['categoryCode']          : "";
                        $categoryName =    isset($json_decode['booking']['hotel']['categoryName'])          ? $json_decode['booking']['hotel']['categoryName']          : "";
                        $destinationCode = isset($json_decode['booking']['hotel']['destinationCode'])       ? $json_decode['booking']['hotel']['destinationCode']       : "";
                        $destinationName = isset($json_decode['booking']['hotel']['destinationName'])       ? $json_decode['booking']['hotel']['destinationName']       : "";
                        $zoneCode =        isset($json_decode['booking']['hotel']['zoneCode'])              ? $json_decode['booking']['hotel']['zoneCode']              : "";
                        $zoneName =        isset($json_decode['booking']['hotel']['zoneName'])              ? $json_decode['booking']['hotel']['zoneName']              : "";
                        $latitude =        isset($json_decode['booking']['hotel']['latitude'])              ? $json_decode['booking']['hotel']['latitude']              : "";
                        $longitude =       isset($json_decode['booking']['hotel']['longitude'])             ? $json_decode['booking']['hotel']['longitude']             : "";
                        $totalNet =        isset($json_decode['booking']['hotel']['totalNet'])              ? $json_decode['booking']['hotel']['totalNet']              : "";
                        $currency =        isset($json_decode['booking']['hotel']['currency'])              ? $json_decode['booking']['hotel']['currency']              : "";
                        $supplier_name =   isset($json_decode['booking']['hotel']['supplier']['name'])      ? $json_decode['booking']['hotel']['supplier']['name']      : "";
                        $vatNumber =       isset($json_decode['booking']['hotel']['supplier']['vatNumber']) ? $json_decode['booking']['hotel']['supplier']['vatNumber'] : "";
                        $id_booking =      isset($idBooking)                                                ? $idBooking                                                : "";
                        
                        $arreglo = [$checkOut,$checkIn,$code,$name,$categoryCode,$categoryName,$destinationCode,$destinationName,$zoneCode,$zoneName,$latitude,$longitude,$totalNet,$currency,$supplier_name,$vatNumber,$id_booking];
                        $consult_scape = $fn->prepararConsulta($sqlBookingHotel,$arreglo);
                        $idHotel = $this->ejecuta($consult_scape);

                        if(isset($json_decode['booking']['hotel']['rooms'])) {
                            for($r=0;$r<count($json_decode['booking']['hotel']['rooms']);$r++) {
                                $sqlRooms = "INSERT INTO T_Booking_H_Rooms (`status`,id_status,`code`,`name`,id_booking_hotel) VALUES (?,?,?,?,?)";

                                $status =           isset($json_decode['booking']['hotel']['rooms'][$r]['status'])        ? $json_decode['booking']['hotel']['rooms'][$r]['status'] : "";
                                $id_status =        isset($json_decode['booking']['hotel']['rooms'][$r]['id'])            ? $json_decode['booking']['hotel']['rooms'][$r]['id']     : "";
                                $code =             isset($json_decode['booking']['hotel']['rooms'][$r]['code'])          ? $json_decode['booking']['hotel']['rooms'][$r]['code']   : "";
                                $name =             isset($json_decode['booking']['hotel']['rooms'][$r]['name'])          ? $json_decode['booking']['hotel']['rooms'][$r]['name']   : "";
                                $id_booking_hotel = isset($idHotel)                                                       ? $idHotel                                                : "";

                                $arreglo = [$status,$id_status,$code,$name,$id_booking_hotel];
                                $consult_scape = $fn->prepararConsulta($sqlRooms,$arreglo);
                                $idRoom = $this->ejecuta($consult_scape);

                                if(isset($json_decode['booking']['hotel']['rooms'][$r]['paxes'])) {
                                    for($p=0;$p<count($json_decode['booking']['hotel']['rooms'][$r]['paxes']);$p++) {
                                        $sqlPaxes = "INSERT INTO T_Booking_H_R_Paxes (roomId,`type`, `age`,`name`,surname,id_bookinh_h_room) VALUES (?,?,?,?,?,?)";
                                        
                                        $roomId =           isset($json_decode['booking']['hotel']['rooms'][$r]['paxes'][$p]['roomId'])  ? $json_decode['booking']['hotel']['rooms'][$r]['paxes'][$p]['roomId']  : "";
                                        $type =             isset($json_decode['booking']['hotel']['rooms'][$r]['paxes'][$p]['type'])    ? $json_decode['booking']['hotel']['rooms'][$r]['paxes'][$p]['type']    : "";
                                        $age =              isset($json_decode['booking']['hotel']['rooms'][$r]['paxes'][$p]['age'])     ? $json_decode['booking']['hotel']['rooms'][$r]['paxes'][$p]['age']     : "";
                                        $name =             isset($json_decode['booking']['hotel']['rooms'][$r]['paxes'][$p]['name'])    ? $json_decode['booking']['hotel']['rooms'][$r]['paxes'][$p]['name']    : "";
                                        $surname =          isset($json_decode['booking']['hotel']['rooms'][$r]['paxes'][$p]['surname']) ? $json_decode['booking']['hotel']['rooms'][$r]['paxes'][$p]['surname'] : "";
                                        $id_booking_hRoom = isset($idRoom)                                                               ? $idRoom                                                               : "";

                                        $arreglo = [$roomId,$type,$age,$name,$surname,$id_booking_hRoom];
                                        $consult_scape = $fn->prepararConsulta($sqlPaxes,$arreglo);
                                        $idPaxes = $this->ejecuta($consult_scape);
                                    }
                                }

                                if(isset($json_decode['booking']['hotel']['rooms'][$r]['rates'])) {
                                    for($x=0;$x<count($json_decode['booking']['hotel']['rooms'][$r]['rates']);$x++) {
                                        $sqlRates = "INSERT INTO T_Booking_H_R_Rates (rateClass,net,rateComments,paymentType,packaging,boardCode,boardName,rooms,adults,children,id_booking_h_room) 
                                                    VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                                        
                                        $rateClass =        isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['rateClass'])    ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['rateClass']    : "";
                                        $net =              isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['net'])          ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['net']          : "";
                                        $rateComments =     isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['rateComments']) ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['rateComments'] : "";
                                        $paymentType =      isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['paymentType'])  ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['paymentType']  : "";
                                        $packaging =        isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['packaging'])    ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['packaging']    : "";
                                        $boardCode =        isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['boardCode'])    ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['boardCode']    : "";
                                        $boardName =        isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['boardName'])    ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['boardName']    : "";
                                        $rooms =            isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['rooms'])        ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['rooms']        : "";
                                        $adults =           isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['adults'])       ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['adults']       : "";
                                        $children =         isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['children'])     ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['children']     : "";
                                        $id_booking_hRoom = isset($idRoom)                                                                    ? $idRoom                                                                     : "";

                                        $arreglo = [$rateClass,$net,$rateComments,$paymentType,$packaging,$boardCode,$boardName,$rooms,$adults,$children,$id_booking_hRoom];
                                        $consult_scape = $fn->prepararConsulta($sqlRates,$arreglo);
                                        $idRates = $this->ejecuta($consult_scape);

                                        if(isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['cancellationPolicies'])){
                                            for($pc=0;$pc<count($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['cancellationPolicies']);$pc++) {
                                                $sqlCancel = "INSERT INTO T_Booking_PolCancel (amount,`from`,id_rate) VALUES (?,?,?)";

                                                $amount = isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['cancellationPolicies'][$pc]['amount']) ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['cancellationPolicies'][$pc]['amount'] : "";
                                                $from =   isset($json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['cancellationPolicies'][$pc]['from'])   ? $json_decode['booking']['hotel']['rooms'][$r]['rates'][$x]['cancellationPolicies'][$pc]['from']   : "";

                                                $arreglo = [$amount,$from,$idRates];
                                                $consult_scape = $fn->prepararConsulta($sqlCancel,$arreglo);
                                                $this->ejecuta($consult_scape);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if(isset($json_decode['booking']['invoiceCompany'])) {
                for($c=0;$c<1;$c++){
                    $sqlCompany = "INSERT INTO T_Booking_Company (`code`,company,registrationNumber,id_booking) VALUES (?,?,?,?)";

                    $code =               isset($json_decode['booking']['invoiceCompany']['code'])               ? $json_decode['booking']['invoiceCompany']['code']               : "";
                    $company =            isset($json_decode['booking']['invoiceCompany']['company'])            ? $json_decode['booking']['invoiceCompany']['company']            : "";
                    $registrationNumber = isset($json_decode['booking']['invoiceCompany']['registrationNumber']) ? $json_decode['booking']['invoiceCompany']['registrationNumber'] : "";
                    $id_booking =         isset($idBooking)                                                      ? $id_booking                                                     : "";

                    $arreglo       = [$code,$company,$registrationNumber,$id_booking];
                    $consult_scape = $fn->prepararConsulta($sqlCompany,$arreglo);
                    $idcompany     = $this->ejecuta($consult_scape);
                }
            }
        }

        return $idBooking;
    }

    public function CheckDocument($idReservacion){
        $qry = "SELECT tb.reference, tb.`status`, tb.`name` as nombreResp, tb.`surname` as apeResp, tb.remark, tb.totalNet as total, tb.Folio_LGT, 
                tbh.checkIn, tbh.checkOut, tbh.`name` as hotelName, tbh.categoryCode, tbh.destinationName, tbh.`code`, tbh.`supplier_name`, tbh.`vatNumber`,
                tbhr.`code` as codigoHab, tbhr.`name` as habitacion, 
                tbhrp.`name` as nombreTitular, tbhrp.surname as apellidoTitular, tbhrp.type, tbhrp.age,
                tbhrr.rateComments, tbhrr.boardCode, tbhrr.boardName, tbhrr.adults, tbhrr.children,
                tc.telefono, tc.correo
                FROM T_Booking tb
                JOIN T_Booking_Hotel tbh ON tbh.id_booking = tb.id
                JOIN T_Booking_H_Rooms tbhr ON tbhr.id_booking_hotel = tbh.id
                JOIN T_Booking_H_R_Paxes tbhrp ON tbhrp.id_bookinh_h_room = tbhr.id
                JOIN T_Booking_H_R_Rates tbhrr ON tbhrr.id_booking_h_room = tbhr.id
                JOIN T_Booking_Client tc ON tc.id = tb.id_cliente
                WHERE tb.reference = '$idReservacion' OR tb.Folio_LGT = '$idReservacion'";
        $res = $this->consulta($qry);
        return $res;
    }
}

?>