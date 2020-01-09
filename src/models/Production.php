<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

class Production extends Model
{

    protected $table = 'production';
    protected $primaryKey = 'idP';

    static function getById(int $idP)
    {
        return Production::where("idP", "=", "$idP")->first();
    }

    public static function allCheck(int $idU)
    {
        return Production::where("idU", "=", "$idU")->get();
    }

    public static function exist($idP, $idU)
    {
        return Production::where("idP", "=", "$idP")->where("idU", "=", "$idU")->first() != null;
    }
}