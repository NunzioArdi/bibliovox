<?php

namespace bibliovox\controllers;

use bibliovox\models\Classe;
use bibliovox\models\Eleve;
use bibliovox\models\Utilisateur;
use bibliovox\views\VueCompte;

class ControleurCompte extends Controleur
{

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

    public function compte()
    {
        $vue = new VueCompte();
        $vue->compte();
    }

    public function processLogin()
    {
        //Affiche la page de co type email avec l'id du bouton qui change en fonction du choix
        if (isset($_POST['typeAccount'])) {
            $vue = new VueCompte($_POST['radios']);
            $vue->connection(2);
            exit();
        }

        //quand un prof/parent appuis le sur bouton de co
        if (isset($_POST['xeleve'])) {
            $res = Utilisateur::login($_POST['email'], $_POST['password']);
            if ($res[0] >= 0) {
                if ($res[1] != 1) {
                    $_SESSION['connected'] = true;
                    $_SESSION['idU'] = $res[0];
                    $_SESSION['grade'] = $res[1];
                    $GLOBALS["CONNPROCESS"] = 0;
                    return $this->resp->withRedirect($GLOBALS["router"]->pathFor("home"));
                } else {
                    //TODO erreur, elevèe non autorisé
                }
            } else {
                //TODO erreur identifiant incorrect
            }
        }

        // quand un prof appuis sur le bouton de co des eleves. Vérifie et envoie sur une page de selection de l'élèvé
        if (isset($_POST['eleve'])) {
            $res = Utilisateur::login($_POST['email'], $_POST['password']);
            if ($res[0] >= 0) {
                if ($res[1] == 2 || $res[1] == 0) {
                    $classe = Classe::where('idUEnseignant', '=', $res[0])->get();
                    $eleveDansClasse = Eleve::where("idC", "=", $classe[0]->idC)->get();
                    $eleves = [];
                    foreach (json_decode($eleveDansClasse) as $eleve) {
                        $e = Utilisateur::select('nom', 'prenom', 'avatar')
                            ->where('idU', '=', $eleve->idU)->get();

                        array_push($eleves, ['idU' => $eleve->idU, 'nom' => $e[0]->nom,
                            'prenom' => $e[0]->prenom, 'avatar' => $e[0]->avatar]);
                    }

                    $vue = new VueCompte($eleves);
                    $vue->connection(3);
                    exit();
                } else {
                    //TODO erreur, seul les profs sont autorisé
                }
            } else {
                //TODO erreur identifiant incorrect
            }
        }

        if(isset($_POST['student'])){
            $_SESSION['connected'] = true;
            $_SESSION['idU'] = $_POST['radios'];
            $_SESSION['grade'] = 1;
            $GLOBALS["CONNPROCESS"] = 0;
            return $this->resp->withRedirect($GLOBALS["router"]->pathFor("home"));
        }

        /*return $this->resp->withRedirect($this->req->getHeader('Referer')[0]);*/
        //TODO page d'erreur, actuellement si marche pas renvoie sur la page demandé
    }

    public function disconnect()
    {
        session_destroy();
        return $this->resp->withRedirect($GLOBALS["router"]->pathFor("home"));
    }


}