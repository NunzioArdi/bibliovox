<?php


namespace bibliovox\controllers;




use bibliovox\models\AudioRecueil;

class ControleurAudioRecueil
{

    static function renderAudio (int $idR, int $idU) {
        $ar = AudioRecueil::getByBoth($idR, $idU);
        if ($ar != null){
            echo "<div class='comm'>Ton enregistrement: </div>";
            echo "<audio controls>";
            echo "<source src='" . PATH . "/media/aud/rec/" . $ar->audio . "' type='audio/mp3'>";
            echo "</audio></div>";
        } else {
            echo "<div class='comm'>Eneregistre toi ! </div>";
        }

    }

}