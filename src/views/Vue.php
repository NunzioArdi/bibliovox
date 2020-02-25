<?php

namespace bibliovox\views;

abstract class Vue
{

    /**
     * @var mixed résultat du controlleur
     */
    protected $res;

    /**
     * @var string $title titre de la page
     */
    protected $title;

    /**
     * @var array $nav contenu de la barre de navigation
     */
    protected $nav;

    /**
     * @var string $content contenu de la page
     */
    protected $content;


    /**
     * view constructor.
     * @param mixed $res résultat du controlleur pour l'affichage
     */
    public function __construct($res = null)
    {
        $this->res = $res;

        $this->nav = [
            "logo" => "<li><a href=\"" . $GLOBALS["router"]->urlFor('home') . "\"><img class =\"icn\" src=\"" . $GLOBALS["PATH"] . "/media/img/icn/logo.png\" alt=\"Logo\"></a></li>",
            "Accueil" => "<li><a href=\"" . $GLOBALS["router"]->urlFor('home') . "\"><img class =\"icn\" src=\" " . $GLOBALS["PATH"] . "/media/img/icn/home.png\" alt=\"Accueil\">Accueil</a></li>",
            "dictionnaires" => "<li><a href=\"" . $GLOBALS["router"]->urlFor('dictionnaires') . "\"><img class =\"icn\" src=\" " . $GLOBALS["PATH"] . "/media/img/icn/dico.png\" alt=\"Dictionnaires\">Dictionnaires</a></li>",
            "recueils" => "<li><a href=\"" . $GLOBALS["router"]->urlFor('recueils') . "\"><img class =\"icn\" src=\" " . $GLOBALS["PATH"] . "/media/img/icn/recueil.png\" alt=\"Recueils\">Recueils</a></li>",
            "productions" => "<li><a href=\"" . $GLOBALS["router"]->urlFor('productions') . "\"><img class =\"icn\" src=\" " . $GLOBALS["PATH"] . "/media/img/icn/production.png\" alt=\"Productions\">Productions</a></li>",
            "compte" => "<li><a href=\"" . $GLOBALS["router"]->urlFor('compte') . "\"><img class =\"icn\" src=\" " . $GLOBALS["PATH"] . "/media/img/icn/compte.png\" alt=\"Compte\">Compte</a></li>",

        ];
    }

    /**
     * Sélectionne la vue à afficher. Dois appeler la fonction afficher à la fin.
     * @param string $view le nom de la vue
     * @return void
     */
    public abstract function views(string $view);

    /**
     * Affiche le contenue de la page
     * @return void
     */
    protected function afficher()
    {
        echo <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>$this->title</title>
    <link rel="stylesheet" href="{$GLOBALS["PATH"]}/web/css/bootstrap.css">
    <link rel="icon" href="{$GLOBALS["PATH"]}/media/img/icn/logo.png">
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
    <script src="{$GLOBALS["PATH"]}/web/js/jquery-1.10.2.js"></script>
    <script src="{$GLOBALS["PATH"]}/web/js/bootstrap.js"></script>



</head>
<body>
    <nav>
        <ul>
            {$this->showNav()}
        </ul>
    </nav>
$this->content
</body>
</html>
HTML;
    }

    /**
     * Rendu de la barre de navigation
     * @return string le nav
     */
    private function showNav(): string
    {
        $res = "";
        foreach ($this->nav as $item => $value) {
            $res .= $value . "\n            ";
        }
        return $res;
    }
}