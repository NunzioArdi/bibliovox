<?php


namespace bibliovox\views;

/**
 * Afficheur des productions individuel des utilisateurs.
 * @package bibliovox\views
 */
class VueProduction extends Vue
{

    /**
     * @inheritDoc
     */
    public function views(string $view)
    {
        switch ($view) {
            case 'all':
                $this->all();
                break;
            case 'prod':
                $this->prod();
                break;
            case 'editProd':
                $this->editProd();
                break;
            case 'create':
                $this->create();
                break;
            default:
                break;
        }
        $this->afficher();
    }

    /**
     * Affiche toutes les production d'un utilisateur
     */
    private function all()
    {
        $this->title = 'Productions personnel';
        $this->content .= '<h1>Retrouve ici tes productions !</h1>';

        if ($this->res[0] != null) {
            foreach ($this->res[0] as $r) {
                $this->content .= "<a href =\"" . $GLOBALS["router"]->urlFor('productions') . "$r->idP\"><h2>$r->nomP</h2></a>";
            }
        } else {
            $this->content .= '<p>Aucune production</p>';
        }

        //    L'idU est temporairement passé dans le GET
        $this->content .= "<div class='createNew'>
            <a class='boutton' href=\"" . $GLOBALS["router"]->urlFor("new_production") . "?idU={$this->res[1]}\">Ajout</a>";

    }

    private function prod()
    {
        $prod = $this->res;
        $this->title = $prod->nomP;

        $this->content .= "<h1>Production: <i>$prod->nomP</i></h1>";
        $date = explode('-', $prod->dateP);
        $this->content .= "<div class=\"date\">Créé le: " . $date['2'] . "/" . $date['1'] . "/" . $date['0'] . "</div>";
        $this->content .= "<cite>$prod->commentaire</cite>";

        $this->content .= "<div class=\"comm\">Ton enregistrement: </div>";
        $this->content .= "<audio controls>";
        $this->content .= "<source src=\"" . $GLOBALS["PATH"] . "/media/aud/prod/" . $prod->audio . "\" type=\"audio/mp3\">";
        $this->content .= "</audio></div>";

        //L'idU est stocké en get temporairement (jusqu'à la gestion du compte)
        $this->content .= "<a class=\"boutton\" href=\" " . $GLOBALS["router"]->urlFor("edit_production", $data = ['idP' => $prod->idP]) . " \">Editer</a>";
    }

    private function editProd()
    {
        $prod = $this->res[0];
        $url = $this->res[1];
        $this->title = "Édition";

        $this->content .= <<<END
    <form id="edit_production" method="post" action="$url" enctype="multipart/form-data">
        <label>Titre de la production</label>
        <input type="text" name="nom" placeholder="Titre" value="$prod->nomP">
        <label>Audio</label>
        <input type="file" name="audio" accept="audio/*">
        <label>Commentaire</label>
        <textArea name='comm'>$prod->commentaire</textArea>
        <br>
        <input class='bouton' type="reset" value="Annuler modifications">
        <input class="bouton" type="submit" value="Valider modifications">
    </form>
END;
    }

    private function create()
    {
        $this->title = 'Nouvelle production';
        $this->content .= "<h1>Créer une nouvelle production</h1>";

        $path = $GLOBALS["router"]->urlFor("new_production_process") . "?idU=$this->res";

        $this->content .= <<<FORM
<form id='new_production' method='post' action='$path' enctype="multipart/form-data">
<label>Titre de la production</label>
<input type='text' name='nom' placeholder='Titre' required>
<label>Audio</label>
<input type='file' name='audio' accept="audio/*" required>
<input class='bouton' type="reset" value="Annuler">
<input class="bouton" type="submit" value="Valider">
</form>
FORM;
    }
}