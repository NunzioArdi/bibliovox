<?php

use bibliovox\controllers\ControleurDictionnaire;
use bibliovox\controllers\ControleurMot;
use bibliovox\controllers\ControleurProduction;
use bibliovox\controllers\ControleurRecueil;
use bibliovox\models\DicoContient;
use bibliovox\models\Dictionnaire;
use bibliovox\models\Mot;
use bibliovox\models\Production;
use bibliovox\models\Recueil;
use Illuminate\Database\Capsule\Manager as DB;
use Slim\App as Slim;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

require_once __DIR__ . '/vendor/autoload.php';

define('PATH', parse_ini_file('src/conf/conf_path.ini')['path']);

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
    echoHead('Accueil');
    echo "<p>Bibli O’vox a été imaginé par Christophe Buck, Sophie Deleys et Marie Lequèvre dans le cadre d’un master dans le domaine des sciences de l’éducation. Notre production s’appuiera sur les propositions résultant de ce travail. </p>";
    echo "<p>Bibli O’vox est un outil qui a pour but de favoriser le développement du langage oral dans un cadre scolaire. </p>";
    echo "<p>Ce système a une visée éducative, cela signifie que les principaux utilisateurs de ce système seront des élèves de maternelle et primaire dans un premier temps. Il faudra donc s’assurer de la simplicité d’utilisation. Le système doit combler un manque constaté par plusieurs enseignants, celui de la communication orale qu’il soit en classe ou à la maison. L’outil concernera tout élève rencontrant des difficultés pour s’exprimer en langue française dans un premier temps. Cependant il visera plus particulièrement les élèves allophones (le français n’étant pas leur langue maternelle) ou étrangers (ne communiquant pas forcément en français avec leur entourage). Un outil permettant d’exploiter le français à l’oral dans leur foyer pourrait alors se révéler profitable. En effet, les parents pourraient bénéficier de ce nouveau dispositif, puisque la vie orale en classe, ne peut être partagée avec un cahier du jour classique écrit. </p>";
    echo "<p>Bibli O’vox est donc un cahier de vie oral permettant un suivi des activités des enfants, et ce, jour après jour, que cela soit par les enfants pour constater leur progrès dans l’apprentissage de la langue française ou par les parents afin d’avoir un aperçu de la vie que mène leur enfant au sein de la classe durant la journée et qu’ils puissent eux aussi s’investir dans les devoirs de leur enfant. Cela renforcera le lien entre l’école et le foyer en partageant ce qui a été étudié en classe.</p>";
})->setName('home');


//Compte
$app->get('/compte', function () {
    echoHead('Compte');
    echo "account";
})->setName('compte');


//Dictionnaires
$app->get('/dictionnaires', function (Request $req, Response $res, $args = []) {
    echoHead('Dictionnaires');
    echo "<h1>Les Dictionnaires</h1>";
    $dico = Dictionnaire::all();
    ControleurDictionnaire::renderDictionnaires($dico);

    echo "<div class='createNew'><a href='" . $GLOBALS["router"]->pathFor("new_dictionnaire") . "'>+</a>";

})->setName('dictionnaires');

//Creation dictionnaire
$app->get('/dictionnaire/create', function (Request $req, Response $res, $args = []) {
    echoHead('Nouveau dictionnaire');

    if (array_key_exists('err', $_GET))
        switch ($_GET['err']) {
            case 1:
                echo "<div class='erreur'>L'extension du fichier n'est pas autorisée</div>";
                break;
            default:
                echo "<div class='erreur'>Erreur inconnue</div>";
                break;
        }

    echo "<h1>Créer un nouveau dictionnaire</h1>";
    $path = $GLOBALS["router"]->urlFor("new_dictionnaire_process");

    echo <<<FORM
<form id='new_dictionnaire' method='post' action='$path' enctype="multipart/form-data">
<label>Nom du dictionnaire</label>
<input type='text' name='nom' placeholder='Nom' required>
<label>Description</label>
<textarea name='description' placeholder='Description' lang='fr' required></textarea>
<label>Illustration du dictionnaire (facultatif)</label>
<input type='file' name='image' accept="image/*">
<input class='bouton' type="reset" value="Annuler">
<input class="bouton" type="submit" value="Valider">
</form>
FORM;
})->setName('new_dictionnaire');

$app->post('/dictionnaire/create/process', function (Request $req, Response $res, $args = []) {

    $res = Dictionnaire::createNew($_POST['nom'], $_POST['description']);

    if (is_int($res))
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('new_dictionnaire') . "?err=$res");
    else
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor("dictionnaire_acces") . "?id=$res->idD");

})->setName('new_dictionnaire_process');

//Accès à un dictionnaire
$app->get('/dictionnaire/acces', function (Request $req, Response $res, $args = []) {
    if (isset($_GET['id'])) {
        if ($_GET['id'] == -1) {
            echoHead('Tous les mots');
            echo "<h1>Tous les mots par ordre alphabétique</h1>";
            //On veut l'image?
            //echo "<img src='".PATH."/media/img/img/dico/alpha.png'>";
            $mots = Mot::allAlpha();

            foreach ($mots as $m) {
                echo "<h2><a href='" . $GLOBALS["router"]->urlFor("mot", ["idD" => -1, "idM" => $m->idM]) . "'>$m->texte</a></h2>";
            }
        } else {
            $dico = Dictionnaire::getId($_GET['id']);

            if ($dico != null) {
                echoHead("$dico->nomD");
                ControleurDictionnaire::renderDictionnaire($dico);
            } else {
                echoHead('');
                echo "<div class='erreur'>Ce dictionnaire n'existe pas.</div>";
                echo "<a href='" . $GLOBALS["router"]->urlFor('dictionnaires') . "'><h1><- Retour</h1></a>";
            }
        }
    } else
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('dictionnaires'));
})->setName('dictionnaire_acces');


//Mot du dictionnaire
$app->get('/dictionnaire/acces/{idD}/{idM}/', function (Request $req, Response $res, $args = []) {
    $mot = Mot::getById($args["idM"]);
    if ($mot != null && DicoContient::matchIDs($args["idM"], $args["idD"])) {
        echoHead($mot->texte);
        ControleurMot::renderMot($mot);
    } else {
        echoHead('ERREUR');
        echo "<div class='erreur'>Ce mot n'existe pas dans ce dictionnaire ";
        echo "<a href='" . $GLOBALS["router"]->urlFor('dictionnaires') . "'>Retour aux dictionnaires.</a>";
    }
})->setName('mot');


//Recueil
$app->get('/recueil', function (Request $req, Response $res, $args = []) {
    if (isset($_GET['id'])) {
        if (Recueil::exist($_GET['id'])) {
            $rec = Recueil::getById($_GET['id']);
            echoHead($rec->nomR);
            ControleurRecueil::renderRecueil($rec, 1);
        } else {
            echoHead('Recueils - Erreur');
            echo "<div class='erreur'>Recueil inconnu.</div>";
        }
    } else {
        echoHead('Recueils');
        echo "<h1>Tous les recueils</h1>";

        $rec = Recueil::all();

        ControleurRecueil::renderRecueils($rec);

        echo "<div class='createNew'><a href='" . $GLOBALS["router"]->urlFor("new_recueil") . "'>+</a>";
    }
})->setName('recueils');

$app->get('/recueil/create', function (Request $req, Response $res, $args = []) {
    echoHead('Nouveau recueil');

    if (array_key_exists('err', $_GET))
        switch ($_GET['err']) {
            default:
                echo "<div class='erreur'>Erreur inconnue</div>";
                break;
        }

    echo "<h1>Créer un nouveau recueil</h1>";
    $path = $GLOBALS["router"]->urlFor("new_recueil_process");

    echo <<<FORM
<form id='new_recueil' method='post' action='$path' enctype="multipart/form-data">
<label>Nom du recueil</label>
<input type='text' name='nom' placeholder='Nom' required>
<label>Texte</label>
<textarea name='texte' class='cite' placeholder='Texte' lang='fr' required></textarea>
<input class='bouton' type="reset" value="Annuler">
<input class="bouton" type="submit" value="Valider">
</form>
FORM;
})->setName('new_recueil');

$app->post('/recueil/create/process', function (Request $req, Response $res, $args = []) {
    $res = Recueil::createNew($_POST['nom'], $_POST['texte']);

    if (is_int($res))
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('new_recueil') . "?err=$res");
    else
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor("recueils") . "?id=$res->idR");

})->setName('new_recueil_process');


//Productions
$app->get('/production', function (Request $req, Response $res, $args = []) {

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

$app->get('/production/create', function (Request $req, Response $res, $args = []) {

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

$app->post('/production/create/process', function (Request $req, Response $res, $args = []) {
    echoHead("Création");

    /* Récupération temporaire de l'idU */
    $idU = $_GET['idU'];

    $res = Production::createNew($_POST['nom'], $idU);

    if (is_int($res))
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('new_production') . "?err=$res&idU=$idU");
    else
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor("productions") . "?id=$res->idP");

})->setName('new_production_process');

$app->get('/production/edit', function (Request $req, Response $res, $args = []) {
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
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('productions'));

})->setName('edit_production');

$app->post('/production/edit/process', function (Request $req, Response $res, $args = []) {
    echoHead("Édition");

    /* Récupération temporaire de l'idU */
    $idU = $_GET['idU'];
    if (isset($_GET['idP'])) {
        $idP = $_GET['idP'];

        $res = Production::updateProd($idP, $_POST['nom'], $idU, $_POST['comm']);

        if (is_int($res))
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('edit_productions') . "?err=$res");
        else
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('productions') . "?id=$idP");
    } else {
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('productions'));
    }


})->setName('edit_production_process');


$app->get('/about', function (Request $req, Response $res, $args = []) {
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
    echo "<link rel='stylesheet' href='" . PATH . "/web/css/bibliovox.css'>\n";
    echo "<link rel='icon' href='" . PATH . "/media/img/icn/logo.png'>\n";
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

echo "</body>";
echo "</html>";