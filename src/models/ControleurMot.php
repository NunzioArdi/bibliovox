<?php


namespace bibliovox\models;


class ControleurMot
{
    static function renderMot(Mot $mot){
echo "<div class = 'mot'>";
echo "<h1>$mot->texte</h1>";

echo "<img src='" . PATH . "/media/img/img/" . $mot->image ."' alt='img'>";

echo "<br><audio controls>";
echo "<source src='" . PATH. "/media/aud/dico/" . $mot->audio ."' type='audio/mp3'>";
echo "</audio></div>";




    }

}