<!DOCTYPE html>
<html lang="<?php echo $this->lang; ?>">
    <?php include $this->getPath() . 'includes' . DS . 'head.tpl.php'; ?>
    <body>
        <section id="container">
            <?php include $this->getPath() . 'includes' . DS . 'header.tpl.php'; ?>
            <?php include $this->getPath() . 'includes' . DS . 'sidebar.tpl.php'; ?>
            <div id="ajax-content">
                <?php if ($this->block) include $this->block; ?>
            </div>
            <input type="hidden" value="<?php echo $this->csrf; ?>" id="csrf" name="csrf">
            <img class="hide" id="global-loader" src="<?php echo $this->getUrlAsset('img'); ?>loader.gif" />
            <?php include $this->getPath() . 'includes' . DS . 'footer.tpl.php'; ?>
        </section>
    </body>
</html>