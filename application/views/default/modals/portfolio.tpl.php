<!-- Portfolio Modals -->
<?php if (is_array($this->references) && count($this->references) > 0) { ?>
    <?php foreach ($this->references as $reference) { ?>
        <div class="portfolio-modal modal fade" id="referenceModal<?php echo $reference->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-content">
                <div class="close-modal" data-dismiss="modal">
                    <div class="lr">
                        <div class="rl">
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-lg-offset-2">
                            <div class="modal-body">
                                <h2 class="align-center"><?php echo $reference->name; ?></h2>
                                <hr class="star-primary">
                                <?php echo $reference->content; ?>
                                <div class="align-center">
                                    <ul class="list-inline item-details">
                                        <li>Date: <strong><?php echo $reference->getDate(true); ?></strong></li>
                                        <li>Technologies: <strong><?php echo $reference->technology; ?></strong></li>
                                        <li>Lien: <strong><a href="<?php echo $reference->link; ?>" target='blanck'>Voir le site</a></strong></li>
                                    </ul>
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>