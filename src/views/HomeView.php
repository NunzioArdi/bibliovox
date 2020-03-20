<?php


namespace bibliovox\views;


class HomeView extends Vue
{

    /**
     * @inheritDoc
     */
    public function views(string $view)
    {
        switch ($view) {
            case 'index':
                $this->index();
                break;
            case 'about':
                $this->about();
                break;
            default:
                break;
        }
        $this->afficher();
    }

    private function index()
    {
        $this->title = "Accueil";

        $this->content .= <<<HTML
            <p class="text">Bibli O’vox a été imaginé par <strong>Christophe Buczkowski</strong>, <strong>Sophie Deleys</strong> et <strong>Marie Lequèvre</strong> 
                dans le cadre d’un master dans le domaine des sciences de l’éducation. Notre production s’appuiera sur les propositions résultant 
                de ce travail. </p>
            <p class="text">Bibli O’vox est un outil qui a pour but de favoriser le développement du langage oral dans un cadre scolaire. </p>
            <p class="text">Ce système a une visée éducative, cela signifie que les principaux utilisateurs de ce système seront des 
                élèves de maternelle et primaire dans un premier temps. Il faudra donc s’assurer de la simplicité d’utilisation.
                Le système doit combler un manque constaté par plusieurs enseignants, celui de la communication orale
                qu’il soit en classe ou à la maison. L’outil concernera tout élève rencontrant des difficultés pour
                s’exprimer en langue française dans un premier temps. Cependant il visera plus particulièrement les élèves
                allophones (le français n’étant pas leur langue maternelle) ou étrangers (ne communiquant pas forcément
                en français avec leur entourage). Un outil permettant d’exploiter le français à l’oral dans leur foyer
                pourrait alors se révéler profitable. En effet, les parents pourraient bénéficier de ce nouveau dispositif,
                puisque la vie orale en classe, ne peut être partagée avec un cahier du jour classique écrit. </p>
            <p class="text">Bibli O’vox est donc un cahier de vie oral permettant un suivi des activités des enfants, et ce, jour
                après jour, que cela soit par les enfants pour constater leur progrès dans l’apprentissage de la langue
                française ou par les parents afin d’avoir un aperçu de la vie que mène leur enfant au sein de la classe
                durant la journée et qu’ils puissent eux aussi s’investir dans les devoirs de leur enfant. Cela renforcera
                le lien entre l’école et le foyer en partageant ce qui a été étudié en classe.</p>

HTML;

    }

    private function about()
    {
        $this->title = "À propos";

        $this->content .= <<<END
        <div>Icons made by <a href='https://www.flaticon.com/authors/eucalyp' title='Eucalyp'>Eucalyp</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>
        <div>Icons made by <a href='https://www.flaticon.com/authors/ddara' title='dDara'>dDara</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>
        <div>Icons made by <a href='https://www.flaticon.com/authors/freepik' title='Freepik'>Freepik</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>
        <div>Icons made by <a href='https://www.flaticon.com/authors/itim2101' title='itim2101'>itim2101</a> from <a href='https://www.flaticon.com/' title='Flaticon'>www.flaticon.com</a></div>
        <div>Icons made by <a href="https://www.flaticon.com/authors/flat-icons" title="Flat Icons">Flat Icons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>

END;

    }
}