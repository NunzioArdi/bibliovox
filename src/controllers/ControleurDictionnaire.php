<?php


namespace bibliovox\controllers;


use bibliovox\models\DicoContient;
use bibliovox\models\Dictionnaire;
use bibliovox\models\Mot;
use bibliovox\views\VueDico;

class ControleurDictionnaire
{
    static function renderDictionnaire($dico) {
        echo "<h1>Accès au dictionnaire <i>$dico->nomD</i></h1>";
        //On veut l'image ?
        //echo "<img src='".PATH."/media/img/img/dico/$dico->imageD'>";
        $mots = DicoContient::motContenuDico($_GET['id']);
        foreach ($mots as $m) {
            $texte = Mot::getById($m->idM)->texte;
            echo "<h2><a href='" . $GLOBALS["router"]->urlFor('mot', ['idD' => $dico->idD, 'idM' => $m->idM]) . "'>$texte</a></h2>";
        }
    }

    /**
     * Récupère la liste complète de tout les dictionnaires
     */
    public function allDico()
    {
        $dico = Dictionnaire::all();
        $vue = new VueDico($dico);
        $vue->views('all');
    }

}