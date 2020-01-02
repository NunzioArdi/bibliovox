<?php

use bibliovox\models\ControleurMot;
use bibliovox\models\DicoContient;
use bibliovox\models\Mot;
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
    echo "hello world";
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
    echo "<a href='" . Slim::getInstance()->urlFor('dictionnaire_alpha') . "'>Dictionnaire alphabétique</a><br>";
    echo "Ou sélectionnez un dictionnaire:";
    echo "<form id='f1' method='get' action='" . PATH . "/dictionnaire/acces'>";
    echo "<select name='id'>";

    foreach ($dico as $d) {
        echo "<option value='" . $d->idD . "'>" . $d->nomD . "</option>";
    }

    echo "</select>\n<input type = 'submit' class='bouton' name='valider' value='Valider'></form>";

})->name('dictionnaires');


//Accès à un dictionnaire
$app->get('/dictionnaire/acces', function () {
    if (isset($_GET['id'])) {
        $dico = Dictionnaire::getId($_GET['id']);
        if ($dico != null) {
            echoHead("$dico->nomD");
            echo "<h1>Accès au dictionnaire <i>$dico->nomD</i></h1>";
            $mots = DicoContient::motContenuDico($_GET['id']);
            foreach ($mots as $m) {
                echo "<a href='" . PATH . "/dictionnaire/acces/$dico->idD/$m->texte'>$m->texte.</a>";
            }
        } else {
            echo "<div class='erreur'>Ce dictionnaire n'existe pas.</div>";
            echo "<a href='" . Slim::getInstance()->urlFor('dictionnaires') . "'>Retour.</a>";
        }
    } else
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('dictionnaires'));
})->name('dictionnaire_acces');


$app->get('/dictionnaire/alphabetique', function () {
    echoHead('Dictionnaires');
    echo "<h1>Tous les mots par ordre alphabétique</h1>";
    $mots = Mot::allAlpha();

    foreach ($mots as $m) {
        echo "<a href='" . PATH . "/dictionnaire/acces/-1/$m->texte'>$m->texte.</a>";
    }
})->name('dictionnaire_alpha');

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
    echo "<li><a href='" . Slim::getInstance()->urlFor('home') . "'><img class ='icn' src='" . PATH . "/media/img/icn/logo.png'></a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('home') . "'><img src='" . PATH . "/media/img/icn/home.png'>Accueil</a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('dictionnaires') . "'><img src='" . PATH . "/media/img/icn/dico.png'>Dictionnaires</a></li>";
    echo "<li><a href='" . Slim::getInstance()->urlFor('compte') . "'><img src='" . PATH . "/media/img/icn/compte.png'>Votre compte</a></li>";
    echo "</ul></nav>";

}

$app->run();