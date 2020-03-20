<?php


namespace bibliovox\controllers;

use bibliovox\views\VueCompte;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class Controleur
{


    protected $req;
    protected $resp;
    protected $args;


    public function __construct(Request $req = null, Response $resp = null, $args = null)
    {
        $this->req = $req;
        $this->resp = $resp;
        $this->args = $args;
        if ($GLOBALS["CONNPROCESS"] == 0) {
            if (!$this->isConnected()) {
                $this->connection();
                exit();
            }
        }
    }

    protected static function isConnected()
    {
        if (isset($_SESSION['connected'])) {
            if ($_SESSION['connected'] == true) {
                return true;
            }
        } else {
            $_SESSION['connected'] = false;
        }
        return false;
    }

    /**
     * Affiche la page de connection
     */
    protected function connection()
    {
        $vue = new VueCompte();
        $vue->connection();
    }
}