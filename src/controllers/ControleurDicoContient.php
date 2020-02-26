<?php


namespace bibliovox\controllers;


use bibliovox\models\DicoContient;

class ControleurDicoContient extends Controleur
{


    public static function deleteMot(int $idM)
    {
        DicoContient::where("idM", "=", "$idM")->delete();
    }

    public function processUpdate(int $idM, $idD)
    {
        $bool = false;
        if (isset($idD, $idM)){
            DicoContient::deleteBiIdM($idM);
            foreach ($idD as $r){
                if ($r == "-1")
                    $bool = true;
            }
            if ($bool == false)
            foreach ($idD as $d){
                if (intval($d) != 0){
                    DicoContient::create(intval($d), $idM);
                    echo($d);
                }
            }
        }
    }
}