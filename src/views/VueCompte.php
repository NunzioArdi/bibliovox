<?php

namespace bibliovox\views;


class VueCompte extends Vue
{

    public function compte()
    {
        $this->title = 'Compte';
        $this->content .= "account";
    }


    /*  public function connection(){
          $this->content(<<<END
  Je suis
  20 SAUSSICE
  END
  )->content("test2");
          $this->afficher();
      }*/


    public function connection()
    {
        $this->title = 'connection';
        $path = $GLOBALS["PATH"];

        $this->content(<<<END
<form method="POST" action="$path/account/login">

<!-- File Button --> 
<div class="form-group">
  <div class="col-md-4">
    <label>First Name</label>
    <input name="firstname" type="text"><br>
    <label>Last Name</label>
    <input name="lastname" type="text"><br>
    <label>Password</label>
    <input name="password" type="text"><br>
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