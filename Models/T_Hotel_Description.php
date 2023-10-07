<?php 

namespace Modelo;

class T_Hotel_Description extends ActiveRecord {
    protected static $tabla = 'T_Hotel_Description';
    protected static $columnasDB = [
        'id',
        'code',
        'name',
        'description',
        'countryCode',
        'stateCode',
        'destinationCode',
        'categoryCode',
        'categoryGroupCode',
        'chainCode',
        'accommodationTypeCode',
        'email',
        'license',
        'S2C',
        'web',
        'ranking'
    ];

    public $id;
    public $code;
    public $name;
    public $description;
    public $countryCode;
    public $stateCode;
    public $destinationCode;
    public $categoryCode;
    public $categoryGroupCode;
    public $chainCode;
    public $accommodationTypeCode;
    public $email;
    public $license;
    public $S2C;
    public $web;
    public $ranking;

    public $qry;

    public function __construct($args = [])
    {
        $this->id                       = $args["id"]                       ?? '';
        $this->code                     = $args["code"]                     ?? '';
        $this->name                     = $args["name"]                     ?? '';
        $this->description              = $args["description"]              ?? '';
        $this->countryCode              = $args["countryCode"]              ?? '';
        $this->stateCode                = $args["stateCode"]                ?? '';
        $this->destinationCode          = $args["destinationCode"]          ?? '';
        $this->categoryCode             = $args["categoryCode"]             ?? '';
        $this->categoryGroupCode        = $args["categoryGroupCode"]        ?? '';
        $this->accommodationTypeCode    = $args["accommodationTypeCode"]    ?? '';
        $this->email                    = $args["email"]                    ?? '';
        $this->license                  = $args["license"]                  ?? '';
        $this->S2C                      = $args["S2C"]                      ?? '';
        $this->web                      = $args["web"]                      ?? '';
        $this->ranking                  = $args["ranking"]                  ?? '';
    }
}