<!DOCTYPE html>
<html lang="<?php echo $this->lang; ?>">
    <?php include $this->getPath() . 'includes' . DS . 'head.tpl.php'; ?>
    <body id="page-top" class="index">
        <?php include $this->getPath() . 'includes' . DS . 'navigation.tpl.php'; ?>
        <!-- content -->
        <?php include $this->getPath() . 'includes' . DS . 'home.tpl.php'; ?>
        <?php include $this->getPath() . 'includes' . DS . 'about.tpl.php'; ?>
        <?php include $this->getPath() . 'includes' . DS . 'prestation.tpl.php'; ?>
        <?php include $this->getPath() . 'includes' . DS . 'portfolio.tpl.php'; ?>
        <?php include $this->getPath() . 'includes' . DS . 'cv.tpl.php'; ?>
        <!-- modals -->
        <?php include $this->getPath() . 'modals' . DS . 'portfolio.tpl.php'; ?>
        <?php include $this->getPath() . 'modals' . DS . 'legal.tpl.php'; ?>
        <!-- footer -->
        <?php include $this->getPath() . 'includes' . DS . 'footer.tpl.php'; ?>
    </body>
</html>