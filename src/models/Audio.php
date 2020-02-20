<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    public $timestamps = false;
    protected $table = 'audio';
    protected $primaryKey = 'idAudio';

    static function newAudio(String $nomFichier, int $idU, String $commentaire = null){
        $n = new Audio();
        $n->idU = $idU;
        //$n->dateCreation = time();
        $n->chemin = "media/aud/" . $nomFichier;
        $n->commentaire = $commentaire;

        $n->save();
        return Audio::get()->last();
    }
}