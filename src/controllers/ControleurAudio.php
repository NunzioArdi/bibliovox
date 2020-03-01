<?php


namespace bibliovox\controllers;


use bibliovox\models\Audio;

class ControleurAudio extends Controleur
{
    public static function createAudio(int $idU, String $commentaire = null)
    {
        if (isset($_FILES['newAudio']) AND $_FILES['newAudio']['error'] == 0) {
            $extension_upload = pathinfo($_FILES['newAudio']['name'])['extension'];
            $extensions_autorisees = array('mp3');
            if (in_array($extension_upload, $extensions_autorisees)) {
                $fileName = rand() . filter_var($_FILES['newAudio']['name'], FILTER_SANITIZE_URL);
                move_uploaded_file($_FILES['newAudio']['tmp_name'], 'media/aud/' . $fileName);

                $ret = Audio::newAudio($fileName, $idU, $commentaire);

            } else {
                return -2;
            }

        } else
            return -1;
        return $ret->idAudio;
    }


    //On pourra éventuellement passer des paramètres pour l'enregistrement si nécessaire
    public static function record() : String
    {
        $res = <<<REC
<div class="card text-white bg-info mb-3" style="min-width: 18rem;">
  <div class="card-header">Enregistre toi !</div>
  <div class="card-body">
    <p>Enregistre toi en utilisant le bouton "enregistrement".</p>
    <p class="text-center">
        <a href="#" class="btn btn-success" id="bRecord">Enregistrement</a>
        <a href="#" class="btn btn-light" id="bPause">Stop</a>
        <a href="#" class="btn btn-warning" id="bUpload">Envoyer</a>
    </p>
  </div>
</div>
REC;
        return $res;

    }
}