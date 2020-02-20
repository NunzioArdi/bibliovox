<?php


namespace bibliovox\views;


class VueRecueil extends Vue
{
    public function views(string $view)
    {
        switch ($view) {
            case 'recueil':
                $this->recueil();
                break;
            case 'allrecueil':
                $this->allRecueil();
                break;
            case 'creer':
                $this->create();
                break;
            default:
                break;
        }
        $this->afficher();
    }

    private function recueil()
    {
        $rec = $this->res;

        $this->title = $rec->nomR;

        $date = explode('-', $rec->dateR);

        $this->content .= "<h1>Recueil: <i>$rec->nomR</i></h1>";
        $this->content .= "<div class='date'>Créé le: " . $date['2'] . "/" . $date['1'] . "/" . $date['0'] . "</div>";
        $this->content .= "<textarea readonly class='cite'>$rec->descriptionR</textarea>";


        //TODO
        //Ajouter une recherche suivant l'utilisateru connecté
        //ControleurAudioRecueil::renderAudio($rec->idR, $idU);
    }

    /**
     * Affiche tout les recueil
     */
    private function allRecueil()
    {
        $this->title = "Recueils";

        $this->content = "<h1>Tous les recueils</h1>";
        if ($this->res != null)
            foreach ($this->res as $r) {
                $this->content .= "<a href =\"" . $GLOBALS["router"]->urlFor('recueils') . "$r->idR\"><h2>$r->nomR</h2></a>";
            }
        $this->content .= "<div class=\"createNew\"><a href=\" " . $GLOBALS["router"]->urlFor("new_recueil") . "  \">Nouveau Recueil</a>";
    }

    private function create()
    {
        $this->title = "Nouveau recueil";

        $this->content .= "<h1>Créer un nouveau recueil</h1>";
        $path = $GLOBALS["router"]->urlFor("new_recueil_process");

        $this->content .= <<<FORM
<form id='new_recueil' method='post' action='$path' enctype="multipart/form-data">
<label>Nom du recueil</label>
<input type='text' name='nom' placeholder='Nom' required>
<label>Texte</label>
<textarea name='texte' class='cite' placeholder='Texte' lang='fr' required></textarea>
<input class='bouton' type="reset" value="Annuler">
<input class="bouton" type="submit" value="Valider">
</form>
FORM;
    }

}