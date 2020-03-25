<?php


namespace bibliovox\controllers;




use bibliovox\models\Audio;
use bibliovox\models\AudioRecueil;

class ControleurAudioRecueil extends Controleur
{

    static function allAudioRec (int $idR) {
        return AudioRecueil::where("idR", "=", "$idR")->get();
    }

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

    static function audioPartage (int $idR) : string {
        $ret = "";
        $ar = AudioRecueil::where("idR", "=", "$idR")->where("partage", "=", "1")->get();

        foreach ($ar as $row){
            $aud = ControleurAudio::getPathById($row->idAudio);

            $date = explode('-', $aud->dateCreation);
            $an = $date[0];
            $mois = $date[1];
            $jour = explode(" ", $date[2])[0];

            $ret .= "<p>Exemple de <b>" . ControleurUtilisateur::getNameById($aud->idU) . "</b></p>";
            $ret .= "<p class='date'>Créé le: $jour / $mois / $an</p>";
            $ret .= "<audio controls>";
            $ret .= "<source src=' " . $GLOBALS["PATH"] . "/" . $aud->chemin . "' type='audio/mp3'>";
            $ret .= "</audio>";
        }

        if ($ret == "")
            $ret = "<p>Aucun exemple pour l'instant.</p><p>Tu peux demander à ta maitresse ou à ton maitre de te choisir comme exemple !</p>";

        return $ret;
    }

    public function delete()
    {
        if (isset($_GET['idAudio'])){
            $idAudio = $_GET['idAudio'];
            Audio::deleteById($idAudio);
            $rec = AudioRecueil::where("idAudio", "=", "$idAudio")->first();
            echo $rec->idAudio;
            $rec->forceDelete();
        }

        $rec = "";
        if (isset($_GET['idR']))
            $rec = $_GET['idR'];

            return $this->resp->withRedirect($GLOBALS["router"]->urlFor("recueils") . $rec);
    }

}