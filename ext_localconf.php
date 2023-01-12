<?php

defined('TYPO3') || die();

use DigitalMarketingFramework\Typo3\Collector\Core\Controller\CollectorController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

ExtensionUtility::configurePlugin(
    'DigitalmarketingframeworkCollector',
    'AjaxService',
    [
        CollectorController::class => 'show',
    ],
    // non-cacheable actions
    [
        CollectorController::class => 'show',
    ]
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['TyposcriptValueMapper'] =
    \DigitalMarketingFramework\Typo3\Collector\Core\Routing\Aspect\TyposcriptValueMapper::class;
