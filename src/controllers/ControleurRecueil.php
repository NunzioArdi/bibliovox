<?php

namespace bibliovox\controllers;

use bibliovox\models\Recueil;
use bibliovox\views\VueErreur;
use bibliovox\views\VueRecueil;
use Slim\Http\Response;

/**
 * Controleur des recueils
 *
 * @package bibliovox\controllers
 */
class ControleurRecueil extends Controleur
{
    /**
     * Accès à un recueil
     * @param $id l'id du recueil
     * @return void
     */
    public function recueil($id)
    {

        if (Recueil::exist($id)) {
            $rec = Recueil::getById($id);
            $vue = new VueRecueil(json_decode($rec));
            $vue->views('recueil');
        } else {
            $err = new VueErreur();
            $err->views('idRecueil');
        }
    }

    /**
     * Accès à tous les recueils
     * @return void
     */
    public function allRecueil()
    {
        $rec = Recueil::all();
        $vue = new VueRecueil(json_decode($rec));
        $vue->views('allrecueil');

    }

    /**
     * Accès à la création d'un recueil
     * @return void
     */
    public function creerRecueil()
    {
        $vue =  new VueRecueil();
        $vue->views('creer');
    }

    /**
     * Traitement des informations de la création du recueil
     * @return Response réponse slim
     */
    public function processCreate() : Response
    {
        try {
            $res = Recueil::createNew($_POST['nom'], $_POST['texte']);
            return $this->resp->withRedirect($GLOBALS["router"]->urlFor("recueils") . "$res->idR");
        }catch (\Exception $e){
            $err = new VueErreur();
            $err->views('createRecueil');
            return $this->resp->withStatus(500);
        }

    }
}