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
}