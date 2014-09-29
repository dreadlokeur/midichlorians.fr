<!-- About Section -->
<section id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2><?php echo $this->pages['about']->title; ?></h2>
                <hr class="star-primary">
            </div>
        </div>

        <div class="row mt">
            <div class="col-lg-6">
                <?php echo $this->pages['about']->content; ?>
            </div>
            <div class="col-lg-6">
                <h4><?php echo $this->langs->h4_1; ?></h4>
                 <?php if (is_array($this->skills) && count($this->skills) > 0) { ?>
                    <?php foreach ($this->skills as $skill) { ?>
                        <span class=""><?php echo $skill->name; ?></span>
                        <div class="progress">
                            <div style="width: <?php echo $skill->value; ?>%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $skill->value; ?>" role="progressbar" class="progress-bar progress-bar-theme">
                                <span class=""><?php echo $skill->value; ?> %</span>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</section>