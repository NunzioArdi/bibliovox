<?php

use bibliovox\controllers\ControleurCompte;
use bibliovox\controllers\ControleurDicoContient;
use bibliovox\controllers\ControleurDictionnaire;
use bibliovox\controllers\ControleurMot;
use bibliovox\controllers\ControleurProduction;
use bibliovox\controllers\ControleurRecueil;
use bibliovox\controllers\ControleurHome;
use Illuminate\Database\Capsule\Manager as DB;
use Slim\App as Slim;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

require_once __DIR__ . '/vendor/autoload.php';

//define('PATH', parse_ini_file('src/conf/conf_path.ini')['path']);
global $PATH;
$PATH = parse_ini_file('src/conf/conf_path.ini')['path'];
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


$app->get('/compte', function () {
    $cont = new ControleurCompte();
    $cont->compte();
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
})->setName('new_dictionnaire');

//Accès à un dictionnaire
$app->get('/dictionnaire/acces/{idD}[/]', function (Request $req, Response $resp, $args) {
    $cont = new ControleurDictionnaire();
    if ($args["idD"] == 0) {                          // dico alphabet
        $cont->getDicoAlphabet();
    } elseif ($args["idD"] > 0) {                      // dico thème
        $cont->getDicoTheme($args["idD"]);
    }
})->setName('dictionnaire_acces');

//Mot du dictionnaire
$app->get('/dictionnaire/acces/{idD}/{idM}/', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurMot();
    $cont->getMotDico($args["idM"], $args["idD"]);
})->setName('mot');

//Créer un mot
$app->get('/dictionnaire/acces/{idD}/nouveauMot', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurMot();
    $cont->createMot($args['idD']);
})->setName('new_mot');


//Recueil
$app->get('/recueil[/[{id}]]', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurRecueil();
    if (isset($args["id"]) && is_numeric($args["id"]))
        $cont->recueil($args["id"]);
    else
        $cont->allRecueil();
})->setName('recueils');

$app->get('/recueil/create/', function () {
    $cont = new ControleurRecueil();
    $cont->creerRecueil();
})->setName('new_recueil');


//Production
$app->get('/production/create', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurProduction();
    $cont->createProduction();
})->setName('new_production');

$app->get('/production/{idP}/edit[/]', function (Request $req, Response $resp, $args) {
    $cont = new ControleurProduction();
    $cont->editProduction($args['idP']);
})->setName('edit_production');

$app->get('/production[/[{id}]]', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurProduction();
    if (isset($args["id"]) && is_numeric($args["id"])) {
        $cont->production($args["id"]);
    } else {
        $cont->allProduction();
    }
})->setName('productions');


/********************************
*             POST              *
*********************************/

$app->post('/recueil/create/process', function (Request $req, Response $resp) {
    $cont = new ControleurRecueil($req, $resp);
    return $cont->processCreate();
})->setName('new_recueil_process');

$app->post('/dictionnaire/create/process', function (Request $req, Response $resp) {
    $cont = new ControleurDictionnaire($req, $resp);
    return $cont->processCreate();
})->setName('new_dictionnaire_process');


$app->post('/production/create/process', function (Request $req, Response $resp, $args) {
    $cont = new ControleurProduction($req, $resp, $args);
    return $cont->processCreateProduction();
})->setName('new_production_process');


$app->post('/production/{idP}/edit/process', function (Request $req, Response $resp, $args) {
    $cont = new ControleurProduction($req, $resp, $args);
    return $cont->processEditProduction($args['idP']);
})->setName('edit_production_process');

$app->post('/dictionnaires/nouveauMot/process', function (Request $req, Response $resp, $args) {
    $cont = new ControleurMot($req, $resp, $args);
    return $cont->processCreateMot();
})->setName('new_mot_process');

$app->post('/dictionnaires/access/{idM}/majAppartenanceMots/process', function (Request $req, Response $resp, $args) {
    $cont = new ControleurDicoContient($req, $resp, $args);
    return $cont->processUpdate($args["idM"]);
})->setName('update_dico_contient');

$app->get('/about', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurHome();
    $cont->about();
})->setName('about');



/********************************
 *      MÉTHODES POUR AJAX      *
 ********************************/

// Suppression d'un mot
$app->get("/deleteMot", function (Request $req, Response $resp, $args){
    $idM = $_REQUEST['idM'];
    $cont = new ControleurMot($req, $resp, $args);
    return $cont->deleteMot($idM);
})->setName("delete_mot");


$app->run();

//echo "</body>";
//echo "</html>";