<?php


namespace bibliovox\views;


class VueDico extends View
{

    public function views(string $view)
    {
        switch ($view){
            case "all":
                $this->allDico();
                break;
            default:
                break;
        }
        $this->afficher();
    }

    private function allDico()
    {
        $this->title="Dictionnaires";

        $this->content.="<div class=\"dico\"><a href=\"{$GLOBALS["router"]->urlFor('dictionnaire_acces')}?id=-1\"><img src=\"{$GLOBALS["PATH"]}/media/img/img/dico/alpha.png\"><h2>Dictionnaire alphabétique</h2></a></div>\n";

        foreach ($this->res as $d) {
            $image = "/media/img/img/dico/";
            if ($d->imageD != null)
                $image.= $d->imageD;
            else
                $image.= "dico.png";
            $this->content.= "<div class=\"dico\"><a href=\"{$GLOBALS["router"]->urlFor('dictionnaire_acces')}?id=$d->idD\"><img src=\"{$GLOBALS["PATH"]}$image\"><h2>$d->nomD</h2></a></div>\n";
        }

        $this->content.="<div class=\"createNew\"><a href=\"{$GLOBALS["router"]->pathFor("new_dictionnaire")}\">Nouveau Dico</a></div>";
    }
}