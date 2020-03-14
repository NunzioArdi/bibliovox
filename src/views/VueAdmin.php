<?php


namespace bibliovox\views;


use bibliovox\views\Vue;

class VueAdmin extends Vue
{

    /**
     * @inheritDoc
     */
    public function views(string $view)
    {
        switch ($view){
            case 'createUser':
                $this->creerCompte();
                break;
            default:
                break;
        }
        $this->afficher();
    }

    private function creerCompte()
    {
$this->content.=<<<END
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
END;


    }
}