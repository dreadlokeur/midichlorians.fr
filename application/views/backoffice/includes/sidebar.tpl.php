<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <li class="mt">
                <a href="<?php echo $this->getUrl('backoffice'); ?>" class="ajax-switcher">
                    <i class="fa fa-dashboard"></i>
                    <span>Accueil</span>
                </a>
            </li>
            <li>
                <a href="">
                    <i class="fa fa-dashboard"></i>
                    <span>Analytics</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="">
                    <i class="fa fa-desktop"></i>
                    <span>Préférences</span>
                </a>
                <ul class="sub">
                    <li><a href="<?php echo $this->getUrl('backlinkView'); ?>" class="ajax-switcher">Partenaires</a></li>
                    <li><a href="">Prestations</a></li>
                    <li><a href="">Compétences</a></li>
                    <li><a href="">Coordonnées</a></li>
                    <li><a href="">Cv</a></li>
                    <li><a href="">Réseaux sociaux</a></li>
                    <li><a href="">Languages</a></li>
                    <li><a href="">Paramètres</a></li>
                </ul>
            </li>
            <li>
                <a href="<?php echo $this->getUrl('edit', array('portfolio')); ?>">
                    <i class="fa fa-dashboard"></i>
                    <span>Portfolio</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $this->getUrl('page'); ?>" class="ajax-switcher">
                    <i class="fa fa-dashboard"></i>
                    <span>Pages</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="">
                    <i class="fa fa-desktop"></i>
                    <span>Crm</span>
                </a>
                <ul class="sub">
                    <li><a  href="">Clients</a></li>
                    <li><a  href="">Facture/Devis</a></li>
                    <li><a  href="">Comptabilité</a></li>
                    <li><a  href="">Emailing</a></li>
                    <li><a  href="">Rendez-vous</a></li>
                    <li><a  href="">Sites/Applications</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="">
                    <i class="fa fa-desktop"></i>
                    <span>Outils</span>
                </a>
                <ul class="sub">
                    <li><a  href="">Tâches</a></li>
                    <li><a  href="">Bloc note</a></li>
                    <li><a  href="">Hebergement</a></li>
                    <li><a  href="">Logs</a></li>
                </ul>
            </li>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->