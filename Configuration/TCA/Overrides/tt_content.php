<?php

use DigitalMarketingFramework\Typo3\Collector\Core\Utility\TcaUtility;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || exit;

(static function (): void {
    // -- frontend plugin content modifier --

    $flexFormPath = 'FILE:EXT:dmf_collector_core/Configuration/FlexForms/ContentModifierPlugin.xml';

    if ((new Typo3Version())->getMajorVersion() >= 14) {
        // v14+: plugins are first-class CTypes. The "list" subtype mechanism was removed
        // (Breaking #105538) and addPiFlexFormValue() is deprecated (#107047). FlexForm is
        // passed directly to registerPlugin() via the new $flexForm parameter.
        // @phpstan-ignore-next-line TYPO3 version switch — $flexForm parameter only in v14+
        ExtensionUtility::registerPlugin(
            'DmfCollectorCore',
            'ContentModifier',
            'Anyrel Content',
            null,
            'plugins',
            '',
            $flexFormPath,
        );
    } else {
        // v12/v13: plugin registered as a "list" subtype; FlexForm assigned separately.
        ExtensionUtility::registerPlugin(
            extensionName: 'DmfCollectorCore',
            pluginName: 'ContentModifier',
            pluginTitle: 'Anyrel Content',
        );

        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['dmfcollectorcore_contentmodifier'] = 'pi_flexform';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['dmfcollectorcore_contentmodifier'] = 'recursive,select_key,pages';
        ExtensionManagementUtility::addPiFlexFormValue(
            'dmfcollectorcore_contentmodifier',
            $flexFormPath
        );
    }

    // -- frontend element content modifier --

    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        [
            'tx_dmf_collector_core_content_modifier' => TcaUtility::getContentModifierConfigEditorTcaField(true, 'element'),
        ]
    );

    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Anyrel,tx_dmf_collector_core_content_modifier'
    );
})();
