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

    static function getAll() {
        return Production::orderBy("dateP", "DESC")->get();
    }

    public static function allCheck(int $idU)
    {
        $res = [];
        foreach (Production::orderBy("dateP", "DESC")->get() as $prod) {
            $audio = $prod->audio();
            if ($audio != null && $audio->idU == $idU) {
                array_push($res, $prod);
            }
        }

        return $res;
    }

    /**
     * Test si une production donné pour un utilisateur donné existe
     * @param $idP int l'id de la production
     * @return bool true si existe
     */
    public static function exist(int $idP)
    {
        return Production::where("idP", "=", "$idP")->first() != null;
    }

    public static function createNew(string $nom, int $idAudio)
    {
        $newProduction = new Production();

        $newProduction->idAudio = $idAudio;
        $newProduction->nomP = filter_var($nom, FILTER_SANITIZE_STRING);
        $newProduction->save();

        return $newProduction;

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

    public static function remove($idP)
    {
        $prod = Production::where("idP", "=", "$idP")->first();
        Audio::deleteById($prod->idAudio);
        $prod->forceDelete();
    }

    public function audio()
    {
        return $this->belongsTo("\bibliovox\models\Audio", "idAudio")->first();
    }

}