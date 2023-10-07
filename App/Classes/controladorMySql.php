<?php
namespace Classes;

class controladorMySql {
    /**
     * The Singleton's instance is stored in a static field. This field is an
     * array, because we'll allow our Singleton to have subclasses. Each item in
     * this array will be an instance of a specific Singleton's subclass. You'll
     * see how this works in a moment.
     */
    private static $instances = [];
    public $conexion;
    
    /**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
     */
    protected function __construct($host = HOST, $usuario = USUARIO, $clave = PASSWORD, $db = BD) {
        $this->conexion = new \mysqli($host, $usuario, $clave, $db);
        $this->conexion->set_charset("utf8");
    }

    /**
     * Singletons should not be cloneable.
     */
    protected function __clone() { }

    /**
     * Singletons should not be restorable from strings.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * This is the static method that controls the access to the singleton
     * instance. On the first run, it creates a singleton object and places it
     * into the static field. On subsequent runs, it returns the client existing
     * object stored in the static field.
     *
     * This implementation lets you subclass the Singleton class while keeping
     * just one instance of each subclass around.
     */
    public static function getInstance(): controladorMySql
    {
        $cls = static::class; // <--- Retorna el nombre de la clase actual
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }


    public function consulta($query) {    
        $result = $this->conexion->query($query) or die(mysqli_error($this->conexion));

        $number_cols    = mysqli_num_fields($result);
        $number_filas   = mysqli_num_rows($result);

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
        $result     = $this->conexion->query($query) or die(mysqli_error($this->conexion));

        $registro   = mysqli_insert_id($this->conexion);

        return $registro;
    }
    
    public function escape($qry){        
        $escapar = mysqli_real_escape_string($this->conexion, $qry); 
        
        return $escapar;
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
}
