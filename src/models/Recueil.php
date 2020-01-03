<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

class Recueil extends Model
{
    protected $primaryKey = 'idR';
    protected $table = 'recueil';

    static function exist (int $idR) {
        return (Recueil::where("idR", "=", "$idR")->first() != null);
    }

    static function getById (int $idR) {
        return Recueil::where("idR", "=", "$idR")->first();
    }

}

