<section id="main-content">
    <section class="wrapper site-min-height">
        <h3>Préférences</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="content-panel">
                    <section id="unseen">
                        <h3>CV</h3>
                        <?php include $this->getPath() . 'tables' . DS . 'cv.tpl.php'; ?>
                        <h4>Action groupées</h4>
                        <a href="" class="btn btn-default deleteAll" title="Supprimer la selection" alt="Supprimer la selection"><i class="fa fa-times"></i></a>
                    </section>
                </div><!-- /content-panel -->
            </div><!-- /col-lg-4 -->			
        </div>
    </section>
</section>
<?php include $this->getPath() . 'modals' . DS . 'media.tpl.php'; ?>