<?php

namespace bibliovox\models;
use Illuminate\Database\Eloquent\Model;

class Dictionnaire extends Model {

    protected $table = 'dictionnaire';
    protected $primaryKey = 'idD';

    static function getId(int $id) {
        return Dictionnaire::all()->where("idD", "=", "$id")->first();
    }



}