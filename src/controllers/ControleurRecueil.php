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
        echo "<h1>Recueil: <i>$rec->nomR</i></h1>";
        echo "<div class='date'>Créé le: $rec->dateR</div>";
        echo "<cite>$rec->descriptionR</cite>";
        echo "<p>Ton enregistrement: </p>";

        //TODO
        //Ajouter une recherche suivant l'utilisateru connecté
        ControleurAudioRecueil::renderAudio($rec->idR, 1);
    }
}