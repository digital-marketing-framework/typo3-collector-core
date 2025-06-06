<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Utility;

class TcaUtility
{
    /**
     * @param array<string,mixed> $additionalParameters
     *
     * @return array<string,mixed>
     */
    public static function getConfigEditorTcaField(string $mode, string $documentType, bool $supportsIncludes, array $additionalParameters = []): array
    {
        return [
            'label' => 'Content Modification',
            'exclude' => 1,
            'config' => [
                'type' => 'text',
                'renderType' => 'digitalMarketingFrameworkConfigurationEditorTextFieldElement',
                'mode' => $mode,
                'ajaxControllerDocumentType' => $documentType,
                'ajaxControllerSupportsIncludes' => $supportsIncludes,
                'ajaxControllerAdditionalParameters' => $additionalParameters,
                'cols' => 30,
                'rows' => 10,
            ],
        ];
    }

    /**
     * @return array<string,mixed>
     */
    public static function getContentModifierConfigEditorTcaField(bool $asList, string $group, string $mode = 'modal'): array
    {
        return static::getConfigEditorTcaField(
            mode: $mode,
            documentType: 'content-modifier',
            supportsIncludes: false,
            additionalParameters: [
                'contentModifierList' => $asList,
                'contentModifierGroup' => $group,
            ]
        );
    }
}
