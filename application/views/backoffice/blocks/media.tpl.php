<section id="main-content">
    <section class="wrapper site-min-height">
        <h3>Medias</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="content-panel">
                    <section id="unseen">
                        <button class="btn btn-theme03 margin-bot-10" type="button" id="request-media-dropzone">Ajouter</button>
                        <div id="media-dropzone" class="margin-bot-20 hide">
                            <form action="<?php echo $this->getUrl('mediaAdd'); ?>" class="dropzone clickable" enctype="multipart/form-data" id="formMediaDropzone" max-size="<?php echo MEDIA_MAXSIZE; ?>" accept="<?php echo MEDIA_ACCEPT; ?>"></form>
                        </div>
                        <?php include $this->getPath() . 'tables' . DS . 'media.tpl.php'; ?>
                        <h4>Action groupées</h4>
                        <a href="" class="btn btn-default deleteAll" title="Supprimer la selection" alt="Supprimer la selection"><i class="fa fa-times"></i></a>
                    </section>
                </div><!-- /content-panel -->
            </div><!-- /col-lg-4 -->			
        </div>
    </section>
</section>