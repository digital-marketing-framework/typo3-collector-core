<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || exit;

(static function (): void {
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
})();
