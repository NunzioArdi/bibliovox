<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 * @method get()
 */
class Recueil extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'idR';
    protected $table = 'recueil';

    static function exist(int $idR)
    {
        return (Recueil::where("idR", "=", "$idR")->first() != null);
    }

    static function getById(int $idR)
    {
        return Recueil::where("idR", "=", "$idR")->first();
    }

    public static function allCheck(int $idU)
    {
        return Recueil::where("idU", "=", "$idU");
    }

    public static function createNew(string $nom, string $texte)
    {
        $newRecueil = new Recueil();
        $newRecueil->nomR = filter_var($nom, FILTER_SANITIZE_STRING);
        $newRecueil->descriptionR = filter_var($texte, FILTER_SANITIZE_STRING);
        $newRecueil->dateR = date('Y-m-d');
        $newRecueil->save();
        return $newRecueil->get()->last();
    }

}

