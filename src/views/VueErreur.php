<?php

namespace bibliovox\views;

/**
 * Vue des pages  d'erreurs
 * @package bibliovox\views
 */
class VueErreur extends Vue
{
    /**
     * @inheritDoc
     */
    public function views(string $view)
    {
        switch ($view) {
            case 'createRecueil':
                $this->createRecueil();
                break;
            case 'idRecueil':
                $this->viewRecueil();
                break;
            case 'getDico':
                $this->getDico();
                break;
            case 'getMotDico':
                $this->getMotDico();
                break;
            case 'createDico':
                $this->createDico();
                break;
            case 'idProd':
                $this->viewProduction();
                break;
            case 'prodProcess':
                $this->productionProcess();
                break;
            default:
                break;
        }
        $this->afficher();
    }

    /**
     * Erreur quand la création d'un recueil échoue.
     * @return void
     */
    private function createRecueil()
    {
        $this->title = "Nouveau recueil";

        $this->content("<div class=\"erreur\">Erreur inconnue</div>");
    }

    /**
     * Erreur quand tentative d'accès à un recueil qui n'existe pas
     * @return void
     */
    private function viewRecueil()
    {
        $this->title = 'Page non trouvé';

        $this->content("<div class=\"erreur\">Le recueil n'existe pas</div>");
    }

    /**
     * Erreur quand tentative d'accès à un dictionnaire qui n'existe pas
     * @return void
     */
    private function getDico()
    {
        $this->title = 'Page non trouvé';

        $this->content("<div class=\"erreur\">Ce dictionnaire n'existe pas.</div>")
            ->content("<a href=\"" . $GLOBALS["router"]->urlFor('dictionnaires') . "\"><h1><- Retour</h1></a>");
    }

    private function getMotDico(){
        $this->content("<div class=\"erreur\">Ce mot n'existe pas dans ce dictionnaire")
            ->content("<a href=\"" . $GLOBALS["router"]->urlFor('dictionnaire_acces', ['idD' => $this->res]) . "\">Retour au dictionnaire.</a>");
    }

    /**
     * Erreur quand la création d'un dictionnaire échoue.
     * @return void
     */
    private function createDico()
    {
        switch ($this->res) {
            case 1:
                $this->content("<div class=\"erreur\">L'extension du fichier n'est pas autorisée</div>");
                break;
            default:
                $this->content("<div class=\"erreur\">Erreur inconnue</div>");
                break;
        }
    }

    /**
     * Erreur quand tentative d'accès à une production qui n'existe pas
     * @return void
     */
    private function viewProduction()
    {
        $this->title = 'Page non trouvé';

        $this->content("<div class=\"erreur\">Production inconnue.</div>");
    }

    /**
     * Erreur quand l'édition ou la création d'une production échoue.
     * @return void
     */
    private function productionProcess()
    {
        switch ($this->res) {
            case 1:
                $this->content("<div class=\"erreur\">L'extension du fichier n'est pas autorisée</div>");
                break;
            case 2:
                $this->content("<div class=\"erreur\">Aucun fichier uploadé</div>");
                break;
            default:
                $this->content("<div class=\"erreur\">Erreur inconnue</div>");
                break;

        }

        if(isset($this->res[1]))
            $this->content("<a href=\"" . $GLOBALS["router"]->urlFor('edit_production', ['idP' => $this->res[1]]) . "\"><h1><- Retour</h1></a>");
        else
            $this->content("<a href=\"" . $GLOBALS["router"]->urlFor('new_production') . "\"><h1><- Retour</h1></a>");

    }
}