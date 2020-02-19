<?php

namespace bibliovox\views;


class VueCompte extends Vue
{

    /**
     * @inheritDoc
     */
    public function views(string $view)
    {
        switch ($view) {
            case 'compte':
                $this->compte();
                break;
            default:
                break;
        }
        $this->afficher();
    }

    private function compte()
    {
        $this->title = 'Compte';
        $this->content .= "account";
    }
}