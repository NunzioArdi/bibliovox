<?php

use bibliovox\controllers\ControleurDictionnaire;
use bibliovox\controllers\ControleurMot;
use bibliovox\controllers\ControleurProduction;
use bibliovox\controllers\ControleurRecueil;
use bibliovox\controllers\ControleurHome;
use bibliovox\models\Dictionnaire;
use bibliovox\models\Production;
use bibliovox\models\Recueil;
use Illuminate\Database\Capsule\Manager as DB;
use Slim\App as Slim;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

require_once __DIR__ . '/vendor/autoload.php';

//define('PATH', parse_ini_file('src/conf/conf_path.ini')['path']);
global $PATH;
$PATH = parse_ini_file('src/conf/conf_path.ini')['path'];
echo $PATH;
$db = new DB();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$c = new Container($configuration);
$app = new Slim($c);
global $router;
$router = $app->getContainer()->get('router');


//Accueil
$app->get('/', function () {
    $cont = new ControleurHome();
    $cont->index();
})->setName('home');


//TODO Compte
$app->get('/compte', function () {
    echoHead('Compte');
    echo "account";
})->setName('compte');


//Dictionnaires
$app->get('/dictionnaires', function () {
    $cont = new ControleurDictionnaire();
    $cont->allDico();
})->setName('dictionnaires');

//Creation dictionnaire
$app->get('/dictionnaire/create', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurDictionnaire();
    $cont->createDico();
    /* //TODO a mettre (dna sune class erreur?)
        if (array_key_exists('err', $_GET))
            switch ($_GET['err']) {
                case 1:
                    echo "<div class='erreur'>L'extension du fichier n'est pas autorisée</div>";
                    break;
                default:
                    echo "<div class='erreur'>Erreur inconnue</div>";
                    break;
            }
    */
})->setName('new_dictionnaire');

//TODO MVC
$app->post('/dictionnaire/create/process', function (Request $req, Response $resp, $args = []) {

    $res = Dictionnaire::createNew($_POST['nom'], $_POST['description']);

    if (is_int($res))
        return $resp->withRedirect($GLOBALS["router"]->urlFor('new_dictionnaire') . "?err=$res");
    else
        return $resp->withRedirect($GLOBALS["router"]->urlFor("dictionnaire_acces") . "?id=$res->idD");

})->setName('new_dictionnaire_process');

//Accès à un dictionnaire
$app->get('/dictionnaire/acces/{idD}[/]', function (Request $req, Response $resp, $args) {
    ;
    if ($args["idD"] == 0) {                          // dico alphabet
        $cont = new ControleurDictionnaire();
        $cont->getDicoAlphabet();
    } elseif ($args["idD"] > 0) {                      // dico thème
        $cont = new ControleurDictionnaire();
        $cont->getDicoTheme($args["idD"]);
    }
})->setName('dictionnaire_acces');


//Mot du dictionnaire
$app->get('/dictionnaire/acces/{idD}/{idM}/', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurMot();
    $cont->getMotDico($args["idM"], $args["idD"]);
})->setName('mot');


//Recueil //TODO MVC
$app->get('/recueil[/[{id}]]', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurRecueil();
    if (isset($args["id"]) && is_numeric($args["id"])) {
        $cont->recueil($args["id"]);
    } else {
        $cont->allRecueil();
    }
})->setName('recueils');

$app->get('/recueil/create/', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurRecueil();
    $cont->creerRecueil();
 /*   echoHead('Nouveau recueil');

    if (array_key_exists('err', $_GET))
        switch ($_GET['err']) {
            default:
                echo "<div class='erreur'>Erreur inconnue</div>";
                break;
        }
*/
})->setName('new_recueil');

$app->post('/recueil/create/process', function (Request $req, Response $resp, $args = []) {
    $res = Recueil::createNew($_POST['nom'], $_POST['texte']);

    if (is_int($res))
        return $resp->withRedirect($GLOBALS["router"]->urlFor('new_recueil') . "?err=$res");
    else
        return $resp->withRedirect($GLOBALS["router"]->urlFor("recueils") . "?id=$res->idR");

})->setName('new_recueil_process');


//Productions
$app->get('/production', function (Request $req, Response $resp, $args = []) {

    /* idU utilisé en attente de la fonction des comptes */
    $idU = 1;

    if (isset($_GET['id'])) {
        $idP = $_GET['id'];
        if (Production::exist($idP, 1)) {
            $prod = Production::getById($idP);
            echoHead($prod->nomP);
            ControleurProduction::renderProduction($prod);
            //L'idU est stocké en get temporairement (jusqu'à la gestion du compte)
            echo "<a class='boutton' href='" . $GLOBALS["router"]->urlFor("edit_production") . "?idP=$idP&idU=$idU'>Editer</a>";
            exit();
        } else
            echo "<div class='erreur'>Recueil inconnu.</div>";
    }
    echoHead('Productions');
    echo "<h1>Retrouve ici tes productions !</h1>";
    $prods = Production::allCheck($idU);
    ControleurProduction::renderProductions($prods);

    /* L'idU est temporairement passé dans le GET */
    echo "<div class='createNew'><a class='boutton' href='" . $GLOBALS["router"]->urlFor("new_production") . "?idU=$idU'>+</a>";

})->setName('productions');

$app->get('/production/create', function (Request $req, Response $resp, $args = []) {

    /* Récupération temporaire de l'idU */
    $idU = $_GET['idU'];

    echoHead('Nouvelle production');

    if (array_key_exists('err', $_GET))
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

    echo "<h1>Créer une nouvelle production</h1>";
    $path = $GLOBALS["router"]->urlFor("new_production_process") . "?idU=$idU";

    echo <<<FORM
<form id='new_production' method='post' action='$path' enctype="multipart/form-data">
<label>Titre de la production</label>
<input type='text' name='nom' placeholder='Titre' required>
<label>Audio</label>
<input type='file' name='audio' accept="audio/*" required>
<input class='bouton' type="reset" value="Annuler">
<input class="bouton" type="submit" value="Valider">
</form>
FORM;

})->setName('new_production');

$app->post('/production/create/process', function (Request $req, Response $resp, $args = []) {
    echoHead("Création");

    /* Récupération temporaire de l'idU */
    $idU = $_GET['idU'];

    $res = Production::createNew($_POST['nom'], $idU);

    if (is_int($res))
        return $resp->withRedirect($GLOBALS["router"]->urlFor('new_production') . "?err=$res&idU=$idU");
    else
        return $resp->withRedirect($GLOBALS["router"]->urlFor("productions") . "?id=$res->idP");

})->setName('new_production_process');

$app->get('/production/edit', function (Request $req, Response $resp, $args = []) {
    echoHead("Édition");
    $idU = $_GET['idU'];

    if (isset($_GET['err'])) {
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
    }

    if (isset($_GET['idP'])) {
        $idP = $_GET['idP'];
        if (Production::exist($idP, $idU)) {
            $url = $GLOBALS["router"]->urlFor("edit_production_process") . "?idP=$idP&idU=$idU";
            $prod = Production::getById($idP);
            echo <<<FORMTOP
<form id='edit_production' method='post' action='$url' enctype="multipart/form-data">
<label>Titre de la production</label>
<input type='text' name='nom' placeholder='Titre' value='$prod->nomP'>
<label>Audio</label>
<input type='file' name='audio' accept="audio/*">
FORMTOP;
            //Test : Est-ce un prof? TODO
            if (true) {
                echo "<label>Commentaire</label><textArea name='comm'>$prod->commentaire</textArea>";
            }
            echo <<<FORMBOT
<br>
<input class='bouton' type="reset" value="Annuler modifications">
<input class="bouton" type="submit" value="Valider modifications">
</form>
FORMBOT;

        }

    } else
        return $resp->withRedirect($GLOBALS["router"]->urlFor('productions'));

})->setName('edit_production');

$app->post('/production/edit/process', function (Request $req, Response $resp, $args = []) {
    echoHead("Édition");

    /* Récupération temporaire de l'idU */
    $idU = $_GET['idU'];
    if (isset($_GET['idP'])) {
        $idP = $_GET['idP'];

        $res = Production::updateProd($idP, $_POST['nom'], $idU, $_POST['comm']);

        if (is_int($res))
            return $resp->withRedirect($GLOBALS["router"]->urlFor('edit_productions') . "?err=$res");
        else
            return $resp->withRedirect($GLOBALS["router"]->urlFor('productions') . "?id=$idP");
    } else {
        return $resp->withRedirect($GLOBALS["router"]->urlFor('productions'));
    }


})->setName('edit_production_process');


$app->get('/about', function (Request $req, Response $resp, $args = []) {
    echoHead('À propos');
    echo "<div>Icons made by <a href='https://www.flaticon.com/authors/eucalyp' title='Eucalyp'>Eucalyp</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href='https://www.flaticon.com/authors/ddara' title='dDara'>dDara</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href='https://www.flaticon.com/authors/freepik' title='Freepik'>Freepik</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href='https://www.flaticon.com/authors/freepik' title='Freepik'>Freepik</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href='https://www.flaticon.com/authors/itim2101' title='itim2101'>itim2101</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href='https://www.flaticon.com/authors/freepik' title='Freepik'>Freepik</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href='https://www.flaticon.com/authors/eucalyp' title='Eucalyp'>Eucalyp</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href='https://www.flaticon.com/authors/freepik' title='Freepik'>Freepik</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href='https://www.flaticon.com/authors/freepik' title='Freepik'>Freepik</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>";
})->setName('about');


function echoHead(string $titre)
{
    echo <<<HEAD
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8"/>
<title>Bibli O'Vox - $titre</title>\n
HEAD;
    echo "<link rel='stylesheet' href='" . $GLOBALS["PATH"] . "/web/css/bibliovox.css'>\n";
    echo "<link rel='icon' href='" . $GLOBALS["PATH"] . "/media/img/icn/logo.png'>\n";
    echo "</head>\n";
    echo "<body>\n";

    echo "<nav><ul>";
    echo "<li><a href='" . $GLOBALS["router"]->urlFor('home') . "'><img class ='icn' src='" . PATH . "/media/img/icn/logo.png' alt='Logo'></a></li>";
    echo "<li><a href='" . $GLOBALS["router"]->urlFor('home') . "'><img src='" . PATH . "/media/img/icn/home.png' alt='Accueil'>Accueil</a></li>";
    echo "<li><a href='" . $GLOBALS["router"]->urlFor('dictionnaires') . "'><img src='" . PATH . "/media/img/icn/dico.png' alt='Dictionnaires'>Dictionnaires</a></li>";
    echo "<li><a href='" . $GLOBALS["router"]->urlFor('recueils') . "'><img src='" . PATH . "/media/img/icn/recueil.png' alt='Receuils'>Recueils</a></li>";
    echo "<li><a href='" . $GLOBALS["router"]->urlFor('productions') . "'><img src='" . PATH . "/media/img/icn/production.png' alt='Productions'>Productions</a></li>";
    echo "<li><a href='" . $GLOBALS["router"]->urlFor('compte') . "'><img src='" . PATH . "/media/img/icn/compte.png' alt='Compte'>Compte</a></li>";
    echo "</ul></nav>";

}

$app->run();

//echo "</body>";
//echo "</html>";