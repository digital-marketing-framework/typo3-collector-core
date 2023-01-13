<?php

use DigitalMarketingFramework\Typo3\Collector\Core\Controller\CollectorController;
use DigitalMarketingFramework\Typo3\Collector\Core\Scheduler\SessionCleanupTask;
use DigitalMarketingFramework\Typo3\Collector\Core\Routing\Aspect\TyposcriptValueMapper;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

(function () {
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

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['TyposcriptValueMapper'] = TyposcriptValueMapper::class;

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][SessionCleanupTask::class] = [
        'extension' => 'digitalmarketingframework_collector',
        'title' => 'Digital Marketing Framework - Collector - Session Cleanup (Bot Protection)',
        'description' => 'Removes expired entries from the bot-protection tables',
    ];
})();
