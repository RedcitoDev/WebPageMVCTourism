<?php 

namespace Modelo;

class ActiveRecord {
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    public $qry;

    public function all() {
        return self::$db->consulta('SELECT * FROM' . $this->formatearNombres(static::$tabla));
    }

    public function select($columns = ['*']) {
        $qry = 'SELECT * FROM ' . $this->formatearNombres(static::$tabla);

        $this->qry = str_replace('*', implode(', ', $this->formatearNombres($columns)), $qry);
        return $this;
    }

    public function where($where) {
        $this->qry .= ' WHERE ' . $this->formatearNombres($where);
        return $this;
    }

    public function whereIn($column, $parameters) {
        $this->qry .= ' WHERE ' . $this->formatearNombres($column) . ' IN (' . implode(',', $this->formatearNombres($parameters)) . ')';
        return $this;
    }

    public function limit($limit) {
        $this->qry .= ' LIMIT ' . $limit;
        return $this;
    }

    public function groupBy($groupBy) {
        $this->qry .= ' GROUP BY ' . $this->formatearNombres($groupBy);
        return $this;
    }

    public function orderBy($orderBy) {
        $this->qry .= ' ORDER BY ' . $this->formatearNombres($orderBy);
        return $this;
    }

    public function join($table, $search_condition) {
        $this->qry .= ' JOIN ' . $this->formatearNombres($table) . ' ON ' . $search_condition;
        return $this;
    }

    public function formatearNombres($columns) {
        if (is_array($columns)) {
            for ($i=0, $l=count($columns); $i < $l; $i++) {
                if (is_numeric($columns[$i])) {
                    continue;
                } else {
                    $columns[$i] = '`' . $columns[$i] . '`';
                }
            }

            return $columns;
        } else {
            return '`' . $columns . '`';
        }
        
    }

    public function ejecutarSQL() {
        // echo $this->qry;
        return self::$db->consulta($this->qry);
    }

    public static function establecerDB($database) {
        self::$db = $database;
    }
}

// TODO: Añadir metodos para crear, actualizar y eliminar registros, union de tablas, etc. SECCION 31 DEL CURSO DESARROLLO WEB
