<?php

use DigitalMarketingFramework\Typo3\Collector\Core\Utility\TcaUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || exit;

(static function (): void {
    // -- frontend page content modifier --

    ExtensionManagementUtility::addTCAcolumns(
        'pages',
        [
            'tx_dmf_collector_core_content_modifier' => TcaUtility::getContentModifierConfigEditorTcaField(true, 'page'),
        ]
    );

    ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '--div--;Anyrel,tx_dmf_collector_core_content_modifier'
    );
})();
