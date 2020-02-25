<?php


namespace bibliovox\models;


use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'idU';
    protected $table = 'utilisateur';

    static function getID($char) {
        $res[] = null;
        $tmp = Utilisateur::where("nom", "=", "$char")->get();
        foreach ($tmp as $r){
            array_push($res, $r->idU);
        }

        $tmp = Utilisateur::where("prenom", "=", "$char")->get();
        foreach ($tmp as $r){
            array_push($res, $r->idU);
        }

        $tmp = Utilisateur::where("mail", "=", "$char")->get();
        foreach ($tmp as $r){
            array_push($res, $r->idU);
        }

        $tmp = Utilisateur::where("idU", "=", "$char")->get();
        foreach ($tmp as $r){
            array_push($res, $r->idU);
        }

        return $res;
    }
}