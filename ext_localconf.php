<?php

use DigitalMarketingFramework\Typo3\Collector\Core\Controller\ContentModifierController;
use DigitalMarketingFramework\Typo3\Collector\Core\Scheduler\SessionCleanupTask;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

ExtensionUtility::configurePlugin(
    extensionName: 'DmfCollectorCore',
    pluginName: 'ContentModifier',
    controllerActions: [ContentModifierController::class => 'renderContentModifier'],
    nonCacheableControllerActions: [],
);
