<?php


namespace bibliovox\controllers;

use bibliovox\models\DicoContient;
use bibliovox\models\Dictionnaire;
use bibliovox\models\Mot;
use bibliovox\views\VueErreur;
use bibliovox\views\VueMot;

class ControleurMot extends Controleur
{

    public function getMotDico(int $idM, int $idD)
    {
        $mot = Mot::getById($idM);
        if ($mot != null && (DicoContient::matchIDs($idM, $idD) || $idD == 0)) {
            $vue = new VueMot($mot);
            $vue->views('motDico');
        }
        else {
           $err = new VueErreur($idD);
           $err->views('getMotDico');
        }
    }

    public function createMot($idD)
    {
        $dico = Dictionnaire::all();
        $vue = new VueMot(["idD" => $idD,"dico" => $dico]);
        $vue->views("createMot");
    }

    public function processCreateMot()
    {
        $res = ControleurAudio::createAudio(1, '');
        $ret = Mot::createNew($_POST['mot'], $res, '');
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

}