<?php


namespace bibliovox\views;


class VueDico extends Vue
{

    /**
     * @inheritDoc
     */
    public function views(string $view)
    {
        switch ($view) {
            case "all":
                $this->allDico();
                break;
            case "createDico":
                $this->creDico();
                break;
            case "alphabet":
                $this->affAlph();
                break;
            case "theme":
                $this->theme();
                break;

            default:
                break;
        }
        $this->afficher();
    }

    private function allDico()
    {
        $this->title = "Dictionnaires";

        $this->content .= "    <div class=\"dico\">
            <a href=\"" . $GLOBALS["router"]->pathFor('dictionnaire_acces', $data = ['idD' => 0]) . "\">
            <img src=\"" . $GLOBALS["PATH"] . "/media/img/img/dico/alpha.png\" alt=\"alphabet\">
            <h2>Dictionnaire alphabétique</h2>
            </a>
            </div>\n";

        foreach ($this->res as $d) {
            $image = "/media/img/img/dico/";
            if ($d->imageD != null)
                $image .= $d->imageD;
            else
                $image .= "dico.png";
            $this->content .= "    <div class=\"dico\">
                <a href=\"" . $GLOBALS["router"]->urlFor('dictionnaire_acces', $data = ['idD' => $d->idD]) . "\">
                <img src=\"" . $GLOBALS["PATH"] . "$image\">
                <h2>$d->nomD</h2>
                </a>
                </div>\n";
        }

        $this->content .= "    <div class=\"createNew\">
            <a href=\"" . $GLOBALS["router"]->pathFor("new_dictionnaire") . "\" class=\"btn btn-primary btn-success\"><span class=\"glyphicon glyphicon-plus\">Nouveau Dico</a>
            </div>";
    }

    /**
     * Vue de création d'un dictionnaire
     */
    private function creDico()
    {
        $this->title = "Nouveau dictionnaire";

        $this->content .= "<h1>Créer un nouveau dictionnaire</h1>";
        $path = $GLOBALS["router"]->urlFor("new_dictionnaire_process");
        $this->content .= <<<FORM
            <form id='new_dictionnaire' method='post' action='$path' enctype="multipart/form-data">
            <label>Nom du dictionnaire</label> <br>
            <input type='text' name='nom' placeholder='Nom' required> <br>
            <label>Description</label> <br>
            <textarea name='description' placeholder='Description' lang='fr' required></textarea><br>
            <label>Illustration du dictionnaire (facultatif)</label> <br>
            <input type='file' name='image' accept="image/*"> <br> <br>
            <input class="bouton" type="submit" value="Valider">
            <input class='bouton' type="reset" value="Annuler">
            </form>
FORM;
    }

    private function affAlph()
    {
        $this->title = "Tous les mots";

        $this->content .= "<h1>Tous les mots par ordre alphabétique</h1>";

        //On veut l'image?
        //echo "<img src='".PATH."/media/img/img/dico/alpha.png'>";

        foreach ($this->res as $m) {
            $this->content .= "<h2><a href='" . $GLOBALS["router"]->urlFor("mot", ["idD" => 0, "idM" => $m->idM]) . "'>$m->texte</a></h2>\n";
        }
        $this->content .= "<a href=\"" . $GLOBALS["router"]->pathFor("new_mot", $data = ['idD' => 0]) . "\" class=\"btn btn-primary btn-success\"><span class=\"glyphicon glyphicon-plus\"></span> Nouveau mot</a>";

    }

    private function theme()
    {
        $this->title = $this->res[1];
        $this->content .= "<h1>$this->title</h1>";

        foreach ($this->res[2] as $m) {
            $this->content .= "<h2><a href='" . $GLOBALS["router"]->urlFor('mot', ['idD' => $this->res[0], 'idM' => $m->idM]) . "'>$m->texte</a></h2>\n";
        }

        //TODO Test si admin
        if (true) {
            $this->outilsAdmin();
        }

    }

    private function outilsAdmin()
    {
        $delete = $GLOBALS["router"]->pathFor("delete_dico", $data = ['idD' => $this->res[0]]);
        $newMot = $GLOBALS["router"]->pathFor("new_mot", $data = ['idD' => $this->res[0]]);
        $image = $GLOBALS["router"]->pathFor("update_dico_image", $data = ['idD' => $this->res[0]]);
        $dicoName = $this->res[1];
        $idD = $this->res[0];
        $this->content .= <<<CONT
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
  <div class="card-deck">
  

    <!-- Créer Mot -->
  <div class="card border-primary mb-3" style="min-width: 18rem;">
  <div class="card-header">Créer un mot</div>
  <div class="card-body text-dark">
    <p class="card-text">Créez un mot qui sera associé à ce dictionnaire et/ou à d'autres.</p>
    <p class="card-text">Vous aurez la possibilité d'y associer une image, des audio existants ou d'importer un nouveau fichier.</p>
    <a href="$newMot" class="btn btn-primary">Créer un Mot</a>
  </div>
</div>

    <!-- Changer/ajouter image -->
<div class="card border-warning mb-3" style="min-width: 18rem;">
  <div class="card-header">Modifier ou ajouter une image</div>
  <div class="card-body text-warning">
   <form class="form-horizontal" method="post" action='$image' enctype="multipart/form-data">
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
</div>


  <div class="card-deck">

    <!-- Modifier nom Dico -->
      <div class="card border-success mb-3" style="min-width: 18rem;">
  <div class="card-header">Éditer le nom</div>
  <div class="card-body text-dark">
    <p class="card-text">Éditez ou corrigez le nom de ce dictionnaire.</p>
    
    <form class="form-horizontal">
<fieldset>

<!-- Text input-->
<div class="form-group">
  <div class="col-md-4">
  <input id="dicoName" name="dicoName" type="text" placeholder="nom du dictionnaire" value="$dicoName" class="form-control input-md" onkeypress="refuserToucheEntree(event);">
  <input id="idD" value="$idD" hidden/>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <div class="col-md-4">
    <a href="#" id="bttnName" class="btn btn-success">Valider</a>
  </div>
</div>

</fieldset>
</form>

  </div>
</div>

<!--- Suppression du Dictionnaire -->
<div class="card border-danger mb-3" style="min-width: 18rem;">
  <div class="card-header">Supprimer le dictionnaire</div>
  <div class="card-body text-danger">
    <h5 class="card-title">Cette action est définitive</h5>
    <p></p>
    <a href="$delete" id="bttnDltWrd" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Supprimer</a>
  </div>
</div>




</div>
</div>
</div>

<input id="path" value="{$GLOBALS["PATH"]}" hidden>
<script src="{$GLOBALS["PATH"]}/web/js/bibliovox.js"></script>
CONT;

    }


}