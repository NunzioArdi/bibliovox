<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static get()
 */
class Audio extends Model
{
    public $timestamps = false;
    protected $table = 'audio';
    protected $primaryKey = 'idAudio';

    static function newAudio(String $nomFichier, int $idU, String $commentaire = null){
        $n = new Audio();
        $n->idU = $idU;
        $n->chemin = "media/aud/" . $nomFichier;
        $n->commentaire = $commentaire;

        $n->save();
        return $n;
    }

    static function getAudio($id){
        $auds =  Audio::where("idU", "=", "$id")->get();
        $res[] = null;
        foreach ($auds as $r){
            array_push($res, $r->chemin);
        }
        return $auds;
    }
}