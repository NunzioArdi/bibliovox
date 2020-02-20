<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 */
class DicoContient extends Model
{

    public $timestamps = false;
    protected $table = 'dicoContient';

    public static function motContenuDico(int $idDico)
    {
        return DicoContient::where('idD', '=', "$idDico")->get();
    }

    public static function allDicoMot(int $idMot)
    {
        return DicoContient::where('idM', '=', "$idMot")->get();
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

    public static function create(int $idD, int $idM){
        $new = new DicoContient();
        $new->idD = $idD;
        $new->idM = $idM;

        $new->save();
    }

    public static function deleteBiIdM($idM)
    {
        DicoContient::where("idM", "=", "$idM")->delete();

    }
}