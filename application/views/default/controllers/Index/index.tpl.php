<!DOCTYPE html>
<html lang="<?php echo $this->lang; ?>">
    <?php include $this->getPath() . 'includes' . DS . 'head.tpl.php'; ?>
    <body id="page-top" class="index">
        <?php include $this->getPath() . 'includes' . DS . 'navigation.tpl.php'; ?>
        <?php include $this->getPath() . 'includes' . DS . 'header.tpl.php'; ?>

        <!-- content -->
        <?php include $this->getPath() . 'controllers' . DS . 'Index' . DS . 'about.tpl.php'; ?>
        <?php include $this->getPath() . 'controllers' . DS . 'Index' . DS . 'prestation.tpl.php'; ?>
        <?php include $this->getPath() . 'controllers' . DS . 'Index' . DS . 'portfolio.tpl.php'; ?>
        <?php include $this->getPath() . 'controllers' . DS . 'Index' . DS . 'cv.tpl.php'; ?>
        <?php include $this->getPath() . 'controllers' . DS . 'Index' . DS . 'legal.tpl.php'; ?>

        <?php include $this->getPath() . 'includes' . DS . 'footer.tpl.php'; ?>
    </body>


</html>