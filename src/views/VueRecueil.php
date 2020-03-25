<?php


namespace bibliovox\views;


use bibliovox\controllers\ControleurAudio;
use bibliovox\controllers\ControleurAudioRecueil;
use bibliovox\controllers\ControleurCompte;
use bibliovox\controllers\ControleurUtilisateur;

class VueRecueil extends Vue
{
    public function views(string $view)
    {
        switch ($view) {
            case 'recueil':
                $this->recueil();
                break;
            case 'allrecueil':
                $this->allRecueil();
                break;
            case 'creer':
                $this->create();
                break;
            default:
                break;
        }
        $this->afficher();
    }

    private function recueil()
    {
        $rec = $this->res;
        $this->title = $rec->nomR;
        $this->content .= "<h1>Recueil: <i>$rec->nomR</i></h1>";
        if (ControleurCompte::isTeatch())
            $this->recTeatch($rec);
        else {

            $date = explode('-', $rec->dateR);
            $an = $date[0];
            $mois = $date[1];
            $jour = explode(" ", $date[2])[0];


            $this->content .= "<div class='date'>Créé le: $jour / $mois / $an </div>";
            $this->content .= "<textarea readonly class='cite'>$rec->descriptionR</textarea>";

            $this->content .= ControleurAudio::record();


            //TODO Ajouter l'id de l'utilisateur connecté
            $aPerso = ControleurAudioRecueil::audioPerso($rec->idR, ControleurCompte::getIdUser());
            $aPartage = ControleurAudioRecueil::audioPartage($rec->idR);

            $this->content .= <<<AUDIOS
<div class='card border-info mb-3 '>
  <div class='card-header'>Enregistrements</div>
  <div class='card-body text-info'>
    <div class="card-deck">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Tes enregistrements</h5>
          $aPerso
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Exemples</h5>
          $aPartage
        </div>
      </div>
    </div>
  </div>
</div>
AUDIOS;
        }
    }

    /**
     * Affiche tout les recueil
     */
    private function allRecueil()
    {
        $this->title = "Recueils";

        $this->content = "<h1>Tous les recueils</h1>";
        if ($this->res != null)
            foreach ($this->res as $r) {
                $this->content .= "<a href =\"" . $GLOBALS["router"]->urlFor('recueils') . "$r->idR\"><h2>$r->nomR</h2></a>";
            }
        if (ControleurCompte::isTeatch()) {
            $path = $GLOBALS["router"]->urlFor("new_recueil");
            $this->content .= "<a href='$path' class='btn btn-block btn-success'>+ Créer un recueil</a>";

        }
    }

    private function create()
    {
        $this->title = "Nouveau recueil";

        $this->content .= "<h1>Créer un nouveau recueil</h1>";
        $path = $GLOBALS["router"]->urlFor("new_recueil_process");

        $this->content .= <<<FORM
<form id='new_recueil' method='post' action='$path' enctype="multipart/form-data">
<label>Nom du recueil</label>
<input type='text' name='nom' placeholder='Nom' required>
<label>Texte</label>
<textarea name='texte' class='cite' placeholder='Texte' lang='fr' required></textarea>
<input class='bouton' type="reset" value="Annuler">
<input class="bouton" type="submit" value="Valider">
</form>
FORM;
    }

    private function recTeatch($rec)
    {
        //Récuperation de tous les audios associés au recueil
        $auds = ControleurAudioRecueil::allAudioRec($rec->idR);
        $pathDelete = $GLOBALS["router"]->urlFor("deleteRecordRec") . "?idR=" . $rec->idR;

        //Variable affichant les audios des élèves par ordre d'ajout
        $histo = "";
        foreach ($auds as $aud) {

           // Préparation pour $histo
            $idAudio = $aud->idAudio;
            $details = ControleurAudio::getPathById($aud->idAudio);
            $chemin = $details->chemin;
            $nom = ControleurUtilisateur::getNameById($aud->idU);
            $comm = $details->commentaire;

            $date = explode('-', $details->dateCreation);
            $an = $date[0];
            $mois = $date[1];
            $jour = explode(" ", $date[2])[0];
            $crea = "Créé le: $jour / $mois / $an";

            $shared = "";
            $unshared = "";
            if ($aud->partage == 1)
                $shared = "checked='checked'";
            else
                $unshared = "checked='checked'";

            echo $aud->partage;

            // Affichage des audio des élèves en fonction de la date
            $histo .= <<<AUD
<div class="card border-info mb-3" style="min-width: 20rem;">
  <div class="card-header">$nom</div>
  <div class="card-body text-info">
    <h5 class="card-title">Ton enregistrement</h5>
    <p><audio controls><source src="$chemin" type="audio/mp3"></audio controls></p>
    <h5 class="card-title">Commentaire</h5>
    <input id="comm-$idAudio" name="comm" type="text" value="$comm" class="form-control input-md">
  
    
    <h5 class="card-title">Partager l'enregistrement</h5>
    <div class="col-md-4"> 
    <label class="radio-inline" for="radios-0">
      <input type="radio" name="$idAudio" id="shared-$idAudio" value="1" $shared>
      Oui
    </label> 
    <label class="radio-inline" for="radios-1">
      <input type="radio" name="$idAudio" id="radios-1" value="0" $unshared>
      Non
    </label>
  </div>
  
  <button id="saveRec" class='btn btn-block btn-success' value="$idAudio">Enregistrer</button>
  <a href="$pathDelete&idAudio=$idAudio" class='btn btn-block btn-danger'>Supprimer l'enregistrement</a>
  
</div>



  <div class="card-footer"> $crea</div>
</div>
AUD;
        }

        //préparation de l'affichage des enregistrements perso
        $aPerso = ControleurAudioRecueil::audioPerso($rec->idR, ControleurCompte::getIdUser());
        $aPartage = ControleurAudioRecueil::audioPartage($rec->idR);
        $perso = <<<AUDIOS

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Vos enregistrements</h5>
          $aPerso
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Exemples</h5>
          $aPartage
        </div>
      </div>
AUDIOS;

        //Préparation de l'affichage de l'éditeur

        $titre = $rec->nomR;
        $contenu = $rec->descriptionR;
        $deleteRec = $GLOBALS["router"]->urlFor("delete_recueil") . "?idR=$rec->idR";

        $this->content .= <<<NAV
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
    <a class="nav-link" id="pills-edition-tab" data-toggle="pill" href="#pills-edition" role="tab" aria-controls="pills-edition" aria-selected="false"><b>Édition du recueil</b></a>
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
            <p class="card-text">Ajoutez un nouvel enregistrement à ce recueil !</p>
            <a class="btn btn-light" href=" # ">Nouvel enregistrement</a>
          </div>
      </div>
  </div>
        $perso
  </div>
</div>

<div class="tab-pane fade" id="pills-edition" role="tabpanel" aria-labelledby="pills-edition-tab">

    <form class="form-horizontal">
<fieldset>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="titre">Titre</label>  
  <div class="col-md-4">
  <input id="titre" name="titre" type="text" placeholder="titre" class="form-control input-md" required value="$titre">
    
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="contenu">Contenu</label>
  <div>                     
    <textarea class="cite" id="contenu" name="contenu" required>$contenu</textarea>
  </div>
</div>

<div class="form-group">
    <div id="er" class="btn btn-success">Enregistrer</div>
    <a id="editRec" href="$deleteRec" class="btn btn-danger">Supprimer le recueil</a>
</div>

</fieldset>
</form>

</div>


</div>


NAV;
    }

}