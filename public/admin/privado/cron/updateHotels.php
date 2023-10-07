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
    
    $destination = "PCM"; //Nosotros usaremos MEXICO por ahora, pero debemos hacer la clase para paises
    $language = "CAS";    //Nosotros usaremos MEXICO por ahora, pero debemos hacer la clase para lenguages
    $from = 1; //Siempre empezaremos desde 1
    $to = 10; //Este es el tamaño de registros que mostraremos
    
    $hotels = $bedsonline->getHotels($destination, $language, $from, $to);        
    
    if(is_object($hotels)) {
        //Respuesta correcta
        echo "<br />Es un objeto";
        echo "Total encontrados: ".$hotels->total;
       foreach($hotels->hotels as $hotel){
            $codigo = $hotel->code;
            $nombre = $hotel->name->content;
            $countrycode = $hotel->countryCode;
            $cateogry = $hotel->categoryCode;
            
            $acomodationtypecode = $hotel->accommodationTypeCode;
            
            /*$dest = $cat->findDestination($codigo);
            $total  =$fn->cuentarray($dest, 'id');
            
            if($total >= 1){
                $nombrebd = $dest["nombre"][0];
                $id = $dest["id"][0];
                if($nombrebd != $nombre){
                    $qryup="update destinos set nombre = '$nombre' where id = '$id'";
                    $ejecutaup = $ejecucion->ejecuta($qryup);                   
                }
            }else{
                $qry="insert into destinos (codigo, nombre, countrycode) values ('$codigo', '$nombre', '$countrycode')";
                $ejecutar = $ejecucion->ejecuta($qry);                   
            }*/
            echo "<br />Code: ".$codigo." - Name: ".$nombre." - Category: ".$cateogry;
        }
    }else{
        //Error
        echo "<br />No es un objeto";
        echo "<br />Cadena de respuesta: ".$destinations;
    }    