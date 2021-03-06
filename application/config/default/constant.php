<?php

$config = array(
    //NAME => VALUE
    //required
    'ENVIRONNEMENT' => 'dev', //dev/test/prod
    'HOSTNAME' => 'local.dev/midichlorians.fr', // your hostname
    'ADMIN_NAME' => 'dreadlokeur', // administrator name
    'ADMIN_EMAIL' => 'dreadlokeur@gmail.com', // administrator email
    'LANGUAGE_DEFAULT' => 'fr-FR',
    //Optional
    'SITE_MAINTENANCE' => false, // true/false (if true, running route "error" with param : "503")
    'SMTP_SERVER' => null,
    'TIMEZONE' => 'Europe/Paris',
    'TEMPLATE_DEFAULT' => 'default', //template name
    'AUTOLOADER_CACHE' => 'core', //cache name/ false
    'AUTOLOADER_GLOBALIZER' => true, //autoloader cache must be activated)
    'GOOGLE_VERIFICATION' => null,
    'GOOGLE_UA' => null,
    //logger
    'LOGGER_LEVEL' => 4, // EMERGENCY = 0,  ALERT = 1, CRITICAL = 2, ERROR = 3, WARNING = 4, NOTICE = 5, INFO = 6, DEBUG = 7
    'LOGGER_BACKTRACE' => false,
    'LOGGER_WRITE' => true,
    'LOGGER_DISPLAY' => 'display,firebug',
    'LOGGER_MAIL' => true,
    'LOGGER_MAIL_TO_NAME' => '[ADMIN_NAME]',
    'LOGGER_MAIL_TO_EMAIL' => '[ADMIN_EMAIL]',
    'LOGGER_CACHE' => 'core', //cache name
    'LOGGER_ERROR' => true,
    //app
    'ADMIN_PASSWORD' => '**********************',
    'MEDIA_MAXSIZE' => 2, //(MiB)
    'MEDIA_ACCEPT' => 'image/*, audio/*, video/*',
    'MEDIA_SIZE_PORTFOLIO_PROPORTION' => true,
    'MEDIA_SIZE_PORTFOLIO_W' => 360,
    'MEDIA_SIZE_PORTFOLIO_H' => 239,
    'GITHUB_USER' => 'dreadlokeur',
);
?>
