<?php


namespace bibliovox\controllers;


use bibliovox\models\Audio;

class ControleurAudio extends Controleur
{
    public static function createAudio(int $idU, String $commentaire)
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
}