<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 */
class AudioRecueil extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'audioRec';

    static function getByBoth (int $idR, int $idU) {
        return AudioRecueil::where("idR", "=", "$idR")->where("idU", "=", "$idU")->first();
    }

}