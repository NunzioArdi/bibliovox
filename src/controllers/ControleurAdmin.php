<?php


namespace bibliovox\controllers;


use bibliovox\models\Utilisateur;
use bibliovox\views\VueAdmin;

class ControleurAdmin extends Controleur
{

    public function interface()
    {
        $vue = new VueAdmin();
        $vue->views('createUser');
    }

    public function processCreateUser(){
        $pwdEncode = password_hash($_POST['password'], PASSWORD_BCRYPT);
        //PHP Deprecated:  password_hash(): Use of the 'salt' option to password_hash is deprecated in /srv/http/bibli/src/controllers/ControleurAdmin.php on line 34
        // c'est pour ça que je ne met pas de sel, il est généré aléatoirement
        try {
            Utilisateur::createUser($_POST['firstname'], $_POST['lastname'], $pwdEncode, $_POST['grade'], $_POST['email']);
        } catch (\Exception $e) {
            echo "catch ControleurAdmin\n<br>";
            echo $e;
        }


        return $this->resp->withRedirect($GLOBALS["router"]->urlFor("admin"));

    }
}