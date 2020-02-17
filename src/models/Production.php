<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 * @method get()
 */
class Production extends Model
{
    public $timestamps = false;
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

    /**
     * Test si une production donné pour un utilisateur donné existe
     * @param $idP int l'id de la production
     * @param $idU int l'id utilisateur
     * @return bool true si existe
     */
    public static function exist(int $idP,int $idU)
    {
        return Production::where("idP", "=", "$idP")->where("idU", "=", "$idU")->first() != null;
    }

    public static function createNew(string $nom, int $idUtilisateur)
    {
        /* Futur Test de l'utilisateur ici*/
        if (true) {
            if (isset($_FILES['audio']) AND $_FILES['audio']['error'] == 0) {
                $extension_upload = pathinfo($_FILES['audio']['name'])['extension'];
                $extensions_autorisees = array('mp3');
                if (in_array($extension_upload, $extensions_autorisees)) {
                    $fileName = rand() . filter_var($_FILES['audio']['name'], FILTER_SANITIZE_URL);
                    move_uploaded_file($_FILES['audio']['tmp_name'], 'media/aud/prod/' . $fileName);

                    $newProduction = new Production();

                    $newProduction->audio = $fileName;
                    $newProduction->nomP = filter_var($nom, FILTER_SANITIZE_STRING);
                    $newProduction->idU = $idUtilisateur;
                    $newProduction->dateP = date('Y-m-d');
                    $newProduction->save();
                    return $newProduction->get()->last();
                } else {
                    return 1;
                }

            } else return 2;
        }
    }

    public static function updateProd(int $idProduction, string $nom, int $idUtilisateur, string $comm)
    {
        $prod = Production::where("idP", "=", "$idProduction")->first();

        /* Futur Test de l'utilisateur ici cad Prof seulement*/
        if (true) {
            echo "TTTT";
            $prod->commentaire = filter_var($comm, FILTER_SANITIZE_STRING);
            /* Futur Test de l'utilisateur ici cad élève ou TODO prof*/
        }
        if (true OR self::allCheck($idUtilisateur)) {
            $prod->nomP = filter_var($nom, FILTER_SANITIZE_STRING);
            echo "OUI";
            if (isset($_FILES['audio']) AND $_FILES['audio']['error'] == 0) {
                $extension_upload = pathinfo($_FILES['audio']['name'])['extension'];
                $extensions_autorisees = array('mp3');
                if (in_array($extension_upload, $extensions_autorisees)) {
                    $fileName = rand() . filter_var($_FILES['audio']['name'], FILTER_SANITIZE_URL);
                    move_uploaded_file($_FILES['audio']['tmp_name'], 'media/aud/prod/' . $fileName);
                    $prod->audio = $fileName;
                } else {
                    return 1;
                }
            }
            $prod->update();
            return $prod;
        }
        return 2;
    }

}