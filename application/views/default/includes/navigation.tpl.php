<!-- bot trap -->
<a class="hide" href="?badbottrap"></a>
<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="page-scroll">
                    <a href="#page-top"><?php echo $this->pages['home']->menu; ?></a>
                </li>
                <li class="page-scroll">
                    <a href="#about"><?php echo $this->pages['about']->menu; ?></a>
                </li>
                <li class="page-scroll">
                    <a href="#prestation"><?php echo $this->pages['prestation']->menu; ?></a>
                </li>
                <li class="page-scroll">
                    <a href="#portfolio"><?php echo $this->pages['reference']->menu; ?></a>
                </li>
                <li class="page-scroll">
                    <a href="#cv"><?php echo $this->pages['cv']->menu; ?></a>
                </li>
<!--                <li class="page-scroll">
                    <a href="#contact"><?php echo $this->pages['contact']->menu; ?></a>
                </li>-->
                <li>
                    <a href="" target="_blank"><?php echo $this->pages['blog']->menu; ?></a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>