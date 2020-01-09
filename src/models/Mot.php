<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

class Mot extends Model
{
    protected $table = 'mot';
    protected $primaryKey = 'idM';


    static function allAlpha() {
        return Mot::orderBy('texte', 'Asc')->get();
    }

    static function getByTxt(string $texte) {
        return Mot::where("texte", "=", "$texte")->first();
    }

    public static function getById(int $idM)
    {
        return Mot::where("idM", "=", "$idM")->first();
    }
}