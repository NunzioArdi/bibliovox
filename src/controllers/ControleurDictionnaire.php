<?php


namespace bibliovox\controllers;


use bibliovox\models\DicoContient;
use bibliovox\models\Dictionnaire;
use bibliovox\models\Mot;
use bibliovox\views\VueDico;
use Illuminate\Support\Facades\View;

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

    /**
     * Appelle la vue de création de dictionnaire en fonction des autorisations
     */
    public function createDico()
    {
        //TODO accès en focntion des types de compte
        $vue = new VueDico();
        $vue->views('createDico');
    }

    public function getDicoAlphabet()
    {

        //On veut l'image?
        //echo "<img src='".PATH."/media/img/img/dico/alpha.png'>";
        $mots = Mot::allAlpha();
        $vue = new VueDico($mots);
        $vue->views('alphabet');

    }

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
            //TODO erreur
            echoHead('');
            echo "<div class='erreur'>Ce dictionnaire n'existe pas.</div>";
            echo "<a href='" . $GLOBALS["router"]->urlFor('dictionnaires') . "'><h1><- Retour</h1></a>";
        }
    }

}