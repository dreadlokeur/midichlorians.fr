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