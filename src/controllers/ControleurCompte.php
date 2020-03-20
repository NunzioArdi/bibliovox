<?php

namespace bibliovox\controllers;

use bibliovox\models\Utilisateur;
use bibliovox\views\VueCompte;

class ControleurCompte extends Controleur
{

    public function compte()
    {
        $vue = new VueCompte();
        $vue->views('compte');
    }

    public function processLogin()
    {
        $res = Utilisateur::login($_POST['firstname'], $_POST['lastname'], $_POST['password']);
        if ($res[0] >= 0) {
            $_SESSION['connected'] = true;
            $_SESSION['idU'] = $res[0];
            $_SESSION['nom'] = $_POST['firstname'];
            $_SESSION['prenom'] = $_POST['lastname'];
            $_SESSION['grade'] = $res[1];
            $GLOBALS["CONNPROCESS"] = 0;
        }
        return $this->resp->withRedirect($this->req->getHeader('Referer')[0]);
        //TODO page d'erreur, actuellement si marche pas renvoie sur la page demandé
    }

    static function getIdUser()
    {
        return $_SESSION['idU'];
    }

    static function isEleve()
    {
        $g = $_SESSION['grade'];
        return ($g == 1 || $g == 2 || $g == 3 || $g == 0) ? true : false;
    }

    static function isParent()
    {
        $g = $_SESSION['grade'];
        return ($g == 2 || $g == 3 || $g == 0) ? true : false;
    }

    static function isTeatch()
    {
        $g = $_SESSION['grade'];
        return ($g == 2 || $g == 0) ? true : false;
    }

    static function isAdmin()
    {
        $g = $_SESSION['grade'];
        return ($g == 0) ? true : false;
    }
}