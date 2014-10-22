<!-- prestation -->
<section class="success" id="prestation">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2><?php echo $this->pages['prestation']->title; ?></h2>
                <hr class="star-light">
            </div>
        </div>
        <div class="row mt text-center">
            <div class="col-lg-12">
                <?php echo $this->pages['prestation']->content; ?>
            </div>
        </div>
        <div class="row mt text-center">
            <?php if (is_array($this->prestations) && count($this->prestations) > 0) { ?>
                <?php foreach ($this->prestations as $prestation) { ?>
                    <div class="col-lg-3 padding-top-20">
                        <i class="fa fa-<?php echo $prestation->icon; ?> fa-3x"></i>
                        <p><?php echo $prestation->content; ?></p>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</section>