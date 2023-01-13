<?php

use DigitalMarketingFramework\Typo3\Collector\Core\Controller\CollectorController;
use DigitalMarketingFramework\Typo3\Collector\Core\Scheduler\SessionCleanupTask;
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

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][SessionCleanupTask::class] = [
        'extension' => 'digitalmarketingframework_collector',
        'title' => 'Digital Marketing Framework - Collector - Session Cleanup (Bot Protection)',
        'description' => 'Removes expired entries from the bot-protection tables',
    ];

    // the "map" argument can safely be excluded from the cHash
    // - because it is filtered in the controller (whitelist)
    // - and because the whole ajax service of this extension is not cached
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_digitalmarketingframeworkcollector_ajaxservice[map]';
})();
