<!-- Portfolio Grid Section -->
<section id="portfolio">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2><?php echo $this->pages['reference']->title; ?></h2>
                <hr class="star-primary">
            </div>
        </div>
        <div class="row mt text-center">
            <div class="col-lg-12">
                <?php echo $this->pages['reference']->content; ?>
            </div>
        </div>
        <div class="row">
            <?php if (is_array($this->references) && count($this->references) > 0) { ?>
                <?php foreach ($this->references as $reference) { ?>
                    <div class="col-sm-4 portfolio-item">
                        <a href="#referenceModal<?php echo $reference->id; ?>" class="portfolio-link" data-toggle="modal">
                            <div class="caption">
                                <div class="caption-content">
                                    <i class="fa fa-search-plus fa-3x"></i>
                                </div>
                            </div>
                            <img src="<?php echo $reference->thumb; ?>" class="img-responsive" alt="<?php echo $reference->name; ?>">
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</section>


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
                                <h2><?php echo $reference->name; ?></h2>
                                <hr class="star-primary">
                                <img src="<?php echo $reference->image; ?>" class="img-responsive img-centered" alt="">
                                <p><?php echo $reference->descr; ?></p>
                                <ul class="list-inline item-details">
                                    <li>Date:
                                        <strong><?php echo $reference->date; ?></strong>
                                    </li>
                                    <li>Lien:
                                        <strong><a href="<?php echo $reference->link; ?>">Voir le site</a></strong>
                                    </li>
                                </ul>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>