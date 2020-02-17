<?php

use bibliovox\controllers\ControleurCompte;
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

$app->get('/production/edit/{idP}', function (Request $req, Response $resp, $args) {
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

//TODO MVC
$app->post('/recueil/create/process', function (Request $req, Response $resp, $args = []) {
    $res = Recueil::createNew($_POST['nom'], $_POST['texte']);

    if (is_int($res))
        return $resp->withRedirect($GLOBALS["router"]->urlFor('new_recueil') . "?err=$res");
    else
        return $resp->withRedirect($GLOBALS["router"]->urlFor("recueils") . "?id=$res->idR");

})->setName('new_recueil_process');

//TODO MVC
$app->post('/dictionnaire/create/process', function (Request $req, Response $resp, $args = []) {

    $res = Dictionnaire::createNew($_POST['nom'], $_POST['description']);

    if (is_int($res))
        return $resp->withRedirect($GLOBALS["router"]->urlFor('new_dictionnaire') . "?err=$res");
    else
        return $resp->withRedirect($GLOBALS["router"]->urlFor("dictionnaire_acces") . "?id=$res->idD");

})->setName('new_dictionnaire_process');

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
    $cont = new ControleurHome();
    $cont->about();
})->setName('about');




$app->run();

//echo "</body>";
//echo "</html>";