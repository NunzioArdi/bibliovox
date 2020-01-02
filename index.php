<?php
use Illuminate\Database\Capsule\Manager as DB;
use Slim\Slim;
require_once __DIR__ . '/vendor/autoload.php';


$db = new DB();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$app = new Slim();

$app->get('/', function () {
    echo "hello world";
})->name('home');



$app->run();