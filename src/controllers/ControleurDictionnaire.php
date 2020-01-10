<?php


namespace bibliovox\controllers;


use bibliovox\models\DicoContient;
use bibliovox\models\Mot;
use Slim\Slim;

class ControleurDictionnaire
{
    static function renderDictionnaire($dico) {
        echo "<h1>Accès au dictionnaire <i>$dico->nomD</i></h1>";
        //On veut l'image ?
        //echo "<img src='".PATH."/media/img/img/dico/$dico->imageD'>";
        $mots = DicoContient::motContenuDico($_GET['id']);
        foreach ($mots as $m) {
            $texte = Mot::getById($m->idM)->texte;
            echo "<h2><a href='" . Slim::getInstance()->urlFor('mot', ['idD'=>$dico->idD, 'idM'=>$m->idM]) . "/'>$texte</a></h2>";
        }
    }

    public static function renderDictionnaires($dictionnaires)
    {
        echo "<div class='dico'><a href='" . Slim::getInstance()->urlFor('dictionnaire_acces') . "?id=-1'><img src='".PATH."/media/img/img/dico/alpha.png'><h2>Dictionnaire alphabétique</h2></a></div>";

        foreach ($dictionnaires as $d) {
            if ($d->imageD != null)
            echo "<div class='dico'><a href='" . Slim::getInstance()->urlFor('dictionnaire_acces') . "?id=$d->idD'><img src='".PATH."/media/img/img/dico/$d->imageD'><h2>$d->nomD</h2></a></div>";
            else
                echo "<div class='dico'><a href='" . Slim::getInstance()->urlFor('dictionnaire_acces') . "?id=$d->idD'><img src='".PATH."/media/img/img/dico/dico.png'><h2>$d->nomD</h2></a></div>";
        }
    }

}