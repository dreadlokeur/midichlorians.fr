<section id="main-content">
    <section class="wrapper site-min-height">
        <h3>Page</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="form-panel">
                    <form method="POST" action="<?php echo $this->getUrl('pageUpdate', array($this->page->name, true)); ?>" class="form-horizontal style-form form-update">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Nom</label>
                            <div class="col-sm-10">
                                <input type="text" disabled="" value="<?php echo $this->page->name; ?>" id="name" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Titre</label>
                            <div class="col-sm-10">
                                <input type="text" value="<?php echo $this->page->title; ?>" id="title" name="title" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Menu</label>
                            <div class="col-sm-10">
                                <input type="text" value="<?php echo $this->page->menu; ?>" id="menu" name="menu" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Contenu</label>
                            <div class="col-sm-10">
                                <div class="well wysiwyg" id="content"><?php echo $this->page->content; ?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Supprimable</label>
                            <div class="col-sm-10">
                                <input type="checkbox" id="deletable" name="deletable" data-toggle="switch" <?php if ($this->page->deletable) { ?>checked=""<?php } ?>>
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