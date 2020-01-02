<?php
use Illuminate\Database\Capsule\Manager as DB;
use Slim\Slim;
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



$app->get('/', function () {
    echoHead('Accueil');
    echo "hello world";
})->name('compte');



$app->get('/', function () {
    echoHead('Accueil');
    echo "hello world";
})->name('dictionnaire');




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
    echo "<li><a href='" . Slim::getInstance()->urlFor('compte') . "'><img src='" . PATH . "/media/img/icn/compte.png'>Votre compte</a></li>";
    echo "</ul></nav>";

}

$app->run();