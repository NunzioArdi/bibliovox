<?php


namespace bibliovox\controllers;




use bibliovox\models\AudioRecueil;

class ControleurAudioRecueil
{

    static function audioPerso (int $idR, int $idU) : string {
        $ret = "";
        $ar = AudioRecueil::where("idR", "=", "$idR")->where("idU", "=", "$idU")->get();
        foreach ($ar as $row){
            $aud = ControleurAudio::getPathById($row->idAudio);

            $date = explode('-', $aud->dateCreation);
            $an = $date[0];
            $mois = $date[1];
            $jour = explode(" ", $date[2])[0];

            $ret .= "<p class='date'>Créé le: $jour / $mois / $an</p>";
            $ret .= "<audio controls>";
            $ret .= "<source src=' " . $GLOBALS["PATH"] . "/" . $aud->chemin . "' type='audio/mp3'>";
            $ret .= "</audio>";
        }

        if ($ret == "")
            $ret = "<p>Tu n'as pas encore d'enregistrement.</p><p>Enregistre-toi tout de suite !</p>";

        return $ret;
    }

}