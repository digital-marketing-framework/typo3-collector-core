<?php

use DigitalMarketingFramework\Typo3\Collector\Core\Controller\ContentModifierController;
use DigitalMarketingFramework\Typo3\Collector\Core\Scheduler\SessionCleanupTask;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

(static function () {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][SessionCleanupTask::class] = [
        'extension' => 'dmf_collector_core',
        'title' => 'Anyrel - Collector - Session Cleanup (Bot Protection)',
        'description' => 'Removes expired entries from the bot-protection tables',
    ];
})();

ExtensionUtility::configurePlugin(
    extensionName: 'DmfCollectorCore',
    pluginName: 'ContentModifier',
    controllerActions: [ContentModifierController::class => 'renderContentModifier'],
    nonCacheableControllerActions: [],
);
