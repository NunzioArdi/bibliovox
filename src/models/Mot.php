<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 */
class Mot extends Model
{
    public $timestamps = false;
    protected $table = 'mot';
    protected $primaryKey = 'idM';


    static function allAlpha()
    {
        return Mot::orderBy('texte', 'Asc')->get();
    }

    static function getByTxt(string $texte)
    {
        return Mot::where("texte", "=", "$texte")->first();
    }

    public static function getById(int $idM)
    {
        return Mot::where("idM", "=", "$idM")->first();
    }

    //TODO checker les paramètres nécessaires
    public static function createNew(String $texte)
    {
        $new = new Mot();
        $new->texte = filter_var($texte, FILTER_SANITIZE_STRING);
        if (isset($_FILES['image']) AND $_FILES['image']['error'] == 0) {
            $extension_upload = pathinfo($_FILES['image']['name'])['extension'];
            $extensions_autorisees = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'gif');
            if (in_array($extension_upload, $extensions_autorisees)) {
                $fileName = rand() . filter_var($_FILES['image']['name'], FILTER_SANITIZE_URL);
                move_uploaded_file($_FILES['image']['tmp_name'], 'media/img/img/mot/' . $fileName);

                $new->image = $fileName;
            } else {
                return 1;
            }

        } else $new->image = '';
        $new->save();

        return $new;
    }

    public static function supprimer(int $idM)
    {
        Mot::where("idM", "=", "$idM")->delete();
    }

    public static function updateMot(String $word, int $idM)
    {
        $mot = Mot::where("idM", "=", "$idM")->first();
        $mot->texte = $word;
        $mot->update();
    }

    public static function updatePic($idM)
    {
        $mot = Mot::where("idM", "=", "$idM")->first();
        $old = $mot->image;
        if (isset($_FILES['image']) AND $_FILES['image']['error'] == 0) {
            $extension_upload = pathinfo($_FILES['image']['name'])['extension'];
            $extensions_autorisees = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'gif');
            if (in_array($extension_upload, $extensions_autorisees)) {
                $fileName = rand() . filter_var($_FILES['image']['name'], FILTER_SANITIZE_URL);
                move_uploaded_file($_FILES['image']['tmp_name'], 'media/img/img/mot/' . $fileName);

                $mot->image = $fileName;
            } else {
                echo "aie";
                return 1;
            }
        }
        $mot->update();
        if (!is_null($old))
            unlink("media/img/img/mot" . $old);
    }

    public function audios()
    {
        return $this->belongsToMany("\bibliovox\models\Audio", "audioMot", "idM", "idAudio")->get();
    }
}