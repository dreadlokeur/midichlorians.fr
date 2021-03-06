<div role="dialog" tabindex="-1" id="medias" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Medias</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php if (is_array($this->medias) && count($this->medias) > 0) { ?>
                        <?php foreach ($this->medias as $media) { ?>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 desc padding-top-10">
                                <div id="<?php echo $media->id; ?>">
                                    <?php if ($media->isImage()) { ?>
                                        <img class="mediaModal thumbnail img-responsive" src="<?php echo $media->filename; ?>" alt="<?php echo $media->filename; ?>">
                                    <?php } else { ?>
                                        <img class="mediaModal thumbnail img-responsive" src="<?php echo $media->getThumbType(); ?>" alt="<?php echo $media->filename; ?>">
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
            </div>
        </div>
    </div>
</div>

