<!--header start-->
<header class="header black-bg">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
    <a href="" class="logo"><b>Administration</b></a>
    <div id="top_menu" class="nav notify-row">
        <!--  notification start -->
        <ul class="nav top-menu">
            <!-- settings start -->
            <li class="dropdown">
                <a href="" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-tasks"></i>
                    <span class="badge bg-theme">4</span>
                </a>
                <ul class="dropdown-menu extended tasks-bar">
                    <div class="notify-arrow notify-arrow-green"></div>
                    <li>
                        <p class="green">You have 4 pending tasks</p>
                    </li>
                    <li>
                        <a href="">
                            <div class="task-info">
                                <div class="desc">DashGum Admin Panel</div>
                                <div class="percent">40%</div>
                            </div>
                            <div class="progress progress-striped">
                                <div style="width: 40%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="40" role="progressbar" class="progress-bar progress-bar-success">
                                    <span class="sr-only">40% Complete (success)</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="external">
                        <a href="">See All Tasks</a>
                    </li>
                </ul>
            </li>
            <!-- settings end -->
        </ul>
        <!--  notification end -->
    </div>
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