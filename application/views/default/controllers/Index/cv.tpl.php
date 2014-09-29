<!-- prestation -->
<section class="success" id="cv">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2><?php echo $this->pages['cv']->title; ?></h2>
                <hr class="star-light">
            </div>
        </div>
        <div class="row mt text-center">
            <div class="col-lg-12">
                <?php echo $this->pages['cv']->content; ?>
            </div>
        </div>
        <div class="row mt text-center">
            <?php if (is_array($this->cvs) && count($this->cvs) > 0) { ?>
                <?php foreach ($this->cvs as $cv) { ?>
                    <div class="col-lg-4">
                        <a href="<?php echo $cv->link; ?>" target="_blank">
                            <img alt="<?php echo $cv->name; ?>" src="<?php echo $cv->thumb; ?>">
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</section>