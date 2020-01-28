<?php

namespace bibliovox\models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 * @method get()
 */
class Dictionnaire extends Model
{
    public $timestamps = false;
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

        if (isset($_FILES['image']) AND $_FILES['image']['error'] == 0) {
            $fileName = rand() . filter_var($_FILES['image']['name'], FILTER_SANITIZE_URL);
            move_uploaded_file($_FILES['image']['tmp_name'], 'media/img/img/dico/' . $fileName);

            $newDico->imageD = $fileName;

        } else $newDico->imageD = '';

        $newDico->save();
        return $newDico->get()->last();
    }

}