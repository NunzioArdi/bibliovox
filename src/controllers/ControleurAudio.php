<?php


namespace bibliovox\controllers;


use bibliovox\models\Audio;

class ControleurAudio extends Controleur
{
    public static function createAudio(int $idU, String $commentaire = null)
    {
        if (isset($_FILES['newAudio'])) {
            if (!$_FILES['newAudio']['error']) {
                $fileName = rand(0, 99) . "_" . date("d-m-Y_H-i-s") . ".mp3";
                move_uploaded_file($_FILES['newAudio']['tmp_name'], 'media/aud/' . $fileName);

                $ret = Audio::newAudio($fileName, $idU, $commentaire);

            } else
                return -1;
        }
        return $ret->idAudio;
    }


    //On pourra éventuellement passer des paramètres pour l'enregistrement si nécessaire
    public static function record(): String
    {
        $res = <<<REC
<div class="card text-white bg-info mb-3" style="min-width: 18rem;">
  <div class="card-header">Enregistre toi !</div>
  <div class="card-body">
    <p>Enregistre toi en utilisant le bouton "enregistrement".</p>
    <p class="text-center">
        <button  class="btn btn-success" id="bRecord">Enregistrement</button>
        <button  class="btn btn-light" id="bPause">Stop</button>
        <button  class="btn btn-light" id="bPlay">Écouter</button>
        <button  class="btn btn-warning" id="bUpload">Envoyer</button>
        <button  class="btn btn-danger" id="bReset" title="Efface le début de l'enregistrement">Recommencer</button>

    </p>
  </div>
</div>

<script src="{$GLOBALS["PATH"]}/web/js/jquery-1.10.2.js"></script>
<script src="{$GLOBALS["PATH"]}/web/js/recAudio.js" ></script>

REC;
        return $res;

    }


    //Le nom de cette fonction est trompeur attention
    public static function getPathById($idAudio)
    {
        return Audio::where("idAudio", "=", "$idAudio")->first();
    }


}