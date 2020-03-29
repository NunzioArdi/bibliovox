<?php


namespace bibliovox\controllers;


use bibliovox\models\Audio;
use bibliovox\models\AudioMot;
use bibliovox\models\AudioRecueil;

class ControleurAudioMot extends Controleur
{

    static function renderAudio(int $idR, int $idU)
    {

    }

    static function allAudioMot(int $idM)
    {
        return AudioMot::where("idM", "=", "$idM")->get();
    }

    public static function allSharedPrinter($idM) : string
    {
        $res = "";
        $auds =  AudioMot::where("idM", "=", "$idM")->where("partage", "=", "1")->get();
        foreach ($auds as $row){
            $audio = ControleurAudio::getPathById($row->idAudio);
            $date = explode('-', $audio->dateCreation);
            $an = $date[0];
            $mois = $date[1];
            $jour = explode(" ", $date[2])[0];

            $temp = "<p class='date'>Créé le: $jour / $mois / $an</p>";
            $temp .= "<audio controls>";
            $temp .= "<source src=' " . $GLOBALS["PATH"] . "/" . $audio->chemin . "' type='audio/mp3'>";
            $temp .= "</audio>";

            if ($row->partage == 1) {
                if ($audio->idU == ControleurCompte::getIdUser())
                    $res .= "<p><b>Ton exemple !</b></p>";
                else
                    $res .= "<p>Exemple de <b>" . ControleurUtilisateur::getNameById($audio->idU) . "</b></p>";
                $res .= $temp;
            }
        }
        return $res;
    }

    public function delete()
    {
        if (isset($_GET['idM']) && isset($_GET['idAudio'])) {
            Audio::deleteById($_GET['idAudio']);
            AudioMot::deleteByID($_GET['idAudio']);

            $idM = $_GET['idM'];
            $idD = 0;

            return $this->resp->withRedirect($GLOBALS["router"]->urlFor("mot", ["idM" => $idM, "idD" => $idD]));
        }
        return $this->resp->withRedirect($GLOBALS["router"]->urlFor("mot"));
    }


}