<section id="main-content">
    <section class="wrapper site-min-height">
        <h3>Media</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="form-panel">
                    <form method="POST" action="<?php echo $this->getUrl('mediaUpdate', array($this->media->id, true)); ?>" class="form-horizontal style-form form-update">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Nom</label>
                            <div class="col-sm-10">
                                <input type="text" disabled="" value="<?php echo $this->media->getFilename(false); ?>" id="filename" name="filename" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Type</label>
                            <div class="col-sm-10">
                                <input type="text" disabled="" value="<?php echo $this->media->type; ?>" id="type" name="type" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Mime</label>
                            <div class="col-sm-10">
                                <input type="text" disabled="" value="<?php echo $this->media->mime; ?>" id="mime" name="mime" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Légende</label>
                            <div class="col-sm-10">
                                <input type="text" value="<?php echo $this->media->title; ?>" id="title" name="title" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Texte alternatif</label>
                            <div class="col-sm-10">
                                <input type="text" value="<?php echo $this->media->alt; ?>" id="alt" name="alt" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Poids (MB)</label>
                            <div class="col-sm-10">
                                <input type="text" disabled="" value="<?php echo $this->media->getSize(true); ?>" id="size" name="size" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Date d'ajout</label>
                            <div class="col-sm-10">
                                <input type="text" disabled="" value="<?php echo $this->media->date; ?>" id="size" name="size" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Fichier</label>
                            <div class="col-sm-10">
                                <?php if ($this->media->isImage()) { ?>

                                    <ul class="list-inline margin-top-30">
                                        <li><i class="fa fa-crop fa-4x cursor-pointer media-crop" title="Recadrer"></i></li>
                                        <li><i class="fa fa-arrows-h fa-4x cursor-pointer media-flip-h" title="Retournement horizontal"></i></li>
                                        <li><i class="fa fa-arrows-v fa-4x cursor-pointer media-flip-v" title="Retournement vertical"></i></li>
                                        <li><i class="fa fa-undo fa-4x cursor-pointer media-rotate-l" title="Tourner dans le sens inverse des aiguilles d’une montre"></i></li>
                                        <li><i class="fa fa-repeat fa-4x cursor-pointer media-rotate-r" title="Tourner dans le sens des aiguilles d’une montre"></i></li>
                                    </ul>
                                    <ul class="list-inline">
                                        <li>Hauteur 
                                            <input type="number" value="<?php echo $this->media->height; ?>" id="media-height" name="height" class="form-control" required="">
                                            <input type="hidden" value="<?php echo $this->media->height; ?>" id="media-height-default" name="height-default">
                                        </li>
                                        <li>Largeur 
                                            <input type="number" value="<?php echo $this->media->width; ?>" id="media-width" name="width" class="form-control" required="">
                                            <input type="hidden" value="<?php echo $this->media->width; ?>" id="media-width-default" name="width-default">
                                        </li>
                                        <li>Conserver les proportions <p class="margin-top-10"><input type="checkbox" id="media-proportion" name="proportion" checked="" class="form-control" data-toggle="switch"></p></li>
                                    </ul>
                                    <ul class="list-inline margin-top-30" id="coords">
                                        <li>Axe X <input type="number" class="form-control" id="x1" name="x1" /></li>
                                        <li><input type="hidden" class="form-control" id="x2" name="x2" /></li>
                                        <li>Axe Y <input type="number" class="form-control" id="y1" name="y1" /></li>
                                        <li><input type="hidden" class="form-control" id="y2" name="y2" /></li>
                                        <li>Hauteur <input type="number" class="form-control" id="h" name="h" /></li>
                                        <li>Largeur <input type="number" class="form-control" id="w" name="w" /></li>
                                    </ul>
                                    <p id="media-block">
                                        <img class="cursor-pointer img-responsive media" src="<?php echo $this->media->filename; ?>">
                                        <input type="hidden" value="0" id="rotate" name="rotate" class="form-control">
                                        <input type="hidden" value="0" id="flipH" name="flipH" class="form-control">
                                        <input type="hidden" value="0" id="flipV" name="flipV" class="form-control">
                                    </p>

                                <?php } elseif ($this->media->isAudio()) { ?>
                                    <audio controls>
                                        <source src="<?php echo $this->media->filename; ?>" type="audio/ogg">
                                        <source src="<?php echo $this->media->filename; ?>" type="<?php echo $this->media->mime; ?>">
                                        Your browser does not support the audio tag.
                                    </audio> 
                                <?php } elseif ($this->media->isVideo()) { ?>
                                    <video controls>
                                        <source src="<?php echo $this->media->filename; ?>" type="<?php echo $this->media->mime; ?>">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php } ?>
                            </div>
                        </div>


                        <div class="form-group margin-top-20">
                            <div class="col-sm-12 text-center">
                                <input class="btn btn-theme03 update" type="submit" value="Eregistrer"></button>
                                <input class="btn btn-theme04" type="reset" value="Annuler">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- col-lg-12-->      	
    </section>
</section>