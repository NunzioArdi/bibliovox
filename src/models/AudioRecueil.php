<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

class AudioRecueil extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'audioRec';

    static function getByBoth (int $idR, int $idU) {
        return AudioRecueil::where("idR", "=", "$idR")->where("idU", "=", "$idU")->first();
    }

}