<?php

    include("../clases/allclass.php");
    use nsbedsonline\getBedsonline;
    use conexionbd\mysqlconsultas;
    use nsfunciones\funciones;
    use nscatalogo\catalogo;
    
    $ejecucion = new mysqlconsultas();    
    $fn = new funciones();
    
    $bedsonline = new getBedsonline();
    $cat = new catalogo();
    
    $language = "CAS";    //Nosotros usaremos MEXICO por ahora, pero debemos hacer la clase para lenguages
    $from = 1; //Siempre empezaremos desde 1
    $to = 300; //Este es el tamaño de registros que mostraremos
    
    $countries = $bedsonline->getCountries($language, $from, $to);        
    
    if(is_object($countries)) {
        //Respuesta correcta
        echo "<br />Es un objeto";
        foreach($countries->countries as $country){
            $codigo = $country->code;
            $nombre = $country->description->content;
            $isocode = $country->isoCode;
                        
            $country = $cat->findCountry($codigo);
            $total  =$fn->cuentarray($country, 'id');
            
            if($total >= 1){
                $nombrebd = $country["nombre"][0];
                $id = $country["id"][0];
                if($nombrebd != $nombre){
                    $qryup="update countries set nombre = '$nombre' where id = '$id'";
                    $ejecutaup = $ejecucion->ejecuta($qryup);                   
                }
            }else{
                $qry="insert into countries (code, isocode, nombre) values ('$codigo', '$isocode', '$nombre')";
                $ejecutar = $ejecucion->ejecuta($qry);                   
            }
            
            
            
            echo "<br />Code: ".$codigo." - Name: ".$nombre." - ISOcode: ".$isocode;
        }
    }else{
        //Error
        echo "<br />No es un objeto";
        echo "<br />Cadena de respuesta: ".$countries;
    }    