<?php

namespace bibliovox\controllers;

use bibliovox\models\Production;
use bibliovox\views\VueErreur;
use bibliovox\views\VueProduction;
use Slim\Http\Response;

/**
 * Controleur des productions
 * @package bibliovox\controllers
 * @todo accès au production des utilisateurs authentifier
 */
class ControleurProduction extends Controleur
{

    /**
     * Accès à toutes les productions d'un utilisateur
     * @return void
     */
    public function allProduction()
    {
        /* idU utilisé en attente de la fonction des comptes */


        $prods = Production::allCheck(ControleurCompte::getIdUser());
        $vue = new VueProduction([$prods, ControleurCompte::getIdUser()]);
        $vue->views('all');
    }

    /**
     * Accès à une production
     * @param int $idP l'id de la production
     * @return void
     */
    public function production(int $idP)
    {
        /* idU utilisé en attente de la fonction des comptes */
        $idU = ControleurCompte::getIdUser();

        if (Production::exist($idP)) {
            $prod = Production::getById($idP);
            $vue = new VueProduction($prod);
            $vue->views('prod');
        } else {
            $err = new VueErreur();
            $err->views('idProd');
        }
    }

    /**
     * Accès à l'édition d'une production
     * @param int $idP l'id de la production
     * @return void
     */
    public function editProduction(int $idP)
    {
        /* idU utilisé en attente de la fonction des comptes */
        $idU = ControleurCompte::getIdUser();

        if (Production::exist($idP)) {
            $url = $GLOBALS["router"]->urlFor("edit_production_process", ['idP' => $idP]) . "?idU=$idU";
            $prod = Production::getById($idP);
            $vue = new VueProduction([$prod, $url]);
            $vue->views('editProd');
        } else {
            $err = new VueErreur();
            $err->views('idProd');
        }
    }

    public function createProduction()
    {
        /* idU utilisé en attente de la fonction des comptes */
        $idU = ControleurCompte::getIdUser();

        $vue = new VueProduction($idU);
        $vue->views('create');
    }

    /**
     * Traitement des informations de la création d'une production
     * @return Response réponse slim
     */
    public function processEditProduction($idP): Response
    {
        /* idU utilisé en attente de la fonction des comptes */
        $idU = ControleurCompte::getIdUser();

        $res = Production::updateProd($idP, $_POST['nom'], $idU, $_POST['comm']);

        if (is_int($res)) {
            $err = new VueErreur([$res, $idP]);
            $err->views('prodProcess');
            return $this->resp->withStatus(500);
        } else {
            return $this->resp->withRedirect($GLOBALS["router"]->urlFor('productions') . "$idP");
        }
    }

    public function processCreateProduction()
    {
        /* idU utilisé en attente de la fonction des comptes */
        $idU = ControleurCompte::getIdUser();

        $idAudio = ControleurAudio::createAudio($idU);

        $res = Production::createNew($this->req->getParsedBody()['nom'], $idAudio);

        if (is_int($res)) {
            $err = new VueErreur($res);
            $err->views('prodProcess');
            return $this->resp->withStatus(500);
        } else
            return $this->resp->withRedirect($GLOBALS["router"]->urlFor("productions"));

    }

    public function delete()
    {
        if (isset($_GET['idP']))
            Production::remove($_GET['idP']);

        return $this->resp->withRedirect($GLOBALS["router"]->urlFor("productions"));
    }

}