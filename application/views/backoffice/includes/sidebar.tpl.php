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
                    <li><a href="">Languages</a></li>
                    <li><a href="">Paramètres</a></li>
                </ul>
            </li>
            <li>
                <a href="<?php echo $this->getUrl('media'); ?>" class="ajax-switcher">
                    <i class="fa fa-dashboard"></i>
                    <span>Médias</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $this->getUrl('reference'); ?>" class="ajax-switcher">
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
                    <li><a  href="">Logs</a></li>
                </ul>
            </li>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->