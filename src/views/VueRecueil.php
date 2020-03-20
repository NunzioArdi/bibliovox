<?php


namespace bibliovox\views;


use bibliovox\controllers\ControleurAudio;
use bibliovox\controllers\ControleurAudioRecueil;

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

        $date = explode('-', $rec->dateR);
        $an = $date[0];
        $mois = $date[1];
        $jour = explode(" ", $date[2])[0];

        $this->content .= "<h1>Recueil: <i>$rec->nomR</i></h1>";
        $this->content .= "<div class='date'>Créé le: $jour / $mois / $an </div>";
        $this->content .= "<textarea readonly class='cite'>$rec->descriptionR</textarea>";

        $this->content .= ControleurAudio::record();


        //TODO Ajouter l'id de l'utilisateur connecté
        $aPerso = ControleurAudioRecueil::audioPerso($rec->idR, 1);
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
AUDIOS;
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
        $this->content .= "<div class=\"createNew\"><a href=\" " . $GLOBALS["router"]->urlFor("new_recueil") . "  \">Nouveau Recueil</a>";
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

}