<?php


namespace bibliovox\views;


use bibliovox\models\DicoContient;
use bibliovox\models\Dictionnaire;

class VueMot extends Vue
{

    public function views(string $view)
    {
        switch ($view) {
            case "motDico":
                $this->motDico();
                break;
            case "createMot" :
                $this->creMot();
            default:
                break;
        }
        $this->afficher();
    }

    private function motDico()
    {
        $mot = $this->res;
        $texte = ucfirst($mot->texte);

        $this->title = $texte;


        $this->content .= "<div class = \"mot\">";
        $this->content .= "<h1>$texte</h1>";
        if ($mot->image != null)
            $this->content .= "<img src=\" " . $GLOBALS["PATH"] . "/media/img/img/mot/" . $mot->image . "\"  alt=\"img\">";

        $audios = $mot->audios();
        foreach ($audios as $audio) {
            $date = explode('-', $audio->dateCreation);
            $this->content .= "<div class=\"date\">Créé le: " . $date['2'] . "/" . $date['1'] . "/" . $date['0'] . "</div>";

            $this->content .= "<audio controls>";
            $this->content .= "<source src=\" " . $GLOBALS["PATH"] . "/" . $audio->chemin . "\" type=\"audio/mp3\">";
            $this->content .= "</audio></div>";
        }

        $this->content .= "<h2>Enregistre toi !</h2>";
        //TODO
        //Appel à l'enregistreur


        //TODO controler qu'il s'agit d'un prof/admin
        if (true) {
            $this->editDicosMot($mot->idM);
        }
    }

    private function editDicosMot(int $idM)
    {
        $dico = DicoContient::allDicoMot($idM);
        $all = Dictionnaire::all();
        $mot = $this->res->texte;


        $_POST['idM'] = $idM;
        $this->content .= <<<CARD
<div class="card text-center">
  <div class="card-header">
    <b>Outils d'édition</b>
  </div>
  <div class="card-body">
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
                $this->content .= "<option value=\"" . $r->idD . "\"selected>" . $r->nomD . "</option>";
            else
                $this->content .= "<option value=\"" . $r->idD . "\">" . $r->nomD . "</option>";
        }
        $this->content .= <<<END
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
        $this->content .= <<<CARD
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
        $this->content .= <<<CARD
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
        $this->content .= <<<CARD
<div class="card border-danger mb-3" style="min-width: 18rem;">
  <div class="card-header">Supprimer le mot</div>
  <div class="card-body text-danger">
    <h5 class="card-title">Cette action est définitive</h5>
    <p></p>
    <a href="$path" id="bttnDltWrd" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Supprimer</a>
  </div>
</div>

     </div>   

<script src="{$GLOBALS["PATH"]}/web/js/bibliovox.js"></script>
  
            </div>
</div>    
CARD;


    }




    private function creMot()
    {
        $this->title = "Nouveau mot";
        $path = $GLOBALS["router"]->urlFor("new_mot_process");
        $this->content .= <<<FORM
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
FORM;
        foreach ($this->res['dico'] as $d) {
            if ($this->res['idD'] == $d->idD) {
                $this->content .= "<option value=\"" . $d->idD . "\"selected>" . $d->nomD . "</option>";
            } else
                $this->content .= "<option value=\"" . $d->idD . "\">" . $d->nomD . "</option>";
        }
        $this->content .= <<<FORM
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
                <p>Attention, si vous importez un fichier audio, seul celui-ci sera pris en compte.</p>
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


FORM;

    }
}