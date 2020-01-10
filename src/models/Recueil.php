<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 */
class Recueil extends Model
{
    protected $primaryKey = 'idR';
    protected $table = 'recueil';

    static function exist (int $idR) {
        return (Recueil::where("idR", "=", "$idR")->first() != null);
    }

    static function getById (int $idR) {
        return Recueil::where("idR", "=", "$idR")->first();
    }

    public static function allCheck(int $idU)
    {
        return Recueil::where("idU", "=", "$idU");
    }

}

