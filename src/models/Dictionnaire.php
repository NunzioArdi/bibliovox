<?php

namespace bibliovox\models;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 */
class Dictionnaire extends Model
{

    protected $table = 'dictionnaire';
    protected $primaryKey = 'idD';

    static function getId(int $id)
    {
        return Dictionnaire::all()->where("idD", "=", "$id")->first();
    }

    static function createNew(string $nom, string $description)
    {
        $newDico = new Dictionnaire();
        $newDico->nomD = filter_var($nom, FILTER_SANITIZE_STRING);
        $newDico->descriptionD = filter_var($description, FILTER_SANITIZE_STRING);

        if (isset($_FILES['Image']) AND $_FILES['Image']['error'] == 0) {
            $extension_upload = pathinfo($_FILES['Image']['name'])['extension'];
            $extensions_autorisees = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG');

            if (in_array($extension_upload, $extensions_autorisees)) {
                $fileName = rand() . filter_var($_FILES['Image']['name'], FILTER_SANITIZE_URL);
                move_uploaded_file($_FILES['Image']['tmp_name'], 'media/img/dico/' . $fileName);

                $newDico->imageD = $fileName;
            } else {
                return "L'extension du fichier n'est pas autorisé ('jpg', 'jpeg', 'png', 'JPG', 'JPEG' uniquement)";
            }
        }

        $newDico->save();
        return $newDico;
    }

}