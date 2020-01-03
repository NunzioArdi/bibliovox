<?php

use bibliovox\controllers\ControleurMot;
use bibliovox\controllers\ControleurRecueil;
use bibliovox\models\DicoContient;
use bibliovox\models\Mot;
use bibliovox\models\Recueil;
use Illuminate\Database\Capsule\Manager as DB;
use Slim\Slim;
use bibliovox\models\Dictionnaire;

require_once __DIR__ . '/vendor/autoload.php';

define('PATH', parse_ini_file('src/conf/conf_path.ini')['path']);

$db = new DB();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$app = new Slim();


$app->get('/', function () {
    echoHead('Accueil');
    echo "<p>Bibli O’vox a été imaginé par Christophe Buck, Sophie Deleys et Marie Lequèvre dans le cadre d’un master dans le domaine des sciences de l’éducation. Notre production s’appuiera sur les propositions résultant de ce travail. </p>";
    echo "<p>Bibli O’vox est un outil qui a pour but de favoriser le développement du langage oral dans un cadre scolaire. </p>";
    echo "<p>Ce système a une visée éducative, cela signifie que les principaux utilisateurs de ce système seront des élèves de maternelle et primaire dans un premier temps. Il faudra donc s’assurer de la simplicité d’utilisation. Le système doit combler un manque constaté par plusieurs enseignants, celui de la communication orale qu’il soit en classe ou à la maison. L’outil concernera tout élève rencontrant des difficultés pour s’exprimer en langue française dans un premier temps. Cependant il visera plus particulièrement les élèves allophones (le français n’étant pas leur langue maternelle) ou étrangers (ne communiquant pas forcément en français avec leur entourage). Un outil permettant d’exploiter le français à l’oral dans leur foyer pourrait alors se révéler profitable. En effet, les parents pourraient bénéficier de ce nouveau dispositif, puisque la vie orale en classe, ne peut être partagée avec un cahier du jour classique écrit. </p>";
    echo "<p>Bibli O’vox est donc un cahier de vie oral permettant un suivi des activités des enfants, et ce, jour après jour, que cela soit par les enfants pour constater leur progrès dans l’apprentissage de la langue française ou par les parents afin d’avoir un aperçu de la vie que mène leur enfant au sein de la classe durant la journée et qu’ils puissent eux aussi s’investir dans les devoirs de leur enfant. Cela renforcera le lien entre l’école et le foyer en partageant ce qui a été étudié en classe.</p>";
})->name('home');


$app->get('/compte', function () {
    echoHead('Compte');
    echo "account";
})->name('compte');


//Dictionnaires
$app->get('/dictionnaires/', function () {
    echoHead('Dictionnaires');
    echo "<h1>Les Dictionnaires</h1>";
    $dico = Dictionnaire::all();
    echo "<h2><a href='" . Slim::getInstance()->urlFor('dictionnaire_acces') . "?id=-1'><img src='".PATH."/media/img/img/dico/alpha.png'>Dictionnaire alphabétique</a></h2>";

    foreach ($dico as $d) {
        echo "<h2><a href='" . Slim::getInstance()->urlFor('dictionnaire_acces') . "?id=$d->idD'><img src='".PATH."/media/img/img/dico/$d->imageD'>$d->nomD</a></h2>";
    }


})->name('dictionnaires');


//Accès à un dictionnaire
$app->get('/dictionnaire/acces', function () {
    if (isset($_GET['id'])) {
        if ($_GET['id'] == -1) {
            echoHead('Tous les mots');
            echo "<h1>Tous les mots par ordre alphabétique</h1>";
            echo "<img src='".PATH."/media/img/img/dico/alpha.png'>";
            $mots = Mot::allAlpha();

            foreach ($mots as $m) {
                echo "<h2><a href='" . PATH . "/dictionnaire/acces/-1/$m->texte'>$m->texte</a></h2>";
            }
        } else {
            $dico = Dictionnaire::getId($_GET['id']);

            if ($dico != null) {
                echoHead("$dico->nomD");
                echo "<h1>Accès au dictionnaire <i>$dico->nomD</i></h1>";
                echo "<img src='".PATH."/media/img/img/dico/$dico->imageD'>";
                $mots = DicoContient::motContenuDico($_GET['id']);
                foreach ($mots as $m) {
                    echo "<h2><a href='" . PATH . "/dictionnaire/acces/$dico->idD/$m->texte'>$m->texte</a></h2>";
                }
            } else {
                echo "<div class='erreur'>Ce dictionnaire n'existe pas.</div>";
                echo "<a href='" . Slim::getInstance()->urlFor('dictionnaires') . "'>Retour.</a>";
            }
        }
    } else
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('dictionnaires'));
})->name('dictionnaire_acces');


//Mot du dictionnaire
$app->get('/dictionnaire/acces/:idD/:texte', function (int $idD, string $texte) {
    echoHead($texte);
    $mot = Mot::getByTxt($texte);
    if ($mot != null) {
        ControleurMot::renderMot($mot);
    } else {
        echo "<div class='erreur'>Le mot <i>$texte</i> n'existe pas dans ce dictionnaire";
        echo "<a href='" . Slim::getInstance()->urlFor('dictionnaires') . "'>Retour aux dictionnaires.</a>";
    }
})->name('mot');


$app->get('/about', function () {
    echoHead('À propos');
    echo "<div>Icons made by <a href=\"https://www.flaticon.com/authors/eucalyp\" title=\"Eucalyp\">Eucalyp</a> from <a href=\"https://www.flaticon.com/\" title=\"Flaticon\">www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href='https://www.flaticon.com/authors/ddara' title='dDara'>dDara</a> from <a href=''https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href=\"https://www.flaticon.com/authors/freepik\" title=\"Freepik\">Freepik</a> from <a href=\"https://www.flaticon.com/\" title=\"Flaticon\">www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href=\"https://www.flaticon.com/authors/freepik\" title=\"Freepik\">Freepik</a> from <a href=\"https://www.flaticon.com/\" title=\"Flaticon\">www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href=\"https://www.flaticon.com/authors/itim2101\" title=\"itim2101\">itim2101</a> from <a href=\"https://www.flaticon.com/\" title=\"Flaticon\">www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href=\"https://www.flaticon.com/authors/freepik\" title=\"Freepik\">Freepik</a> from <a href=\"https://www.flaticon.com/\" title=\"Flaticon\">www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href=\"https://www.flaticon.com/authors/freepik\" title=\"Freepik\">Freepik</a> from <a href=\"https://www.flaticon.com/\" title=\"Flaticon\">www.flaticon.com</a></div>";
    echo "<div>Icons made by <a href=\"https://www.flaticon.com/authors/freepik\" title=\"Freepik\">Freepik</a> from <a href=\"https://www.flaticon.com/\" title=\"Flaticon\">www.flaticon.com</a></div>";
})->name('about');


$app->get('/recueil', function () {
    if (isset($_GET['id'])){
        if (Recueil::exist($_GET['id'])) {
            $rec = Recueil::getById($_GET['id']);
            echoHead($rec->nomR);
            ControleurRecueil::renderRecueil($rec);
            exit();
        } else
            echo "<div class='erreur'>Recueil inconnu.</div>";

    }
    echoHead('Recueils');
    echo "<h1>Tous vos recueils</h1>";

    $rec = Recueil::all();

    ControleurRecueil::renderRecueils($rec);


})->name('recueils');


$app->get('/productions', function () {
    echoHead('Productions');

})->name('productions');


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
    echo "<li><a href='" . Slim::getInstance()->urlFor('home') . "'><img class ='icn' src='" . PATH . "/media/img/icn/logo.png'></a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('home') . "'><img src='" . PATH . "/media/img/icn/home.png'>Accueil</a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('dictionnaires') . "'><img src='" . PATH . "/media/img/icn/dico.png'>Dictionnaires</a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('recueils') . "'><img src='" . PATH . "/media/img/icn/recueil.png'>Recueils</a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('productions') . "'><img src='" . PATH . "/media/img/icn/production.png'>Productions</a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('compte') . "'><img src='" . PATH . "/media/img/icn/compte.png'>Compte</a></li>";
    echo "</ul></nav>";

}

$app->run();