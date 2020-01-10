<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 */
class Production extends Model
{

    protected $table = 'production';
    protected $primaryKey = 'idP';

    static function getById(int $idP)
    {
        return Production::where("idP", "=", "$idP")->first();
    }

    public static function allCheck(int $idU)
    {
        return Production::where("idU", "=", "$idU")->get();
    }

    public static function exist($idP, $idU)
    {
        return Production::where("idP", "=", "$idP")->where("idU", "=", "$idU")->first() != null;
    }
}