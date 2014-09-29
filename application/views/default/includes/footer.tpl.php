<!-- Footer -->
<footer class="">
    <div class="footer-above">
        <div class="container">
            <div class="row">
                <div class="footer-col col-md-4">
                    <h3><?php echo $this->langs->h3_1; ?></h3>
                    <i class="fa fa-fw fa-phone-square"></i> <?php echo $this->config['phone']->value; ?><br>
                    <i class="fa fa-fw fa-envelope-square"></i> <?php echo $this->config['mail']->value; ?><br>
                    <span class="pull-left text-uppercase"><?php echo $this->langs->content_1; ?> : <?php echo $this->config['siret']->value; ?></span>
                </div>
                <div class="footer-col col-md-4">
                    <h3><?php echo $this->langs->h3_2; ?></h3>
                    <ul class="list-inline">
                        <?php if (is_array($this->networks) && count($this->networks) > 0) { ?>
                            <?php foreach ($this->networks as $network) { ?>
                                <li>
                                    <a href="<?php echo $network->link; ?>" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw <?php echo $network->icon; ?>"></i></a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
                <div class="footer-col col-md-4">
                    <h3><?php echo $this->langs->h3_3; ?></h3>
                    <ul>
                        <?php if (is_array($this->backlinks) && count($this->backlinks) > 0) { ?>
                            <?php foreach ($this->backlinks as $backlink) { ?>
                                <li><a href="<?php echo $backlink->link; ?>" title="<?php echo $backlink->descr; ?>" target="_banck"><?php echo $backlink->name; ?></a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-below">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    Copyright &copy; <?php echo HOSTNAME; ?> <?php echo Date("Y"); ?>
                    <span class="pull-right text-uppercase"><a href="#legal" class="portfolio-link" data-toggle="modal"><?php echo $this->langs->content_2; ?></a></span>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
<div id="scrolltop" class="scroll-top page-scroll hide">
    <a class="btn btn-primary" href="#page-top">
        <i class="fa fa-chevron-up"></i>
    </a>
</div>
<script type="text/javascript"><?php echo $this->getJs(); ?></script>