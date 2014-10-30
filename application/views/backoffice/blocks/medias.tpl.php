<section id="main-content">
    <section class="wrapper site-min-height">
        <h3>Medias</h3>
        <?php include $this->getPath() . 'includes' . DS . 'datatablesFilter.tpl.php'; ?>
        <div class="col-md-3 margin-top-10 pull-left">
            <button class="btn btn-theme03 btn-lg btn-block padding-bot-5 padding-top-5" type="button" id="request-media-dropzone">Ajouter</button>
        </div>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="content-panel">
                    <section id="unseen">
                        <div id="media-dropzone" class="margin-bot-20 hide">
                            <form action="<?php echo $this->getUrl('mediaAdd'); ?>" class="dropzone clickable" enctype="multipart/form-data" id="formMediaDropzone" max-size="<?php echo MEDIA_MAXSIZE; ?>" accept="<?php echo MEDIA_ACCEPT; ?>"></form>
                        </div>
                        <?php include $this->getPath() . 'tables' . DS . 'medias.tpl.php'; ?>
                    </section>
                </div><!-- /content-panel -->
            </div><!-- /col-lg-4 -->			
        </div>
    </section>
</section>