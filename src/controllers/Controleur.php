<?php


namespace bibliovox\controllers;

use Slim\Http\Request;
use Slim\Http\Response;

abstract class Controleur
{


    protected $req;
    protected $resp;
    protected $args;

    public function __construct(Request $req = null, Response $resp = null, $args = null)
    {
        $this->req=$req;
        $this->resp=$resp;
        $this->args=$args;
    }
}