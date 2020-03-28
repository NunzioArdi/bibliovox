<?php


namespace bibliovox\controllers;


use bibliovox\views\HomeView;

class ControleurHome extends Controleur
{

    public function index(){
        $view = new HomeView();
        $view->index();
    }

    public function about(){
        $view = new HomeView();
        $view->about();
    }
}