<?php

namespace bibliovox\views;


use bibliovox\models\Utilisateur;

class VueCompte extends Vue
{

    public function compte()
    {

        $info = Utilisateur::select('nom', 'prenom', 'mail')->where('idU', '=', $_SESSION['idU'])->get();
        $nom = $info[0]->nom;
        $prenom = $info[0]->prenom;
        $email = $info[0]->mail;

        $this->title('Compte')
            ->content("$nom $prenom<br>\n$email")
            ->content("<br>\n<a href='account/disconnect'>Déconnection</a>")
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
        } else {
            $this->page4();
        }

        $this->afficher();
    }

    private function page2($bouton)
    {
        $path = $GLOBALS["PATH"];

        $this->content(<<<END
<form method="POST" action="$path/account/login">

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
      Elève
	</div>
  <div class="radio">
      <input type="radio" name="radios" id="radios-1" value="2">
      Parent/Professeur
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
            ->content("<h2>Séléctionne ton compte</h2>\n");

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

    private function page4()
    {
    }
}