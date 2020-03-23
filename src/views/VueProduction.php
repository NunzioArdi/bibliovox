<?php


namespace bibliovox\views;

use bibliovox\controllers\ControleurAudio;
use bibliovox\controllers\ControleurCompte;
use bibliovox\controllers\ControleurProduction;
use bibliovox\controllers\ControleurUtilisateur;
use bibliovox\models\Production;
use Exception;
use Throwable;

/**
 * Afficheur des productions individuel des utilisateurs.
 * @package bibliovox\views
 */
class VueProduction extends Vue
{

    /**
     * @inheritDoc
     */
    public function views(string $view)
    {
        switch ($view) {
            case 'all':
                $this->all();
                break;
            case 'prod':
                $this->prod();
                break;
            case 'editProd':
                $this->editProd();
                break;
            case 'create':
                $this->create();
                break;
            default:
                break;
        }
        $this->afficher();
    }

    /**
     * Affiche toutes les production d'un utilisateur
     */
    private function all()
    {
        $this->title = 'Productions';
        $path = $GLOBALS["router"]->urlFor("new_production");
        $histo = $this->printHisto(ControleurCompte::isTeatch());
        if (ControleurCompte::isTeatch()) {
            $perso = $this->printHisto(false);

            $this->content .= <<<NAV
<h1>Retrouvez ici toutes les productions de vos élèves</h1>
<ul class="nav nav-pills nav-justified mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-histo-tab" data-toggle="pill" href="#pills-histo" role="tab" aria-controls="pills-histo" aria-selected="true">Dernières productions</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Productions de vos élèves</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-perso-tab" data-toggle="pill" href="#pills-perso" role="tab" aria-controls="pills-perso" aria-selected="false">Vos productions personnelles</a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-histo" role="tabpanel" aria-labelledby="pills-histo-tab">
  <div class="card-columns">
    <div class="card text-white bg-info mb-3" style="min-width: 20rem;">
      <div class="card-header">Information</div>
      <div class="card-body">
        <p class="card-text">Les productions sont triées selon leur ordre d'ajout.</p>
        <p class="card-text">Vous avez la possibilité d'ajouter des commentaires ou de supprimer les productions.</p>
      </div>
  </div>
    $histo
  </div>
  </div>
  
  
  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
  
  
  <div class="tab-pane fade" id="pills-perso" role="tabpanel" aria-labelledby="pills-perso-tab">
  <div class="card-columns">
  <div class="card text-white bg-success mb-3" style="min-width: 20rem;">
      <div class="card-header">Nouvelle production</div>
      <div class="card-body">
        <p class="card-text">Enregistrez une nouvelle production!</p>
        <a class="btn btn-light" href=" $path ">Créer une production</a>
      </div>
  </div>
  $perso
</div>
</div>
</div>
NAV;


        } else {

            $this->content .= <<<AUDS
<h1>Retrouves ici toutes tes productions !</h1>
<div class="card-columns">
  <div class="card text-white bg-success mb-3" style="min-width: 20rem;">
      <div class="card-header">Nouvelle production</div>
      <div class="card-body">
        <p class="card-text">Enregistre une nouvelle production!</p>
        <p class="card-text">Tu auras besoin de ta maîtresse ou de ton maître.</p>
        <a class="btn btn-light" href=" $path ">Créer une production</a>
      </div>
  </div>
  $histo
</div>

AUDS;

        }
    }

    private function prod()
    {
        $prod = $this->res;
        $this->title = $prod->nomP;
        $audios = $prod->audio();

        $this->content .= "<h1>Production: <i>$prod->nomP</i></h1>";

        $printAudPerso = "";
        $printAudEx = "";
        $idu = ControleurCompte::getIdUser();
        foreach ($audios as $audio) {
            try {
                throw_if($audio == null, new Exception("Audio introuvable"));
                if ($audio->idU = $idu) {
                    $printAudPerso .= "<audio controls>";
                    $printAudPerso .= "<source src=\"" . $GLOBALS["PATH"] . "/" . $audio->chemin . "\" type=\"audio/mp3\">";
                    $printAudPerso .= "</audio></div>";
                } else {
                    $printAudEx .= "<audio controls>";
                    $printAudEx .= "<source src=\"" . $GLOBALS["PATH"] . "/" . $audio->chemin . "\" type=\"audio/mp3\">";
                    $printAudEx .= "</audio></div>";
                }
            } catch (Throwable $e) {
                $printAudPerso .= "<div class='erreur'>Audio introuvable</div>";
            }
        }

        $this->content .= <<<AUDS
<div class='card border-info mb-3 '>
  <div class='card-header'>Enregistrements</div>
  <div class='card-body text-info'>
    <div class="card-deck">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Tes enregistrements</h5>
          $printAudPerso
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Exemples</h5>
          $printAudEx
        </div>
      </div>
    </div>
  </div>
</div>
AUDS;


        //L'idU est stocké en get temporairement (jusqu'à la gestion du compte)
        $this->content .= "<a class=\"boutton\" href=\" " . $GLOBALS["router"]->urlFor("edit_production", $data = ['idP' => $prod->idP]) . " \">Editer</a>";
    }

    private function editProd()
    {
        $prod = $this->res[0];
        $url = $this->res[1];
        $this->title = "Édition";

        $this->content .= <<<END
    <form id="edit_production" method="post" action="$url" enctype="multipart/form-data">
        <label>Titre de la production</label>
        <input type="text" name="nom" placeholder="Titre" value="$prod->nomP">
        <label>Audio</label>
        <input type="file" name="audio" accept="audio/*">
        <label>Commentaire</label>
END;
        if ($prod->commentaire != null)
            $this->content .= "<textArea name='comm'>$prod->commentaire</textArea>";
        else
            $this->content .= <<<END
<textArea name='comm'></textArea>
<br>
        <input class='bouton' type="reset" value="Annuler modifications">
        <input class="bouton" type="submit" value="Valider modifications">
    </form>
END;


    }

    private function create()
    {
        $this->title = 'Nouvelle production';
        $this->content .= "<h1>Créer une nouvelle production</h1>";

        $id = ControleurCompte::getIdUser();
        $path = $GLOBALS["router"]->urlFor("new_production_process") . "?idU=$id";

        $this->content .= <<<FORM
<form id='new_production' method='post' action='$path' enctype="multipart/form-data">
<label>Titre de la production</label>
<input type='text' name='nom' placeholder='Titre' required>
<label>Audio</label>
<input type='file' name='newAudio' accept="audio/mp3" required>
<input class='bouton' type="reset" value="Annuler">
<input class="bouton" type="submit" value="Valider">
</form>
FORM;
    }

    private function printHisto(bool $all): string
    {
        $printAuds = "";

        if ($all)
            $prod = Production::getAll();
        else
            $prod = Production::allCheck(ControleurCompte::getIdUser());

        if ($prod != null) {
            $delete = $path = $GLOBALS["router"]->urlFor("delete_production");
            foreach ($prod as $r) {
                $nom = $r->nomP;
                $audio = ControleurAudio::getPathById($r->idAudio);

                if (ControleurCompte::isTeatch())
                    $nom .= " de <b>" . ControleurUtilisateur::getNameById($audio->idU) ."</b>";
                $chemin = $GLOBALS["PATH"] . "/" . $audio->chemin;

                $date = explode('-', $audio->dateCreation);
                $an = $date[0];
                $mois = $date[1];
                $jour = explode(" ", $date[2])[0];
                $crea = "Créé le: $jour / $mois / $an";

                $comm = $audio->commentaire;
                if ($comm == "")
                    $comm = "Aucun commentaire pour le moment.";

                $edit = "";
                if (ControleurCompte::isTeatch()) {
                    $edit = "<div class='card'>
  <div class='card-body'>
    <h5 class='card-title'>Édition</h5>
    <input id='commentaire' name='commentaire' type='text' placeholder='Commentaire' class='form-control input-md'>
    <a href='#' class='card-link'>Ajouter le commentaire</a>
    <a href='$delete?idP=$r->idP' class='card-link text-danger'>Supprimer la production</a>
  </div>
</div>";
                }

                $printAuds .= <<<AUDS

<div class="card border-info mb-3" style="min-width: 20rem;">
  <div class="card-header">$nom</div>
  <div class="card-body text-info">
    <h5 class="card-title">Ton enregistrement</h5>
    <p><audio controls><source src="$chemin" type="audio/mp3"></audio controls></p>
    <h5 class="card-title">Commentaire</h5>
    <p class="card-text">$comm</p>
    $edit
  </div>
  <div class="card-footer">$crea</div>
</div>
AUDS;
            }
        } else {
            $printAuds .= '


<div class="card text-white bg-warning mb-3" style="min-width: 20rem;">
  <div class="card-header">Oups !</div>
  <div class="card-body">
    <h5 class="card-title">Tu n\'as encore aucunne production.</h5>
    <p class="card-text">Tu peux créer une nouvelle production. Demande de l\'aide à ta maîtresse ou à ton maître.</p>
  </div>
</div>';
        }
        return $printAuds;
    }
}