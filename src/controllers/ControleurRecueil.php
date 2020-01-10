<?php


namespace bibliovox\controllers;


use Slim\Slim;

class ControleurRecueil
{
    static function renderRecueils( $recs) {
        if ($recs != null)
        foreach ($recs as $r) {
            echo "<a href ='" . Slim::getInstance()->urlFor('recueils') . "?id=$r->idR'><h2>$r->nomR</h2></a>";
        }

    }

    public static function renderRecueil($rec, int $idU)
    {
        $date =explode('-', $rec->dateR);

        echo "<h1>Recueil: <i>$rec->nomR</i></h1>";
        echo "<div class='date'>Créé le: ". $date['2'] ."/". $date['1'] ."/". $date['0'] ."</div>";
        echo "<textarea readonly class='cite'>$rec->descriptionR</textarea>";


        //TODO
        //Ajouter une recherche suivant l'utilisateru connecté
        ControleurAudioRecueil::renderAudio($rec->idR, $idU);
    }
}