<?php


namespace bibliovox\views;


class VueMot extends Vue
{

    public function views(string $view)
    {
        switch ($view) {
            case "motDico":
                $this->motDico();
                break;
            default:
                break;
        }
        $this->afficher();
    }

    private function motDico()
    {
        $mot = $this->res;
        $texte = ucfirst($mot->texte);

        $this->title = $texte;


        $this->content .= "<div class = \"mot\">";
        $this->content .= "<h1>$texte</h1>";
        if ($mot->image != null)
            $this->content .= "<img src=\" " . $GLOBALS["PATH"] . "/media/img/img/mot/" . $mot->image . "\"  alt=\"img\">";

        if ($mot->audio != null) {
            $this->content .= "<audio controls>";
            $this->content .= "<source src=\" " . $GLOBALS["PATH"] . "/media/aud/dico/" . $mot->audio . "\" type=\"audio/mp3\">";
            $this->content .= "</audio></div>";
        } else {
            $this->content .= "<h2>Enregistre toi !</h2>";
            //TODO
            //Appel à l'enregistreur
        }
    }
}