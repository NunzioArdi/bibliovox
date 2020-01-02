<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

class DicoContient extends Model
{
    protected $table = 'dicoContient';

    protected static function motContenuDico(int $idDico) {
        return DicoContient::where('idD', '=', "$idDico")->get();
    }
}