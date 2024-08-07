<?php

use DigitalMarketingFramework\Typo3\Collector\Core\Utility\TcaUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || exit;

(static function (): void {
    // -- frontend plugin content modifier --

    ExtensionUtility::registerPlugin(
        extensionName: 'DmfCollectorCore',
        pluginName: 'ContentModifier',
        pluginTitle: 'DMF Content'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['dmfcollectorcore_contentmodifier'] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['dmfcollectorcore_contentmodifier'] = 'recursive,select_key,pages';
    ExtensionManagementUtility::addPiFlexFormValue(
        'dmfcollectorcore_contentmodifier',
        'FILE:EXT:dmf_collector_core/Configuration/FlexForms/ContentModifierPlugin.xml'
    );

    // -- frontend element content modifier --

    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        [
            'tx_dmf_collector_core_content_modifier' => TcaUtility::getContentModifierConfigEditorTcaField(true, 'element'),
        ]
    );

    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Digital Marketing,tx_dmf_collector_core_content_modifier'
    );
})();
