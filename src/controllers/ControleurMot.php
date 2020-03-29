<?php


namespace bibliovox\controllers;

use bibliovox\models\Audio;
use bibliovox\models\AudioMot;
use bibliovox\models\DicoContient;
use bibliovox\models\Dictionnaire;
use bibliovox\models\Mot;
use bibliovox\views\VueErreur;
use bibliovox\views\VueMot;

class ControleurMot extends Controleur
{

    public function updatePic($idD, $idM)
    {
        Mot::updatePic($idM);
        return $this->resp->withRedirect($GLOBALS["router"]->urlFor("mot", ["idD" => $idD, "idM" => $idM]));

    }

    public function getMotDico(int $idM, int $idD)
    {
        $mot = Mot::getById($idM);
        if ($mot != null && (DicoContient::matchIDs($idM, $idD) || $idD == 0)) {
            $vue = new VueMot($mot);
            $vue->motDico();
        }
        else {
           $err = new VueErreur($idD);
           $err->views('getMotDico');
        }
    }

    public function createMot($idD)
    {
        $dico = Dictionnaire::all();
        $vue = new VueMot(["idD" => $idD, "dico" => $dico]);
        $vue->creeMot();
    }

    public function processCreateMot()
    {
        $res = ControleurAudio::createAudio(ControleurCompte::getIdUser(), '');
        $ret = Mot::createNew($_POST['mot']);


        if (isset($_POST['cbnumber'])) {
            for ($i = 0; $i < intval($_POST['cbnumber']); $i++){
                if (isset($_POST[$i])){
                    AudioMot::createNew(Audio::getIdByPath($_POST[$i]), $ret->idM, true);
                }
            }
        }


        AudioMot::createNew($res, $ret->idM, false);
        if (is_int($ret)){
            //TODO lancer erreur
        } else {
            if (isset($_POST['dico'])){
                foreach ($_POST['dico'] as $d) {
                    DicoContient::create($d, $ret->idM);
                }
            }else  {
                //TODO lancer erreur
            }
        }
        return $this->resp->withRedirect($GLOBALS["router"]->urlFor("mot", ["idD" => -1, "idM" => $ret->idM]));

    }

    public function deleteMot(int $idM) {
        ControleurDicoContient::deleteMot($idM);
        Mot::supprimer($idM);
        return $this->resp->withRedirect($GLOBALS["router"]->urlFor("dictionnaires"));
    }

}