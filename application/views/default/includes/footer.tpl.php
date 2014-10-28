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
<!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
<div id="scrolltop" class="scroll-top page-scroll hide">
    <a class="btn btn-primary" href="#page-top">
        <i class="fa fa-chevron-up"></i>
    </a>
</div>
<script type="text/javascript"><?php echo $this->getJs(); ?></script>