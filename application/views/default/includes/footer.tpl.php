<!-- Footer -->
<footer class="">
    <div class="footer-above">
        <div class="container">
            <?php echo $this->pages['footer']->content; ?>
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
<div id="scrolltop" class="scroll-top page-scroll hide">
    <a href="#page-top" class="btn btn-primary">
        <i class="fa fa-chevron-circle-up fa-4x cursor-pointer"></i>
    </a>
</div>
<script type="text/javascript"><?php echo $this->getJs(); ?></script>