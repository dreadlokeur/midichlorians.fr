<section id="main-content">
    <section class="wrapper site-min-height">
        <h3>Pages</h3>
        <?php include $this->getPath() . 'includes' . DS . 'datatablesFilter.tpl.php'; ?>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="content-panel">
                    <section id="unseen">
                        <?php include $this->getPath() . 'tables' . DS . 'pages.tpl.php'; ?>
                    </section>
                </div><!-- /content-panel -->
            </div><!-- /col-lg-4 -->			
        </div>
    </section>
</section>