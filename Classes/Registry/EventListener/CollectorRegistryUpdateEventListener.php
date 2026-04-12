<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Registry\EventListener;

use DigitalMarketingFramework\Typo3\Collector\Core\Typo3CollectorCoreInitialization;

class CollectorRegistryUpdateEventListener extends AbstractCollectorRegistryUpdateEventListener
{
    public function __construct(
        Typo3CollectorCoreInitialization $initialization,
    ) {
        parent::__construct($initialization);
    }
}
