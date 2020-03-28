<?php


namespace bibliovox\controllers;




use bibliovox\models\Audio;
use bibliovox\models\AudioMot;
use bibliovox\models\AudioRecueil;

class ControleurAudioMot extends Controleur
{

    static function renderAudio (int $idR, int $idU) {

    }

    static function allAudioMot(int $idM) {
        return AudioMot::where("idM", "=", "$idM")->get();
    }

    public function delete()
    {
        if (isset($_GET['idM']) && isset($_GET['idAudio'])){
            Audio::deleteById($_GET['idAudio']);
            AudioMot::deleteByID($_GET['idAudio']);

            $idM = $_GET['idM'];
            $idD = 0;

            return $this->resp->withRedirect($GLOBALS["router"]->urlFor("mot", ["idM" => $idM, "idD" => $idD]));
        }
        return $this->resp->withRedirect($GLOBALS["router"]->urlFor("mot"));
    }


}