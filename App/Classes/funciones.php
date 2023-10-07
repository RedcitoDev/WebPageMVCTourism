<?php

namespace Classes;

use Classes\mysqlconsultas;

class funciones {

    public function delete($tabla, $id) {
        $qry = "delete from " . $tabla . " where id = " . $id;
        $base = new mysqlconsultas();
        $res = $base->ejecuta($qry);
        return $res;
    }

    public function encriptarMD5($cadena) {
        $clave = md5($cadena);
        return $clave;
    }

    public function cuentarray($arreglo, $campo) {
        if (is_array($arreglo)) {
            $cuantos = count($arreglo["$campo"]);
        } else {
            $cuantos = 0;
        }
        return $cuantos;
    }

    public function cuenta($arreglo) {
        if (is_array($arreglo)) {
            $cuantos = count($arreglo["id"]);
        } else {
            $cuantos = 0;
        }
        return $cuantos;
    }

    public function sumadias($fec_emision, $can_dias) {
        $fecha = explode("-", $fec_emision);
        $dyh = getdate(mktime(0, 0, 0, $fecha[1], $fecha[2], $fecha[0]) + 24 * 60 * 60 * $can_dias);
        $fec_vencimiento = $dyh['year'] . "-" . $this->ceros($dyh['mon']) . "-" . $this->ceros($dyh['mday']);
        return ($fec_vencimiento);
    }

    public function restaFechas($dFecIni, $dFecFin) {
        $date1 = new \DateTime($dFecIni);
        $date2 = new \DateTime($dFecFin);
        $diff = $date1->diff($date2);
        return $diff->days;
    }

    public function ceros($numero, $ceros = 2) {
        return sprintf("%0" . $ceros . "s", $numero);
    }

    public function fechaAmericana($fecha) {
        $fechanueva = explode("-", $fecha);
        $year = $fechanueva[0];
        $mes = $fechanueva[1];
        $dia = $fechanueva[2];

        $mesnuevo = $this->mesAbreviado($mes);
        $nueva = $dia . " " . $mesnuevo . ", " . $year;
        return $nueva;
    }

    public function fechaMexicana($fecha) {
        $fechanueva = explode("-", $fecha);
        $year = $fechanueva[0];
        $mes = $fechanueva[1];
        $dia = $fechanueva[2];

        $mesnuevo = $this->nombremesESP($mes);
        $nueva = $dia . " de " . $mesnuevo . " de " . $year;
        return $nueva;
    }

    public function famercianacdia($fecha) {
        $diasem = $this->diaSemanaES($fecha);

        $fechanueva = explode("-", $fecha);
        $year = $fechanueva[0];
        $mes = $fechanueva[1];
        $dia = $fechanueva[2];

        $mesnuevo = $this->mesAbreviado($mes);
        $nueva = $diasem . ", " . $dia . " " . $mesnuevo . ", " . $year;
        return $nueva;
    }

    public function famercianacdiasinano($fecha) {
        $diasem = $this->diaSemanaES($fecha);

        $fechanueva = explode("-", $fecha);
        $year = $fechanueva[0];
        $mes = $fechanueva[1];
        $dia = $fechanueva[2];

        $mesnuevo = $this->mesAbreviado($mes);
        $nueva = $diasem . ", " . $dia . " " . $mesnuevo;
        return $nueva;
    }

    public function mesAbreviado($mes) {
        switch ($mes) {
            case 1: return 'Ene';
                break;
            case 2: return 'Feb';
                break;
            case 3: return 'Mar';
                break;
            case 4: return 'Abr';
                break;
            case 5: return 'May';
                break;
            case 6: return 'Jun';
                break;
            case 7: return 'Jul';
                break;
            case 8: return 'Ago';
                break;
            case 9: return 'Sept';
                break;
            case 10: return 'Oct';
                break;
            case 11: return 'Nov';
                break;
            case 12: return 'Dic';
                break;
        }
    }

    public function nombremesESP($mes) {
        switch ($mes) {
            case 1: return 'Enero';
                break;
            case 2: return 'Febrero';
                break;
            case 3: return 'Marzo';
                break;
            case 4: return 'Abril';
                break;
            case 5: return 'Mayo';
                break;
            case 6: return 'Junio';
                break;
            case 7: return 'Julio';
                break;
            case 8: return 'Agosto';
                break;
            case 9: return 'Septiembre';
                break;
            case 10: return 'Octubre';
                break;
            case 11: return 'Noviembre';
                break;
            case 12: return 'Diciembre';
                break;
        }
    }

    public function diaSemanaEN($fecha) {
        $fechanueva = explode("-", $fecha);
        $year = $fechanueva[0];
        $mes = $fechanueva[1];
        $dia = $fechanueva[2];

        // 0->domingo     | 6->sabado
        $dia = date("w", mktime(0, 0, 0, $mes, $dia, $year));
        switch ($dia) {
            case 0:
                $day = "Sunday";
                break;
            case 1:
                $day = "Monday";
                break;
            case 2:
                $day = "Tuesday";
                break;
            case 3:
                $day = "Wednesday";
                break;
            case 4:
                $day = "Thursday";
                break;
            case 5:
                $day = "Friday";
                break;
            case 6:
                $day = "Saturday";
                break;
        }
        return $day;
    }

    public function diaSemanaES($fecha) {
        $fechanueva = explode("-", $fecha);
        $year = $fechanueva[0];
        $mes = $fechanueva[1];
        $dia = $fechanueva[2];

        // 0->domingo     | 6->sabado
        $dia = date("w", mktime(0, 0, 0, $mes, $dia, $year));
        switch ($dia) {
            case 0:
                $day = "Domingo";
                break;
            case 1:
                $day = "Lunes";
                break;
            case 2:
                $day = "Martes";
                break;
            case 3:
                $day = "Miercoles";
                break;
            case 4:
                $day = "Jueves";
                break;
            case 5:
                $day = "Viernes";
                break;
            case 6:
                $day = "Sabado";
                break;
        }
        return $day; 
    }
    
    public function replace_filename($cadena) {
	$no_permitidas= array ("'"," ",",","Ã¡","Ã©","Ã­","Ã³","Ãº","Ã�","Ã‰","Ã�","Ã“","Ãš","Ã±","Ã€","Ãƒ","ÃŒ","Ã’","Ã™","Ãƒâ„¢","Ãƒ ","ÃƒÂ¨","ÃƒÂ¬","ÃƒÂ²","ÃƒÂ¹","Ã§","Ã‡","ÃƒÂ¢","Ãª","ÃƒÂ®","ÃƒÂ´","ÃƒÂ»","Ãƒâ€š","ÃƒÅ ","ÃƒÅ½","Ãƒâ€�","Ãƒâ€º","Ã¼","ÃƒÂ¶","Ãƒâ€“","ÃƒÂ¯","ÃƒÂ¤","Â«","Ã’","ÃƒÂ�","Ãƒâ€ž","Ãƒâ€¹");
	$permitidas= array ("","_","","a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
	$texto = str_replace($no_permitidas, $permitidas ,$cadena);
        
	return $texto;
    } 
    
    public function limpiaNFC($cadena){
	$no_permitidas= array (":");
	$permitidas= array ("");
	$texto = str_replace($no_permitidas, $permitidas ,$cadena);
        
	return $texto;        
    }
    
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }  
    
    public function encrypt($string){			
       $key = "elmejorsistemadedmcsenelmundodesdeel2015";	
       $result = '';
       for($i=0; $i<strlen($string); $i++) {
          $char = substr($string, $i, 1);
          $keychar = substr($key, ($i % strlen($key))-1, 1);
          $char = chr(ord($char)+ord($keychar));
          $result.=$char;
       }
       return base64_encode($result);
    }

    public function decrypt($string) {			
       $key = "elmejorsistemadedmcsenelmundodesdeel2015";
       if(is_array($string)){
            $string = $string[0];
            }
       $result = '';
       $string = base64_decode($string);
       for($i=0; $i<strlen($string); $i++) {
          $char = substr($string, $i, 1);
          $keychar = substr($key, ($i % strlen($key))-1, 1);
          $char = chr(ord($char)-ord($keychar));
          $result.=$char;
       }
       return $result;
    }
    public function prepararConsulta($qry, $arreglo) {
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

    public function curlGet($url) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            $response = "cURL Error #:" . $err;
        }

        return $response;
    }

}
