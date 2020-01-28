<?php

use bibliovox\controllers\ControleurDictionnaire;
use bibliovox\controllers\ControleurMot;
use bibliovox\controllers\ControleurProduction;
use bibliovox\controllers\ControleurRecueil;
use bibliovox\models\Dictionnaire;
use bibliovox\models\Mot;
use bibliovox\models\Production;
use bibliovox\models\Recueil;
use Illuminate\Database\Capsule\Manager as DB;
use Slim\Slim;

require_once __DIR__ . '/vendor/autoload.php';

define('PATH', parse_ini_file('src/conf/conf_path.ini')['path']);

$db = new DB();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$app = new Slim();


//Accueil
$app->get('/', function () {
    echoHead('Accueil');
    echo "<p>Bibli O’vox a été imaginé par Christophe Buck, Sophie Deleys et Marie Lequèvre dans le cadre d’un master dans le domaine des sciences de l’éducation. Notre production s’appuiera sur les propositions résultant de ce travail. </p>";
    echo "<p>Bibli O’vox est un outil qui a pour but de favoriser le développement du langage oral dans un cadre scolaire. </p>";
    echo "<p>Ce système a une visée éducative, cela signifie que les principaux utilisateurs de ce système seront des élèves de maternelle et primaire dans un premier temps. Il faudra donc s’assurer de la simplicité d’utilisation. Le système doit combler un manque constaté par plusieurs enseignants, celui de la communication orale qu’il soit en classe ou à la maison. L’outil concernera tout élève rencontrant des difficultés pour s’exprimer en langue française dans un premier temps. Cependant il visera plus particulièrement les élèves allophones (le français n’étant pas leur langue maternelle) ou étrangers (ne communiquant pas forcément en français avec leur entourage). Un outil permettant d’exploiter le français à l’oral dans leur foyer pourrait alors se révéler profitable. En effet, les parents pourraient bénéficier de ce nouveau dispositif, puisque la vie orale en classe, ne peut être partagée avec un cahier du jour classique écrit. </p>";
    echo "<p>Bibli O’vox est donc un cahier de vie oral permettant un suivi des activités des enfants, et ce, jour après jour, que cela soit par les enfants pour constater leur progrès dans l’apprentissage de la langue française ou par les parents afin d’avoir un aperçu de la vie que mène leur enfant au sein de la classe durant la journée et qu’ils puissent eux aussi s’investir dans les devoirs de leur enfant. Cela renforcera le lien entre l’école et le foyer en partageant ce qui a été étudié en classe.</p>";
})->name('home');


//Compte
$app->get('/compte', function () {
    echoHead('Compte');
    echo "account";
})->name('compte');


//Dictionnaires
$app->get('/dictionnaires', function () {
    echoHead('Dictionnaires');
    echo "<h1>Les Dictionnaires</h1>";
    $dico = Dictionnaire::all();
    ControleurDictionnaire::renderDictionnaires($dico);

    echo "<div class='createNew'><a href='" . Slim::getInstance()->urlFor("new_dictionnaire") . "'>+</a>";

})->name('dictionnaires');

//Creation dictionnaire
$app->get('/dictionnaire/create', function () {
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
    $path = Slim::getInstance()->urlFor("new_dictionnaire_process");

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
})->name('new_dictionnaire');

$app->post('/dictionnaire/create/process', function () {

    $res = Dictionnaire::createNew($_POST['nom'], $_POST['description']);

    if (is_int($res))
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('new_dictionnaire') . "?err=$res");
    else
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor("dictionnaire_acces") . "?id=$res->idD");

})->name('new_dictionnaire_process');

//Accès à un dictionnaire
$app->get('/dictionnaire/acces', function () {
    if (isset($_GET['id'])) {
        if ($_GET['id'] == -1) {
            echoHead('Tous les mots');
            echo "<h1>Tous les mots par ordre alphabétique</h1>";
            //On veut l'image?
            //echo "<img src='".PATH."/media/img/img/dico/alpha.png'>";
            $mots = Mot::allAlpha();

            foreach ($mots as $m) {
                echo "<h2><a href='" . Slim::getInstance()->urlFor("mot", ["idD" => -1, "idM" => $m->idM]) . "'>$m->texte</a></h2>";
            }
        } else {
            $dico = Dictionnaire::getId($_GET['id']);

            if ($dico != null) {
                echoHead("$dico->nomD");
                ControleurDictionnaire::renderDictionnaire($dico);
            } else {
                echoHead('');
                echo "<div class='erreur'>Ce dictionnaire n'existe pas.</div>";
                echo "<a href='" . Slim::getInstance()->urlFor('dictionnaires') . "'><h1><- Retour</h1></a>";
            }
        }
    } else
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('dictionnaires'));
})->name('dictionnaire_acces');


//Mot du dictionnaire
$app->get('/dictionnaire/acces/:idD/:idM/', function (int $idD, int $idM) {
    $mot = Mot::getById($idM);
    echoHead($mot->texte);
    if ($mot != null) {
        ControleurMot::renderMot($mot);
    } else {
        echo "<div class='erreur'>Ce mot n'existe pas dans ce dictionnaire ";
        echo "<a href='" . Slim::getInstance()->urlFor('dictionnaires') . "'>Retour aux dictionnaires.</a>";
    }
})->name('mot');


//Recueil
$app->get('/recueil', function () {
    if (isset($_GET['id'])) {
        if (Recueil::exist($_GET['id'])) {
            $rec = Recueil::getById($_GET['id']);
            echoHead($rec->nomR);
            ControleurRecueil::renderRecueil($rec, 1);
        } else
            echo "<div class='erreur'>Recueil inconnu.</div>";
    } else {
        echoHead('Recueils');
        echo "<h1>Tous les recueils</h1>";

        $rec = Recueil::all();

        ControleurRecueil::renderRecueils($rec);
    }
})->name('recueils');


//Productions
$app->get('/production', function () {

    /* idU utilisé en attente de la fonction des comptes */
    $idU = 1;

    if (isset($_GET['id'])) {
        if (Production::exist($_GET['id'], 1)) {
            $prod = Production::getById($_GET['id']);
            echoHead($prod->nomP);
            ControleurProduction::renderProduction($prod);
            exit();
        } else
            echo "<div class='erreur'>Recueil inconnu.</div>";
    }
    echoHead('Productions');
    echo "<h1>Retrouve ici tes productions !</h1>";
    $prods = Production::allCheck($idU);
    ControleurProduction::renderProductions($prods);

    /* L'idU est temporairement passé dans le GET */
    echo "<div class='createNew'><a href='" . Slim::getInstance()->urlFor("new_production") . "?idU=$idU'>+</a>";

})->name('productions');

$app->get('/production/create', function () {

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
    $path = Slim::getInstance()->urlFor("new_production_process") . "?idU=$idU";

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

})->name('new_production');

$app->post('/production/create/process', function () {

    /* Récupération temporaire de l'idU */
    $idU = $_GET['idU'];

    $res = Production::createNew($_POST['nom'], $idU);

    if (is_int($res))
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('new_production') . "?err=$res&idU=$idU");
    else
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor("productions") . "?id=$res->idP");

})->name('new_production_process');


$app->get('/about', function () {
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
})->name('about');


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
    echo "<li><a href='" . Slim::getInstance()->urlFor('home') . "'><img class ='icn' src='" . PATH . "/media/img/icn/logo.png' alt='Logo'></a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('home') . "'><img src='" . PATH . "/media/img/icn/home.png' alt='Accueil'>Accueil</a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('dictionnaires') . "'><img src='" . PATH . "/media/img/icn/dico.png' alt='Dictionnaires'>Dictionnaires</a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('recueils') . "'><img src='" . PATH . "/media/img/icn/recueil.png' alt='Receuils'>Recueils</a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('productions') . "'><img src='" . PATH . "/media/img/icn/production.png' alt='Productions'>Productions</a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('compte') . "'><img src='" . PATH . "/media/img/icn/compte.png' alt='Compte'>Compte</a></li>";
    echo "</ul></nav>";

}

$app->run();