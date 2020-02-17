<?php


namespace bibliovox\controllers;

use bibliovox\models\Production;
use bibliovox\views\VueProduction;

class ControleurProduction
{
    static function renderProductions($prods)
    {
        if ($prods != null)
            foreach ($prods as $r) {
                echo "<a href ='" . $GLOBALS["router"]->urlFor('productions') . "?id=$r->idP'><h2>$r->nomP</h2></a>";
            }
    }

    static function renderProduction($prod)
    {
        if ($prod == null) {
            echo "<div class='erreur'>Production inconnue.</div>";
        } else {
            echo "<h1>Production: <i>$prod->nomP</i></h1>";
            $date = explode('-', $prod->dateP);
            echo "<div class='date'>Créé le: " . $date['2'] . "/" . $date['1'] . "/" . $date['0'] . "</div>";
            echo "<cite>$prod->commentaire</cite>";

            echo "<div class='comm'>Ton enregistrement: </div>";
            echo "<audio controls>";
            echo "<source src='" . PATH . "/media/aud/prod/" . $prod->audio . "' type='audio/mp3'>";
            echo "</audio></div>";
        }
    }

    public function allProduction()
    {
        /* idU utilisé en attente de la fonction des comptes */
        $idU = 1;

        $prods = json_decode(Production::allCheck($idU));
        $vue = new VueProduction([$prods, $idU]);
        $vue->views('all');


    }

    public function production(int $idP)
    {
        /* idU utilisé en attente de la fonction des comptes */
        $idU = 1;

        if (Production::exist($idP, $idU)) {
            $prod = json_decode(Production::getById($idP));
            $vue = new VueProduction($prod);
            $vue->views('prod');
//            echoHead($prod->nomP);
//            ControleurProduction::renderProduction($prod);
            //L'idU est stocké en get temporairement (jusqu'à la gestion du compte)
//            echo "<a class='boutton' href='" . $GLOBALS["router"]->urlFor("edit_production") . "?idP=$idP&idU=$idU'>Editer</a>";
            exit();
        } else
            //TODO Erreur
            echo "<div class='erreur'>Recueil inconnu.</div>";
    }

    public function editProduction(int $idP)
    {
        /* idU utilisé en attente de la fonction des comptes */
        $idU = 1;

        if (Production::exist($idP, $idU)) {
            $url = $GLOBALS["router"]->urlFor("edit_production_process") . "?idP=$idP&idU=$idU";
            $prod = json_decode(Production::getById($idP));
            $vue = new VueProduction([$prod, $url]);
            $vue->views('editProd');
        }

        //TODO erreur
        /*  if (isset($_GET['err'])) {
              switch ($_GET['err']) {
                  case 1:
                      echo "<div class='erreur'>L'extension du fichier n'est pas autorisée</div>";
                      break;
                  case 2:
                      echo "<div class='erreur'>Utilisateur non autorisé</div>";
                      break;
                  default:
                      echo "<div class='erreur'>Erreur inconnue</div>";
                      break;
              }
          }*/
    }

    public function createProduction()
    {
        /* idU utilisé en attente de la fonction des comptes */
        $idU = 1;

        $vue = new VueProduction($idU);
        $vue->views('create');

        //TODO Erreur
        /* if (array_key_exists('err', $_GET))
        switch ($_GET['err']) {
            case 1:
                echo "<div class='erreur'>L'extension du fichier n'est pas autorisée</div>";
                break;
            case 2:
                echo "<div class='erreur'>Aucun fichier uploadé</div>";
                break;
            default:
                echo "<div class='erreur'>Erreur inconnue</div>";
                break;
        }
        */

    }

}