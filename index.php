<?php

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
    //$dico = Dictionnaire::all();
    echo "<a href='" . Slim::getInstance()->urlFor('dictionnaire_alpha') . "'>Dictionnaire alphabétique</a><br>";
    echo "Ou sélectionnez un dictionnaire:";
    echo "<form id='f1' method='get' action='" . PATH . "/dictionnaire/acces'>";
    echo "<select name='idD'>";
    /**
    foreach ($dico as $d) {
        echo "<option value='" . $d->idD . "'>" . $d->nomD . "</option>";
    }
     **/
    echo "<option value='1'>3</option>";
    echo "</select>\n<input type = 'submit' class='bouton' name='valider' value='Valider'></form>";

})->name('dictionnaires');


//Accès à un dictionnaire
$app->get('dictionnaire/acces', function (){
    echo "<h1>Accès au dictionnaire</h1>";
})->name('dictionnaire_acces');


$app->get('/dictionnaire/alphabetique', function () {
    echoHead('Dictionnaires');
    echo "<h1>Les Dictionnaires</h1>";

})->name('dictionnaire_alpha');


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
    echo "<link rel='icon' href='" . PATH . "/web/img/icones/logo.png'>\n";
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