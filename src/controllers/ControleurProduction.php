<?php


namespace bibliovox\controllers;


class ControleurProduction
{
    static function renderProdctions($prods) {
        if ($prods != null)
            foreach ($prods as $r) {
                echo "<a href ='".PATH."production?id=$r->idP'><h2>$r->nomP</h2></a>";
            }
    }

    static function renderProduction($prod){
        if ($prod == null) {
            echo "<div class='erreur'>Production inconnue.</div>";
        } else {
            echo "<h1>Production: <i>$prod->nomP</i></h1>";
            $date = $prod->dateP;
            echo "<div class='date'>Créé le: ". $date['2'] ."/". $date['1'] ."/". $date['0'] ."</div>";
            echo "<cite>$prod->commentaire</cite>";

            echo "<div class='comm'>Ton enregistrement: </div>";
            echo "<audio controls>";
            echo "<source src='" . PATH . "/media/aud/rec/" . $prod->audio . "' type='audio/mp3'>";
            echo "</audio></div>";
        }
    }
}