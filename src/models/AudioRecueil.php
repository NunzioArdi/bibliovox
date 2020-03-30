<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 */
class AudioRecueil extends Pivot
{
    protected $primaryKey = 'idAudio';
    protected $table = 'audioRec';
    public $timestamps = false;

    public static function createNew(int $idA, int $idR, int $idU, bool $partage)
    {
        $aud = new AudioRecueil();
        $aud->idAudio = $idA;
        $aud->idR = $idR;
        $aud->idU = $idU;

        if ($partage)
            $aud->partage = 1;

        $aud->save();
    }

    static function getByBoth(int $idR, int $idU)
    {
        return AudioRecueil::where("idR", "=", "$idR")->where("idU", "=", "$idU")->first();
    }

    public static function updatePartage(string $id, int $shared)
    {
        $aud = AudioRecueil::where("idAudio", "=", "$id")->first();
        $aud->partage = $shared;
        $aud->update();
    }

    public static function deleteByID($id)
    {
        $aud = AudioRecueil::where("idAudio", "=", "$id")->first();
        if (isset($aud))
            $aud->forceDelete();
    }

}