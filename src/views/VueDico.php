<?php


namespace bibliovox\views;


use bibliovox\controllers\ControleurCompte;

class VueDico extends Vue
{


    public function allDico()
    {
        $this->title = "Dictionnaires";

        $this->content("    <div class=\"dico\">
            <a href=\"" . $GLOBALS["router"]->pathFor('dictionnaire_acces', $data = ['idD' => 0]) . "\">
            <img src=\"" . $GLOBALS["PATH"] . "/media/img/img/dico/alpha.png\" alt=\"alphabet\">
            <h2>Dictionnaire alphabétique</h2>
            </a>
            </div>\n");

        foreach ($this->res as $d) {
            $image = "/media/img/img/dico/";
            if ($d->imageD != null)
                $image .= $d->imageD;
            else
                $image .= "dico.png";
            $this->content("    <div class=\"dico\">
                <a href=\"" . $GLOBALS["router"]->urlFor('dictionnaire_acces', $data = ['idD' => $d->idD]) . "\">
                <img src=\"" . $GLOBALS["PATH"] . "$image\">
                <h2>$d->nomD</h2>
                </a>
                </div>\n");
        }

        if(ControleurCompte::isTeatch()){
            $this->content("    <div class=\"createNew\">
            <a id='dicoButton' href=\"" . $GLOBALS["router"]->pathFor("new_dictionnaire") . "\" class=\"btn btn-primary btn-success\"><span class=\"glyphicon \"> + Nouveau Dictionnaire</a>

            </div>");
        }

        $this->afficher();

    }

    /**
     * Vue de création d'un dictionnaire
     */
    public function creDico()
    {
        $this->title = "Nouveau dictionnaire";
        $path = $GLOBALS["router"]->urlFor("new_dictionnaire_process");

        $this->content("<h1>Créer un nouveau dictionnaire</h1>")
            ->content(<<<FORM
            <form id='new_dictionnaire' method='post' action='$path' enctype="multipart/form-data">
            <label>Nom du dictionnaire</label> <br>
            <input type='text' name='nom' placeholder='Nom' required> <br>
            <label>Description</label> <br>
            <textarea name='description' placeholder='Description' lang='fr' required></textarea><br>
            <label>Illustration du dictionnaire (facultatif)</label> <br>
            <input type='file' name='image' accept="image/*"> <br> <br>
            <input class="btn btn-primary" type="submit" value="Valider" >
            <input class="btn btn-primary" type="reset" value="Annuler">
            </form>
FORM
            )->afficher();
    }

    public function affAlph()
    {
        $this->title = "Tous les mots";

        $this->content("<h1 id='dicoText'>Tous les mots par ordre alphabétique</h1>");


        $none = true;

        foreach ($this->res as $m) {
            $none = false;
            $this->content("<h2 id='dicoText'><a href='" . $GLOBALS["router"]->urlFor("mot", ["idD" => 0, "idM" => $m->idM]) . "'>$m->texte</a></h2>\n");
        }
        if ($none)
            $this->content("<p>Aucun mot dans Bibli O'vox.</p>");

        if (ControleurCompte::isTeatch()) {
            $this->content("<a href=\"" . $GLOBALS["router"]->pathFor("new_mot", $data = ['idD' => 0]) . "\" class=\"btn btn-block btn-success\">+ Créer un mot</a>");
        }
        $this->afficher();
    }

    public function theme()
    {
        $this->title = $this->res[1];
        $img = $GLOBALS["PATH"] . "/media/img/img/dico/" . $this->res[3];
        $titre = "<h1 id='dicoText'>$this->title</h1>";

        $this->content(<<<MOTS
<div class="card text-center" style="min-width: 18rem;">
  <img class="card-img-top" src="$img" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title">$titre</h5>
  </div>
  <ul class="list-group list-group-flush">

MOTS
        );

        $none = true;
        foreach ($this->res[2] as $m) {
            $none = false;
            $this->content("<li class='list-group-item'><h2 id='dicoText'><a href='" . $GLOBALS["router"]->urlFor('mot', ['idD' => $this->res[0], 'idM' => $m->idM]) . "'>$m->texte</a></h2></li>\n");
        }

        if ($none)
            $this->content("<li class='list-group-item'><p class='btn btn-danger'>Aucun mot dans ce dictionnaire.</p></li>");

        $this->content("</ul></div>");
        //TODO Test si admin
        if (true) {
            $this->outilsAdmin();
        }
        $this->afficher();

    }

    private function outilsAdmin()
    {
        $delete = $GLOBALS["router"]->pathFor("delete_dico", $data = ['idD' => $this->res[0]]);
        $newMot = $GLOBALS["router"]->pathFor("new_mot", $data = ['idD' => $this->res[0]]);
        $image = $GLOBALS["router"]->pathFor("update_dico_image", $data = ['idD' => $this->res[0]]);
        $dicoName = $this->res[1];
        $idD = $this->res[0];
        $this->content(<<<CONT
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
CONT
        );

    }


}