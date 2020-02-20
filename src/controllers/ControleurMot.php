<?php


namespace bibliovox\controllers;


use bibliovox\models\DicoContient;
use bibliovox\models\Mot;
use bibliovox\views\VueMot;

class ControleurMot
{
    static function renderMot(Mot $mot)
    {
        echo "<div class = 'mot'>";
        echo "<h1>$mot->texte</h1>";
        if ($mot->image != null)
        echo "<img src='" . PATH . "/media/img/img/mot/" . $mot->image . "' alt='img'>";

        if ($mot->audio != null) {
            echo "<audio controls>";
            echo "<source src='" . PATH . "/media/aud/dico/" . $mot->audio . "' type='audio/mp3'>";
            echo "</audio></div>";
        } else {
            echo "<h2>Enregistre toi !</h2>";
            //TODO
            //Appel à l'enregistreur
        }

    }

    public function getMotDico(int $idM, int $idD)
    {
        $mot = Mot::getById($idM);
        if ($mot != null && (DicoContient::matchIDs($idM, $idD) || $idD==0)) {
//        echoHead($mot->texte);
//        ControleurMot::renderMot($mot);
            $vue = new VueMot($mot);
            $vue->views('motDico');
        }
        //TODO erreur
else {
        echoHead('ERREUR');
        echo "<div class='erreur'>Ce mot n'existe pas dans ce dictionnaire ";
        echo "<a href='" . $GLOBALS["router"]->urlFor('dictionnaires') . "'>Retour aux dictionnaires.</a>";
    }
    }

}