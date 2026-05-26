<?php

declare(strict_types=1);

use Mediatis\Typo3CodingStandards\Php\Typo3RectorSetup;
use Rector\Config\RectorConfig;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;

return static function (RectorConfig $rectorConfig): void
{
    Typo3RectorSetup::setup($rectorConfig, __DIR__);

    // The v14+ branch in TCA/Overrides/tt_content.php passes a 7th argument to
    // ExtensionUtility::registerPlugin() that only exists on v14. On v12/v13 vendor,
    // Rector reads the 6-param signature and would "fix" the call by dropping the
    // extra arg — which is exactly the v14-only piece we need to keep.
    $rectorConfig->skip([
        RemoveExtraParametersRector::class => [
            __DIR__ . '/Configuration/TCA/Overrides/tt_content.php',
        ],
    ]);
};
