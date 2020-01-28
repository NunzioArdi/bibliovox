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
$app->get('/dictionnaires/', function () {
    echoHead('Dictionnaires');
    echo "<h1>Les Dictionnaires</h1>";
    $dico = Dictionnaire::all();
    ControleurDictionnaire::renderDictionnaires($dico);

})->name('dictionnaires');


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
    if ($mot != null && DicoContient::matchIDs($idM, $idD)) {
        echoHead($mot->texte);
        ControleurMot::renderMot($mot);
    } else {
        echoHead('ERREUR');
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
        } else {
            echoHead("ERREUR");
            echo "<div class='erreur'>Recueil inconnu.</div>";
        }
    } else {
        echoHead('Recueils');
        echo "<h1>Tous les recueils</h1>";

        $rec = Recueil::all();

        ControleurRecueil::renderRecueils($rec);
    }
})->name('recueils');


//Productions
$app->get('/production', function () {

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
    $prods = Production::allCheck(1);
    ControleurProduction::renderProductions($prods);

})->name('productions');


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