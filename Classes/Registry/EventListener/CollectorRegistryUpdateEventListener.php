<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Registry\EventListener;

use DigitalMarketingFramework\Collector\Core\CollectorCoreInitialization;
use DigitalMarketingFramework\Collector\Core\InvalidIdentifier\CountingInvalidIdentifierHandler;
use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Typo3\Collector\Core\Domain\Repository\InvalidRequestRepository;
use DigitalMarketingFramework\Typo3\Collector\Core\Typo3CollectorCoreInitialization;

class CollectorRegistryUpdateEventListener extends AbstractCollectorRegistryUpdateEventListener
{
    public function __construct(Typo3CollectorCoreInitialization $initialization)
    {
        parent::__construct($initialization);
    }
}
