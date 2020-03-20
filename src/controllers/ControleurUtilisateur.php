<?php


namespace bibliovox\controllers;


use bibliovox\models\Utilisateur;

class ControleurUtilisateur
{
    public static function getNameById (int $id) :string {
        $us = Utilisateur::where("idU", "=", "$id")->first();
        return $us->prenom . " " . strtoupper($us->nom);
}
}