<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 */
class AudioRecueil extends Model
{
    protected $primaryKey = 'idAudio';
    protected $table = 'audioRec';

    static function getByBoth (int $idR, int $idU) {
        return AudioRecueil::where("idR", "=", "$idR")->where("idU", "=", "$idU")->first();
    }

}