<?php


namespace bibliovox\views;

class VueAdmin extends Vue
{

    public function creerCompte()
    {
        $path = $GLOBALS["PATH"];

        $this->content(<<<END
<form method="POST" action="$path/account/createUser">

<!-- File Button --> 
<div class="form-group">
  <div class="col-md-4">
    <label>Prénom</label>
    <input name="firstname" type="text"><br>
    <label>Nom</label>
    <input name="lastname" type="text"><br>
    <label>Mot de passe</label>
    <input name="password" type="text"><br>
    <label>Grade</label>
    <input name="grade" type="text" value="1"><br>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button id="singlebutton" name="singlebutton" class="btn btn-warning">Valider</button>
  </div>
</div>

</form>
END
        )->afficher();
    }
}