<section id="main-content">
    <section class="wrapper site-min-height">
        <h3>Référence</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="form-panel">
                    <form method="POST" action="<?php echo $this->getUrl('referenceUpdate', array($this->reference->id, true)); ?>" class="form-horizontal style-form form-update">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Nom</label>
                            <div class="col-sm-10">
                                <input type="text" value="<?php echo $this->reference->name; ?>" id="name" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Date</label>
                            <div class="col-sm-10">
                                <input type="text" value="<?php echo $this->reference->date; ?>" id="date" name="date" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Lien</label>
                            <div class="col-sm-10">
                                <input type="text" value="<?php echo $this->reference->link; ?>" id="link" name="link" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Technologies</label>
                            <div class="col-sm-10">
                                <input type="text" value="<?php echo $this->reference->technology; ?>" id="technology" name="technology" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Contenu</label>
                            <div class="col-sm-10">
                                <div class="well wysiwyg" id="content"><?php echo $this->reference->content; ?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Vignette</label>
                            <div class="col-sm-2 col-sm-2">
                                <input type="text" value="<?php echo $this->reference->media->id; ?>" id="mediaId" name="mediaId" class="hide">
                                <img id="imageMediaId" src="<?php echo $this->reference->media->filename; ?>" data-target="#medias" data-toggle="modal" title="Parcourir les medias" alt="Parcourir les medias" class="cursor-pointer thumbnail img-responsive">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">En ligne</label>
                            <div class="col-sm-10">
                                <input type="checkbox" id="online" name="online" data-toggle="switch" <?php if ($this->reference->online) { ?>checked=""<?php } ?>>
                            </div>
                        </div>
                        <div class="form-group margin-top-20">
                            <div class="col-sm-12 text-center">
                                <input class="btn btn-theme03 update" type="submit" value="Eregistrer">
                                <input class="btn btn-theme04" type="reset" value="Annuler">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- col-lg-12-->      	
    </section>
</section>
<?php include $this->getPath() . 'modals' . DS . 'medias.tpl.php'; ?>