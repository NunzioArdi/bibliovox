<?php

namespace bibliovox\controllers;

use bibliovox\models\DicoContient;
use bibliovox\models\Dictionnaire;
use bibliovox\models\Mot;
use bibliovox\views\VueDico;
use bibliovox\views\VueErreur;
use Slim\Http\Response;

/**
 * Controleur des dictionnaires
 * @package bibliovox\controllers
 */
class ControleurDictionnaire extends Controleur
{

    /**
     * Accès à tous les dictionnaires
     * @return void
     */
    public function allDico()
    {
        $dico = Dictionnaire::all();
        $vue = new VueDico($dico);
        $vue->views('all');
    }

    /**
     * Accès à la création des dictionnaires
     * @return void
     * @todo Accès à cette fonctionnalité en fonction des privilège du compte
     */
    public function createDico()
    {
        $vue = new VueDico();
        $vue->views('createDico');
    }

    /**
     * Accès au dictionnaire alphabétique
     * @return void
     */
    public function getDicoAlphabet()
    {
        $mots = Mot::allAlpha();
        $vue = new VueDico($mots);
        $vue->views('alphabet');
    }

    /**
     * Accès au dictionnaire thématique
     * @param int $idDico l'id du thème
     * @return void
     */
    public function getDicoTheme(int $idDico)
    {
        $dico = Dictionnaire::getId($idDico);

        if ($dico != null) {
            $rep = [$dico->idD, $dico->nomD, [] ];
            $mots = DicoContient::motContenuDico($idDico);
            foreach ($mots as $m) {
                array_push($rep[2], json_decode($mot = Mot::getById($m->idM)));
            }
            $vue = new VueDico($rep);
            $vue->views("theme");
        } else {
            $err = new VueErreur();
            $err->views('getDico');
        }
    }

    /**
     * Traitement des informations de la création d'un dictionnaire
     * @return Response réponse slim
     */
    public function processCreate() : Response
    {
        $res = Dictionnaire::createNew($_POST['nom'], $_POST['description']);

        if (is_int($res)) {
            $err = new VueErreur([$res]);
            $err->views('createDico');
            return $this->resp->withStatus(500);
        }
        else
            return $this->resp->withRedirect($GLOBALS["router"]->urlFor("dictionnaire_acces") . "$res->idD");
    }

}