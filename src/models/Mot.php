<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

class Mot extends Model
{
    protected $table = 'mot';
    //Pas de primary Key car string posera pb

    static function allAlpha() {
        return Mot::orderBy('texte', 'Desc')->get();
    }

    static function getByTxt(string $texte) {
        return Mot::where("texte", "=", "$texte")->first();
    }
}