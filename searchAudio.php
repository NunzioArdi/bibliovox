<?php

use bibliovox\models\Audio;
use bibliovox\models\Utilisateur;
var_dump($_POST['words']);
if (isset($_POST['words'])) {
    $wods = explode(' ', $_POST['words']);
    $ids[] = null;
    foreach ($wods as $word){
        array_push($ids, Utilisateur::getID($word));
    }

    $chemins[] = null;
    foreach ($ids as $row){
        array_push($chemins, Audio::getAudio($row));
    }

    echo json_encode($chemins);
}
