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
        if ($res[0] > 0) {
            $_SESSION['connected'] = true;
            $_SESSION['idU'] = $res[0];
            $_SESSION['nom'] = $_POST['firstname'];
            $_SESSION['prenom'] = $_POST['lastname'];
            $_SESSION['grade'] = $res[1];
        }
    }
}