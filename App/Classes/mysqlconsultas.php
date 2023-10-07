<?php
namespace Classes;

include_once 'envVariables.php';

class mysqlconsultas {

    public function consulta($query) {    
        $conn = $this->conexion();        
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

    public function ejecuta($query) {
        $connection = $this->conexion();          
        $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
        $registro = mysqli_insert_id($connection);
        return $registro;
    }   
    
    public function conexion(){
        $connection = new \mysqli(HOST, USUARIO, PASSWORD, BD);
        return $connection;
    }
    
    public function escape($qry){        
        $escapar = mysqli_real_escape_string($this->conexion(), $qry); 
        return $escapar;
    }

}
