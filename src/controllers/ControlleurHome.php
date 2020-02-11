<?php


namespace bibliovox\controllers;


use bibliovox\views\HomeView;

class ControlleurHome extends Controlleur
{

    public function index(){
        $view = new HomeView();
        $view->views('index');
    }
}