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
        $this->content .= <<<FORM
<form class="form-horizontal" method='post' action='$path' enctype="multipart/form-data">
<fieldset>

<!-- Form Name -->
<legend>Mot dans les dictionnaires suivants :</legend>

<!-- Select Multiple -->
<div class="form-group">
  <label class="col-md-4 control-label" for="dico"></label>
  <div class="col-md-4">
    <select id="dico" name="dico[]" class="form-control" multiple="multiple">
FORM;

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
        $this->content .= "</select></div></div> <button type = \"submit\" id=\"valid\" name=\"valid\" class=\"btn btn-primary btn-primary\"\"></span> Enregistrer les modifications</button>
 </fieldset></form>";


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