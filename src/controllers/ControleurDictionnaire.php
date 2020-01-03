<?php


namespace bibliovox\controllers;


use bibliovox\models\DicoContient;
use Slim\Slim;

class ControleurDictionnaire
{
    static function renderDictionnaire($dico) {
        echo "<h1>Accès au dictionnaire <i>$dico->nomD</i></h1>";
        echo "<img src='".PATH."/media/img/img/dico/$dico->imageD'>";
        $mots = DicoContient::motContenuDico($_GET['id']);
        foreach ($mots as $m) {
            echo "<h2><a href='" . PATH . "/dictionnaire/acces/$dico->idD/$m->texte'>$m->texte</a></h2>";
        }
    }

    public static function renderDictionnaires($dictionnaires)
    {
        echo "<h2><a href='" . Slim::getInstance()->urlFor('dictionnaire_acces') . "?id=-1'><img src='".PATH."/media/img/img/dico/alpha.png'>Dictionnaire alphabétique</a></h2>";

        foreach ($dictionnaires as $d) {
            if ($d->imageD != null)
            echo "<h2><a href='" . Slim::getInstance()->urlFor('dictionnaire_acces') . "?id=$d->idD'><img src='".PATH."/media/img/img/dico/$d->imageD'>$d->nomD</a></h2>";
            else
                echo "<h2><a href='" . Slim::getInstance()->urlFor('dictionnaire_acces') . "?id=$d->idD'><img src='".PATH."/media/img/img/dico/dico.png'>$d->nomD</a></h2>";
        }
    }

}