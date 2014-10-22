<!DOCTYPE html>
<html lang="<?php echo $this->lang; ?>">
    <head>
        <title><?php echo $this->langs->site_name; ?><?php if ($this->title) echo ' - ' . $this->title; ?></title>
        <meta charset="<?php echo $this->getCharset(); ?>">
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->getCharset(); ?>" />
        <meta http-equiv="Expires" content="24Oct 2018 23:59:59 GMT">
        <meta http-equiv="Cache-Control" content="public;max-age=315360000" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="Robots" content="index,follow" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style media="screen" type="text/css"><?php echo $this->getCss(); ?></style>
    </head>
    <body>
        <section class="success">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2><?php echo $this->errorInfo['code'] . ' ' . $this->errorInfo['message']; ?></h2>
                    </div>
                </div>
            </div>
        </section>
        <script type="text/javascript"><?php echo $this->getJs(); ?></script>
    </body>
</html>