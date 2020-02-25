<?php


namespace bibliovox\models;


use Exception;
use Illuminate\Database\Eloquent\Model;
use PDOException;

/**
 * Modèle MVC de Recueil
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 */
class Recueil extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'idR';
    protected $table = 'recueil';

    /**
     * Test si un id de recueil existe
     *
     * @param int $idR id recueil
     * @return bool true si recueil existe
     */
    static function exist(int $idR): bool
    {
        return (Recueil::where("idR", "=", "$idR")->first() != null);
    }


    /**
     * Récupère un recueil avec son id
     * @param int $idR l'id
     * @return object un Recueil
     */
    static function getById(int $idR)
    {
        return Recueil::where("idR", "=", "$idR")->first();
    }

    public static function allCheck(int $idU)
    {
        return Recueil::where("idU", "=", "$idU");
    }

    /**
     * Créer un nouveau recueil dans la basse de données
     * @param string $nom le nom du recueil
     * @param string $texte le contenu du recueil
     * @return object Le recueil créé
     * @throws Exception Erreur creation dans la base de données
     */
    public static function createNew(string $nom, string $texte)
    {
        /** @var bool $res si créer dans BBD */
        $res = false;
        $newRecueil = new Recueil();
        $newRecueil->nomR = filter_var($nom, FILTER_SANITIZE_STRING);
        $newRecueil->descriptionR = filter_var($texte, FILTER_SANITIZE_STRING);
        $newRecueil->dateR = date('Y-m-d');

        try {
            $res = $newRecueil->save();
        } catch (PDOException $e) {
        }

        if ($res == false) {
            throw new Exception('Le recueil n\'a pu être créé');
        }
        return $newRecueil;
    }

}

