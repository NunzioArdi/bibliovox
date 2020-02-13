<?php


namespace bibliovox\controllers;


use bibliovox\models\Recueil;
use bibliovox\views\VueRecueil;

class ControleurRecueil
{
    public static function renderRecueil($rec, int $idU)
    {
        $date = explode('-', $rec->dateR);

        echo "<h1>Recueil: <i>$rec->nomR</i></h1>";
        echo "<div class='date'>Créé le: " . $date['2'] . "/" . $date['1'] . "/" . $date['0'] . "</div>";
        echo "<textarea readonly class='cite'>$rec->descriptionR</textarea>";


        //TODO
        //Ajouter une recherche suivant l'utilisateru connecté
        ControleurAudioRecueil::renderAudio($rec->idR, $idU);
    }

    public function recueil($id)
    {
        if (Recueil::exist($id)) {
            $rec = Recueil::getById($id);
            $vue = new VueRecueil(json_decode($rec));
            $vue->views('recueil');
        } else {
            //TODO erreur
            $rec = Recueil::getById($_GET['id']);
            echoHead($rec->nomR);
            ControleurRecueil::renderRecueil($rec, 1);
        }
    }

    public function allRecueil()
    {
        $rec = Recueil::all();
        $vue = new VueRecueil(json_decode($rec));
        $vue->views('allrecueil');

    }

    public function creerRecueil()
    {
        $vue =  new VueRecueil();
        $vue->views('creer');
    }
}