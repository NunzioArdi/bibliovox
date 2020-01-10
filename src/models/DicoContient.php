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

    protected static function motContenuDico(int $idDico) {
        return DicoContient::where('idD', '=', "$idDico")->get();
    }
}