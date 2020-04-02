<?php

namespace bibliovox\views;


use bibliovox\controllers\ControleurCompte;
use bibliovox\models\Classe;
use bibliovox\models\Eleve;
use bibliovox\models\Utilisateur;
use http\Client;

class VueCompte extends Vue
{

    public function compte()
    {
        $path = $GLOBALS["PATH"];

        $info = Utilisateur::select('nom', 'prenom', 'mail')->where('idU', '=', $_SESSION['idU'])->get();
        $nom = $info[0]->nom;
        $prenom = $info[0]->prenom;
        $email = $info[0]->mail;
        $link = "";
        if(ControleurCompte::isTeatch()){
            $link.="<a href='$path/account/classes'>Gestion des classes</a><br>";
        }
        if(ControleurCompte::isAdmin()){
            $link.="<a href='$path/account/createUser'>Création d'un utilisateur</a>";
        }

        $this->title('Compte')
            ->content("Nom prénom: $nom $prenom<br>\nEmail: $email")
            ->content("<br><a class='btn btn-danger' href='$path/account/disconnect'>Se déconnecter</a>")
            ->content("<br><br>" . $link)
            ->afficher();
    }


    public function connection(int $p)
    {
        $path = $GLOBALS["PATH"];

        $bouton = "";

        if ($p == 1) {
            $this->page1();
        } elseif ($p == 2) {

            // prof/parent
            if ($this->res == 2)
                $bouton = "xeleve";
            // prof avant connection eleve
            if ($this->res == 1)
                $bouton = "eleve";
            $this->page2($bouton);

        } elseif ($p == 3) {
            $this->page3();
        }

        $this->afficher();
    }

    private function page2($bouton)
    {
        $path = $GLOBALS["PATH"];

        $this->content(<<<END
<form method="POST" action="$path/account/login">
<h2>Se connecter</h2>
<!-- File Button --> 
<div class="form-group">
  <div class="col-md-4">
    <label>Adresse email</label>
    <input name="email" type="text"><br>
    <label>Mot de passe </label>
    <input name="password" type="password"><br>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button type="submit" id="singlebutton" name="$bouton" class="btn btn-warning">Valider</button>
  </div>
</div>

</form>
END
        );
    }

    private function page1()
    {
        $path = $GLOBALS["PATH"];

        $this->content(<<<END
<form method="POST" action="$path/account/login">
<fieldset>

<!-- Form Name -->
<legend>Choix du compte</legend>

<!-- Multiple Radios -->
<div class="form-group">
  <div class="col-md-4">
  <div class="radio">
      <input type="radio" name="radios" id="radios-0" value="1" checked="checked">
      Élève - demande de l'aide à ton maître ou à ta maîtresse !
	</div>
  <div class="radio">
      <input type="radio" name="radios" id="radios-1" value="2">
      Parent ou Professeur
	</div>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button type="submit" id="singlebutton" name="typeAccount" class="btn btn-warning">Valider</button>
  </div>
</div>

</fieldset>
</form>
END
        );
    }

    private function page3()
    {
        $path = $GLOBALS["PATH"];

        $this->title("Connection élève")
            ->content("<h2>Sélectionne ton compte</h2>\n");

        $this->content(<<<END
<form method="POST" action="$path/account/login">
<fieldset>

<legend>Choix du compte</legend>

<div class="form-group">
  <div class="col-md-4">
END
        );

        foreach ($this->res as $e) {
            $avatar = ($e['avatar'] == null) ? "media/img/pp/default.png" : $e['avatar'];
            $nom = $e['nom'];
            $prenom = $e['prenom'];
            $id = $e["idU"];

            $this->content(<<<END
    <div class="radio">
      <input type="radio" name="radios" id="radios-0" value="$id">
        <img src="$path/$avatar" width="50px">
        $nom $prenom
	</div>
END
);
        }

        $this->content(<<<END
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button type="submit" id="singlebutton" name="student" class="btn btn-warning">Valider</button>
  </div>
</div>

</fieldset>
</form>
END
);
    }


    public function classe()
    {
        $path = $GLOBALS["PATH"];


        $this->content($this->listeDesClasse())
            ->content($this->ajouterEleveClasse());

        if(ControleurCompte::isAdmin()){
            $this->content($this->creerClasse());
        }

        $this->title("Gestion des classes")
            ->afficher();
    }

    private function listeDesClasse(){
        $listClasse = Classe::all();

        $this->content("<div>\n")
            ->content("<p>Liste des classes avec leurs eleves</p>\n");
        $this->content("<ul>\n");

        foreach ($listClasse as $classe){
            $prof = Utilisateur::select("nom", "prenom")->where('idU', '=', $classe->idUEnseignant)->get();

            $this->content("    <li>")
                ->content($classe->nom . ", " . $prof[0]->nom . " " . $prof[0]->prenom)
                ->content("</li>\n")
                ->content("    <ul>\n");

            foreach (Eleve::where('idC', "=", $classe->idC)->get() as $e){
                $eleveClasse = Utilisateur::where('idU', '=', $e->idU)->get();

                $this->content("        <li>" .$eleveClasse[0]->nom . " " . $eleveClasse[0]->prenom . "</li>\n");
            }
            $this->content("    </ul>\n");
        }
        $this->content("</ul>\n")
            ->content("</div><br>");
    }

    private function ajouterEleveClasse(){

        $path = $GLOBALS["PATH"];

        $nombreEleveSansClasse = Utilisateur::where('idG',  '=',  1 )->whereNotIn('idU',  Eleve::pluck('idU') )->count();
        $eleveSansClasse = Utilisateur::where('idG',  '=',  1 )->whereNotIn('idU',  Eleve::pluck('idU') )->get();

        if($nombreEleveSansClasse == 0){
            $this->content(<<<END
<form method="POST" action="$path/account/classes/add">
<fieldset>

<!-- Form Name -->
<legend>Ajouter un élève à une classe</legend>
<label>Tout les élèves ont une classe</label>
</fieldset></form>
<br>
END
            );
            return;
        }

        $listClasse = Classe::all();

        $this->content(<<<END
<form method="POST" action="$path/account/classes/add">
<fieldset>

<!-- Form Name -->
<legend>Ajouter un élève à une classe</legend>

<!-- Multiple Radios -->
<div class="form-group">
  <div class="col-md-4">
END
        );

        $this->content("<label>Les classes</label>");

        foreach ($listClasse as $cl){
            $id = $cl->idC;
            $nom = $cl->nom;
            $this->content(<<<END
<div class="radio">
      <input type="radio" name="classe" id="radios-0" value="$id" required="">
      $nom
</div>
END
            );
        };

        $this->content("<label>Elèves sans classe</label>");


        foreach ($eleveSansClasse as $ela){

            $id = $ela->idU;
            $nom = $ela->nom;
            $prenom = $ela->prenom;
            $this->content(<<<END
<div class="radio">
      <input type="radio" name="eleve" id="radios-0" value="$id" required="">
      $nom $prenom
</div>
END
            );
        };


        $this->content(<<<END
</div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button type="submit" id="singlebutton" name="typeAccount" class="btn btn-warning">Ajouter à la classe</button>
  </div>
</div>

</fieldset>
</form>
<br>
END
        );
    }

    private function creerClasse()
    {
        $path = $GLOBALS["PATH"];

        $list ="";
        foreach (Utilisateur::where('idG', '=', 2)->whereNotIn('idU',  Classe::pluck('idUEnseignant'))->get() as $e){

            $id = $e->idU;
            $nom = $e->nom;
            $prenom = $e->prenom;

            $list.= <<<END
<div class="radio">
      <input type="radio" name="prof" id="radios-0" value="$id" required="">
      $nom $prenom
</div>
END;
        }

        $date = date("Y");
        $this->content(<<<END
<form method="POST" action="$path/account/classes/create">

<legend>Créer une classe</legend>

<!-- File Button --> 
<div class="form-group">
  <div class="col-md-4">
    <label>Nom de la classe</label>
    <input name="className" type="text" required="">
    <label>Année de la classe</label>
    <input name="year" type="text" value="$date" required=""><br>
    <label>Professeur dans classe</label>
    $list
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button type="submit" id="singlebutton" name="bouton" class="btn btn-warning">Valider</button>
  </div>
</div>

</form>
END
        );
    }
}