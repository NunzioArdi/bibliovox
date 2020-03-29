<?php


namespace bibliovox\views;


use bibliovox\controllers\ControleurAudio;
use bibliovox\controllers\ControleurAudioMot;
use bibliovox\controllers\ControleurCompte;
use bibliovox\controllers\ControleurUtilisateur;
use bibliovox\models\DicoContient;
use bibliovox\models\Dictionnaire;
use bibliovox\models\Utilisateur;

class VueMot extends Vue
{

    public function motDico()
    {
        $mot = $this->res;
        $texte = ucfirst($mot->texte);

        $this->title($texte)->content("<div class = 'mot'>\n  <h1>$texte</h1>");
        if ($mot->image != null)
            $this->content("<img src=' " . $GLOBALS["PATH"] . "/media/img/img/mot/" . $mot->image . "'  alt='img'>");

        $audioMot = ControleurAudioMot::allAudioMot($mot->idM);
        $audios = $mot->audios(ControleurCompte::getIdUser());
        //$audios = ControleurAudioMot::

        //Représentent les chaines à afficher pour chaque audio
        $printAudioPerso = "";
        $printAudioEx = "";

        foreach ($audios as $audio) {
            $date = explode('-', $audio->dateCreation);
            $an = $date[0];
            $mois = $date[1];
            $jour = explode(" ", $date[2])[0];

            $temp = "<p class='date'>Créé le: $jour / $mois / $an</p>";
            $temp .= "<audio controls>";
            $temp .= "<source src=' " . $GLOBALS["PATH"] . "/" . $audio->chemin . "' type='audio/mp3'>";
            $temp .= "</audio>";

                $printAudioPerso .= $temp;
        }

        $printAudioEx = ControleurAudioMot::allSharedPrinter($mot->idM);

        if ($printAudioPerso == "")
            $printAudioPerso = "<p>Tu n'as pas encore d'enregistrement.</p><p>Enregistre-toi tout de suite !</p>";
        if ($printAudioEx == "")
            $printAudioEx = "<p>Aucun exemple pour l'instant.</p><p>Tu peux demander à ta maitresse ou à ton maitre de te choisir comme exemple !</p>";


        if (ControleurCompte::isTeatch()) {

            //Les inforamtions pour les enregistrements personnels et ceux partagé
            //(voir onglet "vos enregistrements personnels" sont traités ci-dessus

            //Préparation des enregistrements créés par les élèves : $histo
            $auds = ControleurAudioMot::allAudioMot($mot->idM);
            $histo = "";

            //Récupération des informations pour chaque audio, puis mise en forme
            foreach ($auds as $row) {
                $idAudio = $row->idAudio;
                $aud = ControleurAudio::getPathById($idAudio);
                $nom = ControleurUtilisateur::getNameById($aud->idU);
                $date = explode('-', $aud->dateCreation);
                $an = $date[0];
                $mois = $date[1];
                $jour = explode(" ", $date[2])[0];

                $crea = "<p class='date'>Créé le: $jour / $mois / $an</p>";
                $chemin = $GLOBALS["PATH"] . "/" . $aud->chemin;
                $pathDelete = $GLOBALS["router"]->urlFor("deleteRecMot") . "?idM=$mot->idM";
                $comm = $aud->commentaire;
                $shared = "";
                $unshared = "";
                if ($row->partage == 1)
                    $shared = "value='1' checked='checked'";
                else {
                    $unshared = "checked='checked'";
                    $shared = "value='0'";
                }


                $histo .= <<<HISTO
<div class="card border-info mb-3" style="min-width: 20rem;">
  <div class="card-header">$nom</div>
  <div class="card-body text-info">
    <p><audio controls><source src="$chemin" type="audio/mp3"></audio controls></p>
    <h5 class="card-title">Commentaire</h5>
    <input id="comm-$idAudio" name="comm" type="text" value="$comm" class="form-control input-md">
  
    
    <h5 class="card-title">Partager l'enregistrement</h5>
    <div class="col-md-4"> 
    <label class="radio-inline" for="radios-0">
      <input type="radio" name="$idAudio" id="shared-$idAudio"  $shared>
      Oui
    </label> 
    <label class="radio-inline" for="radios-1">
      <input type="radio" name="$idAudio" id="radios-1" $unshared>
      Non
    </label>
  </div>
  
  <button id="saveMot" class='saveMot btn btn-block btn-success' value="$idAudio">Enregistrer</button>
  <a href="$pathDelete&idAudio=$idAudio" class='btn btn-block btn-danger'>Supprimer l'enregistrement</a>
  
</div>



  <div class="card-footer"> $crea</div>
</div>
HISTO;

            }

            //appel à la méthode s'occupant de l'édition
            $edit = $this->editDicosMot($mot->idM);

            $this->content(<<<TEACH
<h2>Retrouvez ici toutes les enregistrements de vos élèves</h2>

<ul class="nav nav-pills nav-justified mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-histo-tab" data-toggle="pill" href="#pills-histo" role="tab" aria-controls="pills-histo" aria-selected="true">Derniers enregistrements</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Enregistrements de vos élèves</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-perso-tab" data-toggle="pill" href="#pills-perso" role="tab" aria-controls="pills-perso" aria-selected="false">Vos enregistrements personnels</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-edition-tab" data-toggle="pill" href="#pills-edition" role="tab" aria-controls="pills-edition" aria-selected="false"><b>Édition du mot</b></a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-histo" role="tabpanel" aria-labelledby="pills-histo-tab">
  <div class="card-columns">
    <div class="card text-white bg-info mb-3" style="min-width: 20rem;">
      <div class="card-header">Information</div>
      <div class="card-body">
        <p class="card-text">Les enregistrements sont triés selon leur ordre d'ajout.</p>
        <p class="card-text">Vous avez la possibilité d'ajouter des commentaires ou de supprimer les enregistrements.</p>
        <p class="card-text">Vous pouvez également partager un enregistrement avec tous les élèves.</p>
      </div>
  </div>
    $histo
  </div>
  </div>
  
  
  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">En cours...</div>
  
  
  <div class="tab-pane fade" id="pills-perso" role="tabpanel" aria-labelledby="pills-perso-tab">
  <div class="card-deck">
    <div class="card border-0">
        <div class="card text-white bg-info" >
          <div class="card-header">Information</div>
          <div class="card-body">
            <p class="card-text">La présentation des informations sur cette page ressemble à celle que visualisent vos élèves.</p>
            <p class="card-text">Les exemples que vous visualisez sur cette page sont les mêmes pour tous les élèves.</p>
          </div>
        </div>
        <br>
      <div class="card text-white bg-success" >
          <div class="card-header">Nouvel enregistrement</div>
          <div class="card-body">
            <p class="card-text">Ajoutez un nouvel enregistrement à ce mot !</p>
            <a class="btn btn-light" href=" # ">Nouvel enregistrement</a>
          </div>
      </div>
  </div>
              <div class="card">
        <div class="card-body">
          <h5 class="card-title">Vos enregistrements</h5>
          $printAudioPerso
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Exemples</h5>
          $printAudioEx
        </div>
      </div>
  </div>
</div>

<div class="tab-pane fade" id="pills-edition" role="tabpanel" aria-labelledby="pills-edition-tab">
$edit
</div>
</div>
</div>
</div>


TEACH
            );
        } else {

            $groupe = $GLOBALS['PATH'] . "/media/img/icn/groupe.png";
            $eleve = $GLOBALS['PATH'] . "/media/img/icn/eleve.png";
            $this->content(<<<AUDIOS
<div class='card border-info mb-3 '>
  <div class='card-header'>Enregistrements</div>
  <div class='card-body text-info'>
<div class="card-deck">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Tes enregistrements</h5>
      $printAudioPerso
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Exemples</h5>
      $printAudioEx
    </div>
  </div>
</div>
AUDIOS
            )->content("  </div></div>")->content(ControleurAudio::record());

        }
        $this->afficher();
    }

    private function editDicosMot(int $idM)
    {


        $dico = DicoContient::allDicoMot($idM);
        $all = Dictionnaire::all();
        $mot = $this->res->texte;


        $_POST['idM'] = $idM;
        $ret = <<<CARD
<div class="card">
  <div class="card-header text-center">
    <b>Outils d'édition</b>
  </div>
  <div class="card-body text-center">
    <p class="card-text">Certainnes modifications n'apparaiteront qu'une fois la page rechargée.</p>
    <p class="card-text"> Pensez à actualiser la page une fois vos modifications effectuées.</p>
    <input class="btn btn-block btn-success" type="button" value = "Rafraîchir" onclick="history.go(0)" />
  </div>
  <div class="card-footer text-muted">
<input id="idM" value="$idM" hidden>

<div class="card-deck">

<div class="card border-primary mb-3" style="min-width: 18rem;">
  <div class="card-header">Dictionnaires associés</div>
  <div class="card-body text-info">
    <p class="text-body">Visualisez les dictionnaires contenants ce mot ou modifiez les.</p>
    <form class="form-horizontal"  enctype="multipart/form-data">
<fieldset>


<!-- Select Multiple -->
<div class="form-group">
  <div class="col-md-auto">
    <select id="selectedDico" name="dico" class="form-control" multiple="multiple">
        <option value="-1">Supprimer des dictionnaires</option>


CARD;

        foreach ($all as $r) {
            $bool = false;
            foreach ($dico as $d) {
                if ($r->idD == $d->idD) {
                    $bool = true;
                }
            }
            if ($bool)
                $ret .= "<option value='" . $r->idD . "'selected>" . $r->nomD . "</option>";
            else
                $ret .= "<option value='" . $r->idD . "'>" . $r->nomD . "</option>";
        }
        $ret .= <<<END
</select>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <a href="#" id="dicoButtn" name="singlebutton" class="btn btn-primary">Valider</a>
  </div>
</div>

</fieldset>
</form>
</div>
</div>
END;

        // Modifier mot :
        $ret .= <<<CARD
<div class="card border-success mb-3" style="min-width: 18rem;">
  <div class="card-header">Corriger l'orthographe</div>
  <div class="card-body text-success">
    <form class="form-horizontal">
<fieldset>


<!-- Text input-->
<div class="form-group">
  <div class="col-md-auto">
  <input id="newWord" name="newWord" type="text" placeholder="correction" value="$mot" class="form-control input-md" onkeypress="refuserToucheEntree(event);">    
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
        <a id="buttnChangeWord" href="#" class="btn btn-success">Valider</a>
    </div>
</div>

</fieldset>
</form>
  </div>
</div>

</div>  
CARD;

        // Modifier ou Ajouter une Image
        $path = $GLOBALS["router"]->urlFor("update_pic", ["idD" => -1, "idM" => $idM]);
        $ret .= <<<CARD
<div class="card-deck">

<div class="card border-warning mb-3" style="min-width: 18rem;">
  <div class="card-header">Modifier ou ajouter une image</div>
  <div class="card-body text-warning">
   <form class="form-horizontal" method="post" action='$path' enctype="multipart/form-data">
<fieldset>

<!-- File Button --> 
<div class="form-group">
  <div class="col-md-4">
    <input id="image" name="image" class="input-file" type="file">
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button id="singlebutton" name="singlebutton" class="btn btn-warning">Valider</button>
  </div>
</div>

</fieldset>
</form>
  </div>
</div>
CARD;


        // Bouton de suppression :
        $path = $GLOBALS["router"]->urlFor("delete_mot") . "?idM=" . $idM;
        $ret .= <<<CARD
<div class="card border-danger mb-3" style="min-width: 18rem;">
  <div class="card-header">Supprimer le mot</div>
  <div class="card-body text-danger">
    <h5 class="card-title">Cette action est définitive</h5>
    <p></p>
    <a href="$path" id="bttnDltWrd" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Supprimer</a>
  </div>
</div>

     </div>   
<input id="path" value="{$GLOBALS["PATH"]}" hidden>
<script src="{$GLOBALS["PATH"]}/web/js/bibliovox.js"></script>
  
            </div>
</div>    
CARD;

        return $ret;
    }


    public function creeMot()
    {
        $path = $GLOBALS["router"]->urlFor("new_mot_process");

        $this->title("Nouveau mot")
            ->content(<<<FORM
<form class="form-horizontal" method='post' action='$path' enctype="multipart/form-data">
<fieldset>

<!-- Form Name -->
<legend><h1>Nouveau Mot</h1></legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="mot">Mot</label>  
  <div class="col-md-5">
  <input id="mot" name="mot" type="text" placeholder="Mot" class="form-control input-md" required="">
  <span class="help-block">Mot à ajouter au dictionnaire</span>  
  </div>
</div>

<!-- Select Multiple -->
<div class="form-group">
  <label class="col-md-4 control-label" for="dico">Dictionnaire(s)</label>
  <div class="col-md-5">
    <select id="dico" name="dico[]" class="form-control" multiple="multiple">
FORM
            );
        foreach ($this->res['dico'] as $d) {
            if ($this->res['idD'] == $d->idD) {
                $this->content("<option value='" . $d->idD . "'selected>" . $d->nomD . "</option>");
            } else
                $this->content("<option value='" . $d->idD . "'>" . $d->nomD . "</option>");
        }
        $this->content(<<<FORM
    </select>
  </div>
</div>



<!-- Button trigger modal -->
<div class="form-group">
  <div class="col-md-5">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        Ajouter un audio
    </button>
  </div>
</div>




<!-- File Button --> 
<div class="form-group">
  <label class="col-md-4 control-label" for="image">Importer une image</label>
  <div class="col-md-4">
    <input id="image" name="image" class="input-file" type="file">
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="valid"></label>
  <div class="col-md-4">
    <button type = "submit" id="valid" name="valid" class="btn btn-primary btn-primary""><span class="glyphicon glyphicon-cloud-upload"></span> Enregistrer</button>
  </div>
</div>



<!-- Modal -->
<div class="modal fade " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajouter un audio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-rechercher" role="tab" aria-controls="pills-rechercher" aria-selected="true">Recherche par utilisateur</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-import" role="tab" aria-controls="pills-import" aria-selected="false">Importer un fichier</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-info" role="tab" aria-controls="pills-info" aria-selected="false">Renseignements supplémentaires</a>
            </li>
        </ul>
        
        <div class="tab-content" id="pills-tabContent">
        
         <!--- Import d'un fichier audio --->
            <div class="tab-pane fade" id="pills-import" role="tabpanel" aria-labelledby="pills-import-tab">
                <div class="form-group">
                  <label class="col-md-4 control-label" for="image">Importer un fichier audio</label>
                  <div class="col-md-4">
                    <input id="newAudio" name="newAudio" class="input-file" type="file">
                  </div>
                </div>
            </div>
            
            
            <!--- Tableau de recherche d'un audio --->
            <div class="tab-pane fade" id="pills-rechercher" role="tabpanel" aria-labelledby="pills-rechercher-tab">
                
                    <fieldset>
                    
                        <!-- Form Name -->
                        <legend>Formulaire de recherche</legend>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <div class="col-md-6">
                          <input id="searchBar"  type="text" placeholder="Recherche" class="form-control input-md" onkeypress="refuserToucheEntree(event);">
                          <span class="help-block">Cherchez par nom, prénom, courriel ou identifiant.</span>  
                          </div>
                        </div>
                    
                    </fieldset>
                
                        <!-- Button -->
                        <div class="form-group">
                          <div class="col-md-4">
                            <a href="#" id="searchButtn" class="btn btn-primary">Lancer la recherche</a>
                          </div>
                        </div>
                        
                        <div id="results"></div>
                    <input id="path" value="{$GLOBALS["PATH"]}" hidden>
                    <script src="{$GLOBALS["PATH"]}/web/js/jquery-1.10.2.js"></script>
                    <script src="{$GLOBALS["PATH"]}/web/js/bibliovox.js" ></script>

                    
            </div>
            <!--- Informations complémentaires --->
            <div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
                <p>Vous avez la possibilité de :</p>
                <ul>
                    <li>Sélectionner un fichier audio déjà existant en cherchant son créateur</li>
                    <li>Importer un fichier audio au format mp3</li>
                </ul>
            </div>
        </div>
        
        
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Sauvegarder</button>
      </div>
    </div>
  </div>
</div>

</fieldset>
</form>


FORM
        )->afficher();

    }
}