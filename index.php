<?php
session_start();


use bibliovox\controllers\ControleurAdmin;
use bibliovox\controllers\ControleurCompte;
use bibliovox\controllers\ControleurDicoContient;
use bibliovox\controllers\ControleurDictionnaire;
use bibliovox\controllers\ControleurMot;
use bibliovox\controllers\ControleurProduction;
use bibliovox\controllers\ControleurRecueil;
use bibliovox\controllers\ControleurHome;
use bibliovox\models\Audio;
use bibliovox\models\Dictionnaire;
use bibliovox\models\Mot;
use bibliovox\models\Utilisateur;
use Illuminate\Database\Capsule\Manager as DB;
use Slim\App as Slim;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

require_once __DIR__ . '/vendor/autoload.php';

//Path
//define('PATH', parse_ini_file('src/conf/conf_path.ini')['path']);
global $PATH;
$PATH = parse_ini_file('src/conf/conf_path.ini')['path'];

/**
 * indique si un processus de connection est en cours.
 * @global integer $GLOBALS ["CONNPROCESS"]
 * @name $CONNPROCESS
 */
global $CONNPROCESS;
$CONNPROCESS = 0;

//Eloquent
$db = new DB();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

//Slim
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$app = new Slim(new Container($configuration));
global $router;
$router = $app->getContainer()->get('router');


/*********************************
 *              GET              *
 *********************************/

//Accueil
$app->get('/', function () {
    $cont = new ControleurHome();
    $cont->index();
})->setName('home');


//Compte
$app->get('/compte', function () {
    $cont = new ControleurCompte();
    $cont->compte();
})->setName('compte');


//Admin
$app->get('/admin', function () {
    $cont = new ControleurAdmin();
    $cont->interface();
})->setName('admin');


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

// Suppression d'un mot
$app->get("/deleteMot", function (Request $req, Response $resp, $args) {
    $idM = $_REQUEST['idM'];
    $cont = new ControleurMot($req, $resp, $args);
    return $cont->deleteMot($idM);
})->setName("delete_mot");

//Supprimer le dictionnaire
$app->get('/dictionnaire/access/{idD}/delete', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurDictionnaire($req, $resp, $args);
    $cont->delete($args['idD']);
})->setName('delete_dico');


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

$app->get('/about', function (Request $req, Response $resp, $args = []) {
    $cont = new ControleurHome();
    $cont->about();
})->setName('about');


/*********************************
 *             POST              *
 *********************************/

//Recueil
$app->post('/recueil/create/process', function (Request $req, Response $resp) {
    $cont = new ControleurRecueil($req, $resp);
    return $cont->processCreate();
})->setName('new_recueil_process');


//Dictionnaire
$app->post('/dictionnaire/create/process', function (Request $req, Response $resp) {
    $cont = new ControleurDictionnaire($req, $resp);
    return $cont->processCreate();
})->setName('new_dictionnaire_process');

$app->post('/dictionnaires/access/{idD}/processImage', function (Request $req, Response $resp, $args) {
    $cont = new ControleurDictionnaire($req, $resp, $args);
    return $cont->updateImage($args['idD']);
})->setName('update_dico_image');

$app->post('/dictionnaires/nouveauMot/process', function (Request $req, Response $resp, $args) {
    $cont = new ControleurMot($req, $resp, $args);
    return $cont->processCreateMot();
})->setName('new_mot_process');

$app->post("/dictionnaire/acces/{idD}/{idM}/editPic/", function (Request $req, Response $resp, $args) {
    $cont = new ControleurMot($req, $resp, $args);
    return $cont->updatePic($args["idD"], $args["idM"]);
})->setName('update_pic');


//Production
$app->post('/production/create/process', function (Request $req, Response $resp, $args) {
    $cont = new ControleurProduction($req, $resp, $args);
    return $cont->processCreateProduction();
})->setName('new_production_process');

$app->post('/production/{idP}/edit/process', function (Request $req, Response $resp, $args) {
    $cont = new ControleurProduction($req, $resp, $args);
    return $cont->processEditProduction($args['idP']);
})->setName('edit_production_process');


//Admin
$app->post("/admin/pannel/createUser", function (Request $req, Response $resp, $args) {
    $cont = new ControleurAdmin($req, $resp, $args);
    return $cont->processCreateUser();
})->setName('createUser');


//Utilisateur
$app->post('/account/login', function (Request $req, Response $resp, $args) {
    $GLOBALS["CONNPROCESS"] = 1;
    $cont = new ControleurCompte($req, $resp, $args);
    return $cont->processLogin();
})->setName('connection');

/********************************
 *      MÉTHODES POUR AJAX      *
 ********************************/
$app->post("/searchAudio", function () {
    if (isset($_POST['data'])) {
        $wods = explode(' ', $_POST['data']);
        $ids[] = null;
        foreach ($wods as $word) {
            foreach (Utilisateur::getID($word) as $id) {
                if (!is_null($id))
                    array_push($ids, $id);
            }
        }

        $chemins[] = null;
        foreach ($ids as $row) {
            if (!is_null($row)) {
                foreach (Audio::getAudio($row) as $chem) {
                    array_push($chemins, $chem['chemin']);
                }
            }
        }
        $chemins = array_unique($chemins);
        foreach ($chemins as $row) {
            if (isset($row))
                echo $row . " ";
        }
    }
});

$app->post("/changeDicoMot", function () {
    if (isset($_POST['data'])) {
        $ids = explode(' ', $_POST['data']);
        $cont = new ControleurDicoContient();
        $cont->processUpdate(intval($_POST['idM']), $ids);
    } else
        echo "ERREUR";
});

$app->post("/udpateWord", function () {
    if (isset($_POST['word'], $_POST['idM'])) {
        Mot::updateMot($_POST['word'], $_POST['idM']);
    }
});

$app->post("/updateDicoName", function () {
    if (isset($_POST['dicoName'], $_POST['idD'])) {
        Dictionnaire::updateName($_POST['dicoName'], $_POST['idD']);
    }
});


try {
    $app->run();
} catch (\Throwable $e) {
    echo "Erreur de lancement du site";
}