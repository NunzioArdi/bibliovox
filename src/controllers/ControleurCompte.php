<?php

namespace bibliovox\controllers;

use bibliovox\views\VueCompte;

class ControleurCompte extends Controleur{

    public function compte()
    {
        $vue = new VueCompte();
        $vue->views('compte');
    }
}