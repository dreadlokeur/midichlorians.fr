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
                            <img src="<?php echo $reference->media->filename; ?>" class="img-responsive" alt="<?php echo $reference->name; ?>">
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</section>