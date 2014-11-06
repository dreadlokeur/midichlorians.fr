<!--header start-->
<header class="header black-bg">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
    <a href="<?php echo $this->getUrl('backoffice'); ?>" class="logo ajax-switcher"><b>Administration</b></a>
    <div class="top-menu">
        <ul class="nav pull-right top-menu">
            <li>
                <a class="logout" href="<?php echo $this->urls->index; ?>" title="Retour au site" alt="Retour au site">
                    <i class="fa fa-sign-out  fa-2x fa-fw"></i>
                </a>
            </li>
            <li>
                <a class="logout" href="<?php echo $this->urls->logout; ?>" title="Deconnexion" alt="Deconnexion" id="logout">
                    <i class="fa fa-power-off  fa-2x fa-fw"></i>
                </a>
            </li>
        </ul>
    </div>
</header>
<!--header end-->