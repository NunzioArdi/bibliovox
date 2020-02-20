<?php


namespace bibliovox\views;


class VueDico extends Vue
{

    /**
     * @inheritDoc
     */
    public function views(string $view)
    {
        switch ($view) {
            case "all":
                $this->allDico();
                break;
            case "createDico":
                $this->creDico();
                break;
            case "alphabet":
                $this->affAlph();
                break;
            case "theme":
                $this->theme();
                break;
            default:
                break;
        }
        $this->afficher();
    }

    private function allDico()
    {
        $this->title = "Dictionnaires";

        $this->content .= "    <div class=\"dico\">
        <a href=\"" . $GLOBALS["router"]->pathFor('dictionnaire_acces', $data = ['idD' => 0]) . "\">
            <img src=\"" . $GLOBALS["PATH"] . "/media/img/img/dico/alpha.png\" alt=\"alphabet\">
            <h2>Dictionnaire alphabétique</h2>
         </a>
    </div>\n";

        foreach ($this->res as $d) {
            $image = "/media/img/img/dico/";
            if ($d->imageD != null)
                $image .= $d->imageD;
            else
                $image .= "dico.png";
            $this->content .= "    <div class=\"dico\">
        <a href=\"" . $GLOBALS["router"]->urlFor('dictionnaire_acces', $data = ['idD' => $d->idD]) . "\">
            <img src=\"" . $GLOBALS["PATH"] . "$image\">
            <h2>$d->nomD</h2>
        </a>
    </div>\n";
        }

        $this->content .= "    <div class=\"createNew\">
        <a href=\"" . $GLOBALS["router"]->pathFor("new_dictionnaire") . "\">Nouveau Dico</a>
     </div>";
    }

    /**
     * Vue de création d'un dictionnaire
     */
    private function creDico()
    {
        $this->title = "Nouveau dictionnaire";

        $this->content .= "<h1>Créer un nouveau dictionnaire</h1>";
        $path = $GLOBALS["router"]->urlFor("new_dictionnaire_process");
        $this->content .= <<<FORM
<form id='new_dictionnaire' method='post' action='$path' enctype="multipart/form-data">
<label>Nom du dictionnaire</label>
<input type='text' name='nom' placeholder='Nom' required>
<label>Description</label>
<textarea name='description' placeholder='Description' lang='fr' required></textarea>
<label>Illustration du dictionnaire (facultatif)</label>
<input type='file' name='image' accept="image/*">
<input class='bouton' type="reset" value="Annuler">
<input class="bouton" type="submit" value="Valider">
</form>
FORM;
    }

    private function affAlph()
    {
        $this->title = "Tous les mots";

        $this->content .= "<h1>Tous les mots par ordre alphabétique</h1>";

        //On veut l'image?
        //echo "<img src='".PATH."/media/img/img/dico/alpha.png'>";

        foreach ($this->res as $m) {
            $this->content .= "<h2><a href='" . $GLOBALS["router"]->urlFor("mot", ["idD" => 0, "idM" => $m->idM]) . "'>$m->texte</a></h2>\n";
        }
    }

    private function theme()
    {
        $this->title = $this->res[1];

        foreach ($this->res[2] as $m) {
            $this->content .= "<h2><a href='" . $GLOBALS["router"]->urlFor('mot', ['idD' => $this->res[0], 'idM' => $m->idM]) . "'>$m->texte</a></h2>\n";
        }
    }
}