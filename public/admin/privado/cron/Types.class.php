<?php

namespace TypesHotelBeds;

include("allclass.php");

use conexionbd\mysqlconsultas;
use nsfunciones\funciones;

set_time_limit(0);

class TypesHotel {
    public function Facilities($responseFacilities){
        $json_decode = json_decode($responseFacilities,true);
        $sql = new mysqlconsultas();
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
                
                $res = $sql->ejecuta($consult_scape);
            }
        }else{

        }
        return $res;
    }
    public function FacilitiesGruop($responseFacilitieGroup){
        $json_decode = json_decode($responseFacilitieGroup,true);
        var_dump($json_decode);
        $sql = new mysqlconsultas();
        $fn = new funciones();
        if(array_key_exists('facilityGroups', $json_decode)){
            for($i=0;$i<count($json_decode['facilityGroups']);$i++){
                $sqlFacilitiesGruop = "INSERT INTO T_Facilities_Group (code,description) VALUES (?,?)";

                $code =         isset($json_decode['facilityGroups'][$i]['code'])                    ? $json_decode['facilityGroups'][$i]['code']                    : "";
                $description =  isset($json_decode['facilityGroups'][$i]['description']['content'])  ? $json_decode['facilityGroups'][$i]['description']['content']  : "";

                $arreglo = [$code,$description];

                $consult_scape = $fn->prepararConsulta($sqlFacilitiesGruop, $arreglo);

                $res = $sql->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function FacilitiesTypologies($responseFacilitiesTypologies){
        $json_decode = json_decode($responseFacilitiesTypologies,true);
        var_dump($json_decode);
        $sql = new mysqlconsultas();
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
                    
                $res = $sql->ejecuta($consult_scape);
            }
        }else{

        }
        return $res;
    }
    public function Rooms($responseRooms){
        $json_decode = json_decode($responseRooms,true);
        $sql = new mysqlconsultas();
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
                
                $res = $sql->ejecuta($consult_scape);
            }
        }else{

        }
        return $res;
    }
    public function Promotions($responsePromotions){
        $json_decode = json_decode($responsePromotions,true);
        $sql = new mysqlconsultas();
        $fn = new funciones();
        if(array_key_exists('promotions', $json_decode)){
            for($i=0;$i<count($json_decode['promotions']);$i++){
                $sqlPromotions = "INSERT INTO T_Promotions (code,description,name) VALUES (?,?,?)";

                $code =        isset($json_decode['promotions'][$i]['code'])                    ? $json_decode['promotions'][$i]['code']                    : "";
                $description = isset($json_decode['promotions'][$i]['description']['content'])  ? $json_decode['promotions'][$i]['description']['content']  : "";
                $name =        isset($json_decode['promotions'][$i]['name']['content'])         ? $json_decode['promotions'][$i]['name']['content']         : "";
                
                $arreglo = [$code,$description,$name];

                $consult_scape = $fn->prepararConsulta($sqlPromotions, $arreglo);
                
                $res = $sql->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function Categories($responseCategories){
        $json_decode = json_decode($responseCategories,true);
        $sql = new mysqlconsultas();
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
                
                $res = $sql->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function CategoriesGroup($responseCategoriesGroup){
        $json_decode = json_decode($responseCategoriesGroup,true);
        var_dump($json_decode);
        $sql = new mysqlconsultas();
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

                $res = $sql->ejecuta($consult_scape);
            }
        }else{
            
        }
        return $res;
    }
    public function Countries($responseCountries){
        $json_decode = json_decode($responseCountries,true);
        $sql = new mysqlconsultas();
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

                $res = $sql->ejecuta($consult_scape);
                
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
                        
                        $sql->ejecuta($consult_scape);
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
        $sql = new mysqlconsultas();
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
                
                $res = $sql->ejecuta($consult_scape);

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
                            $sql->ejecuta($consult_scape);
                    }
                }else{
                    
                }
            }
        }else{
            echo "no exite";
        }
        return $res;
    }
}



?>