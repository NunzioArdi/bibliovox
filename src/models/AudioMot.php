<?php


namespace bibliovox\models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AudioMot extends Pivot
{
    public $timestamps = false;
    protected $table = 'audioMot';
    protected $primaryKey = 'idAudio';

    public static function createNew(int $idA, int $idM)
    {
        $aud = new AudioMot();
        $aud->idAudio = $idA;
        $aud->idM = $idM;

        $aud->save();
    }

    public static function deleteByID($id)
    {
        $aud = AudioMot::where("idAudio", "=", "$id")->first();
        $aud->forceDelete();
    }


}