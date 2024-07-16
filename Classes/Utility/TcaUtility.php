<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Utility;

class TcaUtility
{
    public static function getConfigEditorTcaField(string $mode, string $baseRoute, bool $supportsIncludes, array $additionalParameters = []): array
    {
        return [
            'label' => 'Content Modification',
            'exclude' => 1,
            'config' => [
                'type' => 'text',
                'renderType' => 'digitalMarketingFrameworkConfigurationEditorTextFieldElement',
                'mode' => $mode,
                'ajaxControllerBaseRoute' => $baseRoute,
                'ajaxControllerSupportsIncludes' => $supportsIncludes,
                'ajaxControllerAdditionalParameters' => $additionalParameters,
                'cols' => 30,
                'rows' => 10,
            ],
        ];
    }

    public static function getContentModifierConfigEditorTcaField(bool $asList, string $group, string $mode = 'modal'): array
    {
        return static::getConfigEditorTcaField(
            mode: $mode,
            baseRoute: 'contentmodifier',
            supportsIncludes: false,
            additionalParameters: [
                'contentModifierList' => $asList,
                'contentModifierGroup' => $group,
            ]
        );
    }
}
