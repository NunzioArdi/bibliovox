<?php

namespace bibliovox\models;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $nomColone, string $mode)
 * @method static where(string $nomColone, string $comparateur, string $valeur)
 */
class Dictionnaire extends Model {

    protected $table = 'dictionnaire';
    protected $primaryKey = 'idD';

    static function getId(int $id) {
        return Dictionnaire::all()->where("idD", "=", "$id")->first();
    }



}