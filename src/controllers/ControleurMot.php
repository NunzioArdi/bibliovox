<?php


namespace bibliovox\controllers;


use bibliovox\models\Mot;

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

}