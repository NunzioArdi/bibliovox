<?php


namespace bibliovox\controllers;


use bibliovox\models\Recueil;

class ControleurRecueil
{
    static function renderRecueils( $recs) {
        if ($recs != null)
        foreach ($recs as $r) {
            echo "<a href ='".PATH."/recueil?id=$r->idR'><h2>$r->nomR</h2></a>";
        }

    }

    public static function renderRecueil($rec)
    {
        $date =explode('-', $rec->dateR);

        echo "<h1>Recueil: <i>$rec->nomR</i></h1>";
        echo "<div class='date'>Créé le: ". $date['2'] ."/". $date['1'] ."/". $date['0'] ."</div>";
        echo "<textarea class='cite'>$rec->descriptionR</textarea>";
        echo "<div class='comm'>Ton enregistrement: </div>";

        //TODO
        //Ajouter une recherche suivant l'utilisateru connecté
        ControleurAudioRecueil::renderAudio($rec->idR, 1);
    }
}