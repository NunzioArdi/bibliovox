<?php


namespace bibliovox\views;

class VueAdmin extends Vue
{

    public function __construct($res = null)
    {
        parent::__construct($res);

        $this->nav += [
            "admin" => "<li><a href=\"" . $GLOBALS["router"]->urlFor('admin') . "\"><img class =\"icn\" src=\" " . $GLOBALS["PATH"] . "/media/img/icn/compte.png\" alt=\"Compte\">Admin</a></li>",
        ];
    }


    public function creerCompte()
    {
        $this->content(<<<END
<form method="POST" action="/admin/pannel/createUser">

<!-- File Button --> 
<div class="form-group">
  <div class="col-md-4">
    <label>First Name</label>
    <input name="firstname" type="text"><br>
    <label>Last Name</label>
    <input name="lastname" type="text"><br>
    <label>Password</label>
    <input name="password" type="text"><br>
    <label>Grade</label>
    <input name="grade" type="text" value="1"><br>
    <label>Email</label>
    <input name="email" type="text">
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