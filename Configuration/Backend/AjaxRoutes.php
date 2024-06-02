<?php

use DigitalMarketingFramework\Typo3\Collector\Core\Controller\ContentModifierAjaxController;

return [
    // content modifier
    'digitalmarketingframework_contentmodifier_schema' => [
        'path' => '/digital-marketing-framework-backend/content-modifier/schema',
        'target' => ContentModifierAjaxController::class . '::schemaAction',
    ],
    'digitalmarketingframework_contentmodifier_defaults' => [
        'path' => '/digital-marketing-framework-backend/content-modifier/defaults',
        'target' => ContentModifierAjaxController::class . '::defaultsAction',
    ],
    'digitalmarketingframework_contentmodifier_merge' => [
        'path' => '/digital-marketing-framework-backend/content-modifier/merge',
        'target' => ContentModifierAjaxController::class . '::mergeAction',
    ],
    'digitalmarketingframework_contentmodifier_split' => [
        'path' => '/digital-marketing-framework-backend/content-modifier/split',
        'target' => ContentModifierAjaxController::class . '::splitAction',
    ],
];
