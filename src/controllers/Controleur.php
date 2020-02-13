<?php


namespace bibliovox\controllers;


use Slim\App;

abstract class Controleur
{
    protected $app;

    /**
     * controlleur constructor.
     * @param App $app Object slim injecté dans le controlleur
     */
    public function __construct($app = NULL)
    {
        $this->app=$app;
    }
}