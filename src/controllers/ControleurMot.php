<?php


namespace bibliovox\controllers;

use bibliovox\models\Audio;
use bibliovox\models\AudioMot;
use bibliovox\models\DicoContient;
use bibliovox\models\Dictionnaire;
use bibliovox\models\Mot;
use bibliovox\views\VueErreur;
use bibliovox\views\VueMot;
use Slim\Http\Response;

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

    /**
     * Accès à l'interface de création de mot
     * @param $idD
     * @return Response|void
     */
    public function createMot($idD)
    {
        if(ControleurCompte::isTeatch()){
            $dico = Dictionnaire::all();
            $vue = new VueMot(["idD" => $idD, "dico" => $dico]);
            $vue->creeMot();
        }else{
            return $this->resp->withRedirect($GLOBALS["router"]->urlFor("dictionnaire_acces", ['idD' => 0]));
        }
        return;
    }

    public function processCreateMot()
    {

        $ret = Mot::createNew($_POST['mot']);

        if (isset($_FILES['newAudio']) AND $_FILES['newAudio']['error'] == 0) {
            $res = ControleurAudio::createAudio(ControleurCompte::getIdUser(), '');

            AudioMot::createNew($res, $ret->idM, true);
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
        }

        if (isset($_POST['cbnumber'])) {
            for ($i = 0; $i < intval($_POST['cbnumber']); $i++){
                if (isset($_POST[$i])){
                    AudioMot::createNew(Audio::getIdByPath($_POST[$i]), $ret->idM, true);
                }
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