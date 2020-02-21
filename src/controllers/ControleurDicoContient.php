<?php


namespace bibliovox\controllers;


use bibliovox\models\DicoContient;

class ControleurDicoContient extends Controleur
{


    public static function deleteMot(int $idM)
    {
        DicoContient::where("idM", "=", "$idM")->delete();
    }

    public function processUpdate(int $idM)
    {
        if (isset($_POST['dico'], $idM)){
            DicoContient::deleteBiIdM($idM);
            foreach ($_POST['dico'] as $d){
                DicoContient::create($d, $idM);
            }
        }
        return $this->resp->withRedirect($GLOBALS["router"]->urlFor("mot", ["idD" => $_POST['dico'][0], "idM" => $idM]));
    }
}