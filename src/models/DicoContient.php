<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 */
class DicoContient extends Model
{
    protected $table = 'dicoContient';

    public static function motContenuDico(int $idDico)
    {
        return DicoContient::where('idD', '=', "$idDico")->get();
    }

    public static function matchIDs(int $idMot, int $idDico): bool
    {
        if ($idDico == -1) {
            return true;
        }
        else {
            return DicoContient::where('idD', '=', "$idDico")->where("idM", "=", "$idMot")->first() != null;
        }
    }
}