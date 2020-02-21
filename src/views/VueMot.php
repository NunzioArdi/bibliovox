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

        if ($mot->audio != null) {
            $this->content .= "<audio controls>";
            $this->content .= "<source src=\" " . $GLOBALS["PATH"] . "/media/aud/dico/" . $mot->audio . "\" type=\"audio/mp3\">";
            $this->content .= "</audio></div>";
        } else {
            $this->content .= "<h2>Enregistre toi !</h2>";
            //TODO
            //Appel à l'enregistreur
        }

        //TODO controler qu'il s'agit d'un prof/admin
        if (true) {
            $this->editDicosMot($mot->idM);
        }
    }

    private function editDicosMot(int $idM)
    {
        $dico = DicoContient::allDicoMot($idM);
        $all = Dictionnaire::all();

        $path = $GLOBALS["router"]->urlFor("update_dico_contient", ["idM" => $idM]);

        $_POST['idM'] = $idM;
        $this->content .= <<<CARD
<div class="card-deck">

<div class="card border-primary mb-3" style="min-width: 18rem;">
  <div class="card-header">Dictionnaires associés</div>
  <div class="card-body text-info">
    <p class="text-body">Visualisez les dictionnaires contenants ce mot ou modifiez les.</p>
    <form class="form-horizontal" method='post' action='$path' enctype="multipart/form-data">
<fieldset>


<!-- Select Multiple -->
<div class="form-group">
  <div class="col-md-auto">
    <select id="dico" name="dico[]" class="form-control" multiple="multiple">



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
    <button id="singlebutton" name="singlebutton" class="btn btn-primary">Valider</button>
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
  <input id="textinput" name="textinput" type="text" placeholder="correction" class="form-control input-md">
    
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button id="singlebutton" name="singlebutton" class="btn btn-success">Valider</button>
  </div>
</div>

</fieldset>
</form>
  </div>
</div>

</div>  
CARD;

        // Modifier ou Ajouter une Image
        $this->content .= <<<CARD
<div class="card-deck">

<div class="card border-warning mb-3" style="min-width: 18rem;">
  <div class="card-header">Modifier ou ajouter une image</div>
  <div class="card-body text-warning">
   <form class="form-horizontal">
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

        $this->content .= <<<CARD
<div class="card border-danger mb-3" style="min-width: 18rem;">
  <div class="card-header">Supprimer le mot</div>
  <div class="card-body text-danger">
    <h5 class="card-title">Cette action est définitive</h5>
    <p></p>
    <a href="#" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Supprimer</a>
  </div>
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



<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="idAudio">Choisir un fichier audio</label>
  <div class="col-md-4">
    <button id="oldAudio" name="oldAudio" class="btn btn-primary">Parcourir</button>
    <a href="#" class="btn btn-primary btn-default"><span class="glyphicon glyphicon-folder-open"></span> Parcourir</a>
  </div>
</div>

<!-- File Button --> 
<div class="form-group">
  <label class="col-md-4 control-label" for="audio">Importer un fichier audio</label>
  <div class="col-md-4">
    <input id="newAudio" name="newAudio" class="input-file" type="file">
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

</fieldset>
</form>

FORM;

    }
}