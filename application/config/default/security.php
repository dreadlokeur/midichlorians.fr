<?php

$config = array(
    // optionType => array(options)
    'form' => array(
        'autorun' => false,
        'form' => array(
            'backoffice' => array(
                'protection' => array(
                    'csrf' => array(
                        'urlReferer' => array('backoffice', 'login', 'logout'), //routes name
                        'timeValidity' => 600 //second
                    )
                //TODO BF
                ),
            ),
        )
    ),
    'sniffer' => array(
        'autorun' => true,
        'trapName' => 'badbottrap',
        'badCrawlerFile' => '[PATH_DATA]sniffer[DS]crawlerBad.xml',
        'goodCrawlerFile' => '[PATH_DATA]sniffer[DS]crawlerGood.xml',
        'logBadCrawler' => true,
        'logGoodCrawler' => true,
        'logUnknownCrawler' => true
    )
);
?>
